<?php

namespace App\Http\Controllers\Zaions\WorkSpace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\WorkSpace\WSMemberResource;
use App\Mail\MemberInvitationMail;
use App\Models\Default\Notification\UserNotificationSetting;
use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Notifications\Workspace\Team\WSTeamMemberInvitation;
use App\Zaions\Enums\NotificationTypeEnum;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\PlanFeatures;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Enums\SignUpTypeEnum;
use App\Zaions\Enums\WSEnum;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
use App\Zaions\Enums\WSPermissionsEnum;
use App\Zaions\Helpers\ZAccountHelpers;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class WSMemberController extends Controller
{

    //
    public function getAllInvitationData(Request $request, $type, $uniqueId)
    {
        try {
            $currentUser = $request->user();

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_ws_member->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to viewAny member.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            if ($workspace) {
                $items = WSTeamMember::where('workspaceId', $workspace->id)->get();
                $itemsCount = WSTeamMember::where('workspaceId', $workspace->id)->count();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'items' => WSMemberResource::collection($items),
                    'itemsCount' => $itemsCount
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    //
    public function sendInvitation(Request $request, $type, $uniqueId)
    {
        try {
            $currentUser = $request->user();
            
            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::send_invitation_ws_member->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to viewAny member.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::send_invitation_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }
            
            if(!$workspace){
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['workspace not found!']
                ]);
            }

            $itemsCount = WSTeamMember::where('workspaceId', $workspace->id)->count();
            $wsMembersLimit = ZAccountHelpers::currentUserServicesLimits($currentUser, PlanFeatures::members->value, $itemsCount);

            
            if ($wsMembersLimit === true) {
                $request->validate([
                    'email' => 'required|string|max:65',
                    'role' => 'required|string|max:65',
    
                    'sortOrderNo' => 'nullable|integer',
                    'isActive' => 'nullable|boolean',
                    'extraAttributes' => 'nullable|json',
                ]);

                $item = WSTeamMember::where('email', $request->email)->where('workspaceId', $workspace->id)->first();

                if ($item) {
                    return ZHelpers::sendBackBadRequestResponse([
                        'email' => ['invitation with this email already exist.']
                    ]);
                }

                $role = Role::where('name', $request->role)->first();

                if ($role) {
                    $invitedUserExistsInDB = User::where('email', $request->email)->first();

                    if (!$invitedUserExistsInDB) {
                        // If user does not exists in our db then making a invite type account.
                        $user = User::create([
                            'uniqueId' => uniqid(),
                            'email' => $request->email,
                            'signUpType' => SignUpTypeEnum::invite->value
                        ]);

                        $userRole = Role::where('name', RolesEnum::user->name)->get();

                        $user->assignRole($userRole);
                    }

                    $memberUser = User::where('email', $request->email)->first();

                    // WorkspaceInviteLinkToken
                    [$urlSafeEncodedId, $uniqueId] = ZHelpers::zGenerateAndEncryptUniqueId();
                    $resendAllowedAfter = Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();
                    $wsTeamMemberInvite = WSTeamMember::create([
                        'uniqueId' => uniqid(),
                        'wilToken' => $uniqueId,
                        'resendAllowedAfter' => $resendAllowedAfter,
                        'inviterId' => $currentUser->id,
                        'workspaceId' => $workspace->id,
                        'memberRoleId' => $role->id,
                        'memberId' => $memberUser->id,

                        'email' => $request->has('email') ? $request->email : null,
                        'accountStatus' => WSMemberAccountStatusEnum::pending->value,
                        'invitedAt' => Carbon::now($currentUser->getUserTimezoneAttribute())
                    ]);

                    if ($wsTeamMemberInvite) {
                        $memberNotificationSetting = UserNotificationSetting::where('userId', $memberUser->id)->first();

                        // Send the invitation mail to the memberUser.
                        if (
                            ($memberNotificationSetting && $memberNotificationSetting->invitationNotification['email'] === true) || $memberUser->signUpType === SignUpTypeEnum::invite->value
                        ) {
                            Mail::send(new MemberInvitationMail(
                                $currentUser,
                                $memberUser,
                                $workspace,
                                $urlSafeEncodedId
                            ));
                        }



                        // Send notification to the memberUser.
                        if (
                            ($memberNotificationSetting && $memberNotificationSetting->invitationNotification['inApp'] === true) || $memberUser->signUpType === SignUpTypeEnum::invite->value
                        ) {
                            $message = 'You have received a invitation to join workspace "' . $workspace->title . '" by "' .  $currentUser->username . '".';
                            $data = [
                                'userId' => $memberUser->id,
                                'message' => $message,
                                'inviter' => $currentUser->username,
                                'inviterUserId' => $currentUser->id,
                                'wsTeamMemberInviteId' => $wsTeamMemberInvite->uniqueId
                            ];

                            $memberUser->notify(new WSTeamMemberInvitation($data, $memberUser, NotificationTypeEnum::personal));
                        }

                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new WSMemberResource($wsTeamMemberInvite),
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([]);
                    }
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['invalid role!']
                    ]);
                }
            }else {
                return ZHelpers::sendBackInvalidParamsResponse([
                    'item' => ['You have reached the limit of members you can invite.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    // 
    public function resendInvitation(Request $request, $type, $uniqueId, $itemId)
    {
        try {
            //code...
            $currentUser = $request->user();

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::resend_invitation_ws_member->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to viewAny member.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::resend_invitation_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            if ($workspace) {

                $invitation = WSTeamMember::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                if ($invitation) {

                    // WorkspaceInviteLinkToken
                    [$urlSafeEncodedId, $uniqueId] = ZHelpers::zGenerateAndEncryptUniqueId();

                    $resendAllowedAfter = Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();

                    $invitation->update([
                        'wilToken' => $uniqueId,
                        'resendAllowedAfter' => $resendAllowedAfter,
                        'invitedAt' => Carbon::now($currentUser->getUserTimezoneAttribute()),
                        'accountStatus' =>  WSMemberAccountStatusEnum::resend->value,
                    ]);

                    $invitation = WSTeamMember::where('uniqueId', $itemId)->first();

                    $memberUser = User::where('email', $invitation->email)->first();

                    $memberNotificationSetting = UserNotificationSetting::where('userId', $memberUser->id)->first();


                    // Send the invitation mail to the memberUser.
                    if (
                        ($memberNotificationSetting && $memberNotificationSetting->invitationNotification['email'] === true) || $memberUser->signUpType === SignUpTypeEnum::invite->value
                    ) {
                        Mail::send(new MemberInvitationMail($currentUser, $memberUser,  $workspace,  $urlSafeEncodedId));
                    }


                    // Send notification to the memberUser.
                    if (
                        ($memberNotificationSetting && $memberNotificationSetting->invitationNotification['inApp'] === true) || $memberUser->signUpType === SignUpTypeEnum::invite->value
                    ) {
                        $message = 'You have received a invitation to join workspace "' . $workspace->title . '" by "' . $currentUser->username . '".';
                        $data = [
                            'userId' => $memberUser->id,
                            'message' => $message,
                            'inviter' => $currentUser->username,
                            'inviterUserId' => $currentUser->id,
                            'wsTeamMemberInviteId' => $invitation->uniqueId
                        ];

                        $memberUser->notify(new WSTeamMemberInvitation($data, $memberUser, NotificationTypeEnum::personal));
                    }

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WSMemberResource($invitation),
                    ]);
                } else {
                    ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Invitation not found.']
                    ]);
                }
            } else {
                ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Workspace not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function getInvitationData(Request $request, $type, $uniqueId, $itemId)
    {
        try {
            $currentUser = $request->user();
            
            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_ws_member->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to viewAny member.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::view_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            if ($workspace) {
                $invitation = WSTeamMember::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                if ($invitation) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WSMemberResource($invitation),
                    ]);
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['invitation not found!']
                    ]);
                }
            } else {
                ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Workspace not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function updateInvitationStatus(Request $request, $type, $uniqueId, $invitationId)
    {
        try {
            $currentUser = $request->user();
            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_ws_member->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to viewAny member.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            $request->validate([
                'status' => 'required|string',
            ]);

            if ($workspace) {
                // Getting the invitation.
                $invitation = WSTeamMember::where('uniqueId', $invitationId)->where('workspaceId', $workspace->id)->first();

                // Checking in invitation is not null.
                if ($invitation) {
                    if ($request->status === WSMemberAccountStatusEnum::accepted->value || $request->status === WSMemberAccountStatusEnum::rejected->value) {
                        $message = null;

                        if ($request->status === WSMemberAccountStatusEnum::accepted->value) {
                            $invitation->update([
                                'accountStatus' => WSMemberAccountStatusEnum::accepted->value,
                                'inviteAcceptedAt' => Carbon::now($currentUser->getUserTimezoneAttribute()),
                                'wilToken' => null
                            ]);
                            $message = '"' . $currentUser->username . '"' . ' has accepted your invitation.';
                        }

                        if ($request->status === WSMemberAccountStatusEnum::rejected->value) {
                            $invitation->update([
                                'accountStatus' => WSMemberAccountStatusEnum::rejected->value,
                                'inviteRejectedAt' => Carbon::now($currentUser->getUserTimezoneAttribute()),
                                'wilToken' => null
                            ]);
                            $message = '"' .
                                $currentUser->username . '"' . ' has rejected your invitation.';
                        }


                        $inviterNotificationSetting = UserNotificationSetting::where('userId', $invitation->inviterId)->first();

                        if (($inviterNotificationSetting && $inviterNotificationSetting->invitationNotification['inApp'] === true)) {
                            $data = [
                                'invitee' => $invitation->memberId,
                                'message' => $message,
                                'inviterUserId' => $invitation->inviterId,
                            ];

                            $inviter = User::where('id', $invitation->inviterId)->first();

                            $inviter->notify(new WSTeamMemberInvitation($data, $inviter, NotificationTypeEnum::personal));
                        }
                    }

                    if ($request->status === WSMemberAccountStatusEnum::cancel->value) {
                        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::cancel_invitation_ws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                        $invitation->update([
                            'accountStatus' => WSMemberAccountStatusEnum::cancel->value,
                            'wilToken' => null,
                        ]);
                    }

                    $invitation = WSTeamMember::where('uniqueId', $invitationId)->with('workspace')->first();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WSMemberResource($invitation),
                    ]);
                } else {
                    return ZHelpers::sendBackBadRequestResponse([
                        'invite' => ['invalid invitation!']
                    ]);
                }
            } else {
                ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Workspace not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $type, $uniqueId, $itemId)
    {
        try {
            $currentUser = $request->user();

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_ws_member->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to viewAny member.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::delete_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            if ($workspace) {
                $item = WSTeamMember::where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                if ($item) {
                    $invitee = User::where('email', $item->email)->first();

                    if ($invitee->signUpType === SignUpTypeEnum::invite->value) {
                        $invitee->forceDelete();
                    }

                    $item->forceDelete();
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => ['success' => true]
                    ]);
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Member not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function updateRole(Request $request, $type, $uniqueId, $itemId)
    {
        try {
            $currentUser = $request->user();

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_memberRole_ws_member->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to viewAny member.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_memberRole_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            $request->validate([
                'role' => 'required|string',
            ]);

            if ($workspace) {

                $role = Role::where('name', $request->role)->first();

                if ($role) {
                    $item = WSTeamMember::where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                    if ($item) {
                        $item->update([
                            'memberRoleId' => $role->id,
                        ]);

                        $item = WSTeamMember::where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new WSMemberResource($item),
                        ]);
                    } else {
                        return ZHelpers::sendBackNotFoundResponse([
                            'item' => ['Member not found!']
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['invalid role!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function createShortLinkId(Request $request, $type, $uniqueId, $itemId)
    {
        try {
            $currentUser = $request->user();

            $workspace = null;

            if ($type === WSEnum::personalWorkspace->value) {
                Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_shortUrl_ws_member->name));

                // getting workspace
                $workspace = WorkSpace::where('uniqueId', $uniqueId)->where('userId', $currentUser->id)->first();
            } else if ($type === WSEnum::shareWorkspace->value) {
                # first getting the member from member_table so we can get share workspace
                $member = WSTeamMember::where('uniqueId', $uniqueId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace', 'memberRole')->first();

                if (!$member) {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Share workspace not found!']
                    ]);
                }

                # First of all checking if member has permission to viewAny member.
                Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::create_shortUrl_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

                # $member->inviterId => id of owner of the workspace
                $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->inviterId)->first();
            } else {
                return ZHelpers::sendBackBadRequestResponse([]);
            }

            if ($workspace) {
                $item = WSTeamMember::where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                if ($item) {
                    $shortUrlIdLength = 12;
                    $shortUrlId = ZHelpers::zGenerateRandomString($shortUrlIdLength);

                    $result = $item->update([
                        'shortUrlId' => $shortUrlId,
                    ]);

                    if ($result) {
                        $item = WSTeamMember::where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new WSMemberResource($item),
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Member not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    // Public
    public function validateAndUpdateInvitation(Request $request)
    {
        try {
            // $currentUser = $request->user();

            // Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::invite_ws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'token' => 'required|string',
                'email' => 'nullable|string|max:65'
            ]);

            $token = ZHelpers::zDecryptUniqueId($request->token);

            if ($token) {
                // $memberInvitation = WSTeamMember::where('wilToken', $token)->where('email', $request->email)->first();
                $memberInvitation = WSTeamMember::where('wilToken', $token)->first();

                if ($memberInvitation) {
                    $memberEmail = $memberInvitation->email;


                    if ($request->has('email') && $request->email !== $memberEmail) {
                        return ZHelpers::sendBackForbiddenResponse([
                            'item' => [''],
                        ]);
                    }


                    if ($memberEmail) {
                        $member = User::where('email', $memberEmail)->first();

                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => [
                                // 'invitation' => $memberInvitation,
                                'user' => [
                                    'email' => $member->email,
                                    'signupType' => $member->signUpType,
                                ]
                            ],
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackBadRequestResponse([
                        'token' => ['invalid invitation!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackBadRequestResponse([
                    'token' => ['invalid token!']
                ]);
            }
        } catch (\Throwable $th) {
            if ($th instanceof \Illuminate\Contracts\Encryption\DecryptException) {
                return ZHelpers::sendBackBadRequestResponse([
                    'token' => ['invalid token!']
                ]);
            }
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function shortUrlCheck(Request $request, $shortUrlId)
    {
        try {
            $memberInvitation = WSTeamMember::where('shortUrlId', $shortUrlId)->first();
            if ($memberInvitation) {

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'token' => ZHelpers::zEncryptUniqueId($memberInvitation->wilToken),
                        'success' => true
                    ],
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => [
                        'message' => 'Invitation not found!',
                        'success' => false
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
