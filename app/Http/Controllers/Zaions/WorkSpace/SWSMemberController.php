<?php

namespace App\Http\Controllers\Zaions\Workspace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Workspace\WorkspaceMemberResource;
use App\Http\Resources\Zaions\Workspace\WSTeamMemberResource;
use App\Jobs\Zaions\Mail\SendMailJob;
use App\Mail\MemberInvitationMail;
use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use App\Models\Default\WorkspaceTeam;
use App\Models\Default\WSTeamMember;
use App\Notifications\UserAccount\MemberInvitationNotification;
use App\Notifications\Workspace\Team\WSTeamMemberInvitation;
use App\Zaions\Enums\NotificationTypeEnum;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Enums\SignUpTypeEnum;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
use App\Zaions\Enums\WSPermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Notification;

class SWSMemberController extends Controller
{

    //
    public function getAllInvitationData(Request $request, $memberId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::viewAny_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

            if ($workspace) {
                $items = WSTeamMember::where('workspaceId', $workspace->id)->get();
                $itemsCount = WSTeamMember::where('workspaceId', $workspace->id)->count();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'items' => WSTeamMemberResource::collection($items),
                    'itemsCount' => $itemsCount
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    //
    public function sendInvitation(Request $request, $memberId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::send_invitation_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            $request->validate([
                'email' => 'required|string|max:65',
                'role' => 'required|string|max:65',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

            if ($workspace) {
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
                        $user = User::create([
                            'uniqueId' => uniqid(),
                            // 'name' => $request->name,
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
                        // Send the invitation mail to the memberUser.
                        Mail::send(new MemberInvitationMail(
                            $currentUser,
                            $memberUser,
                            $workspace,
                            $urlSafeEncodedId
                        ));
                        // SendMailJob::dispatch(
                        //     $currentUser,
                        //     $memberUser,
                        //     $workspace,
                        //     $team,
                        //     $urlSafeEncodedId
                        // );

                        $message = 'You have received a invitation to join workspace "' . $workspace->title . '" by "' . $currentUser->username . '".';

                        $data = [
                            'userId' => $memberUser->id,
                            'message' => $message,
                            'inviter' => $currentUser->username,
                            'inviterUserId' => $currentUser->id,
                            'wsTeamMemberInviteId' => $wsTeamMemberInvite->uniqueId
                        ];

                        // Send notification to the memberUser.
                        $memberUser->notify(new WSTeamMemberInvitation($data, $memberUser, NotificationTypeEnum::wsTeamMemberInvitation));

                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new WSTeamMemberResource($wsTeamMemberInvite),
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([]);
                    }
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['invalid role!']
                    ]);
                }
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
    public function resendInvitation(Request $request, $memberId, $itemId)
    {
        try {
            //code...
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::resend_invitation_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

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

                    // Send the invitation mail to the memberUser.
                    Mail::send(new MemberInvitationMail($currentUser, $memberUser,  $workspace,  $urlSafeEncodedId));

                    $message = 'You have received a invitation to join workspace "' . $workspace->title . '" by "' . $currentUser->username . '".';

                    $data = [
                        'userId' => $memberUser->id,
                        'message' => $message,
                        'inviter' => $currentUser->username,
                        'inviterUserId' => $currentUser->id,
                        'wsTeamMemberInviteId' => $invitation->uniqueId
                    ];

                    // Send notification to the memberUser.
                    $memberUser->notify(new WSTeamMemberInvitation($data, $memberUser, NotificationTypeEnum::wsTeamMemberInvitation));

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WSTeamMemberResource($invitation),
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

    public function getInvitationData(Request $request, $memberId, $itemId)
    {
        try {
            //code...
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::view_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

            if ($workspace) {
                $invitation = WSTeamMember::where('uniqueId', $itemId)->where('workspaceId', $workspace->id)->first();

                if ($invitation) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WSTeamMemberResource($invitation),
                    ]);
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['invitation not found!']
                    ]);
                }
            } else {
                ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function updateInvitationStatus(Request $request, $memberId, $invitationId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            $request->validate([
                'status' => 'required|string',
            ]);

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

            if($workspace){
                // Getting the invitation.
                $invitation = WSTeamMember::where('uniqueId', $invitationId)->where('workspaceId', $workspace->id)->first();
    
                // Checking in invitation is not null.
                if ($invitation) {
                    $message = null;
    
                    if ($request->status === WSMemberAccountStatusEnum::accepted->value) {
                        $invitation->update([
                            'accountStatus' => WSMemberAccountStatusEnum::accepted->value,
                            'inviteAcceptedAt' => Carbon::now($currentUser->getUserTimezoneAttribute()),
                        ]);
                        $message = '"' . $currentUser->username . '"' . ' has accepted your invitation.';
                    }
    
                    if ($request->status === WSMemberAccountStatusEnum::rejected->value) {
                        $invitation->update([
                            'accountStatus' => WSMemberAccountStatusEnum::rejected->value,
                            'inviteRejectedAt' => Carbon::now($currentUser->getUserTimezoneAttribute()),
                        ]);
                        $message = '"' .
                            $currentUser->username . '"' . ' has rejected your invitation.';
                    }
    
                    $data = [
                        'invitee' => $invitation->memberId,
                        'message' => $message,
                        'inviterUserId' => $invitation->userId,
                    ];
    
                    $inviter = User::where('id', $invitation->userId)->first();
    
                    $inviter->notify(new WSTeamMemberInvitation($data, $inviter, NotificationTypeEnum::wsMemberInviteAction));
    
                    if ($request->status === WSMemberAccountStatusEnum::cancel->value) {
                        $invitation->update([
                            'accountStatus' => WSMemberAccountStatusEnum::cancel->value,
                            'wilToken' => null,
                        ]);
                    }
    
                    $invitation = WSTeamMember::where('uniqueId', $invitationId)->with('workspace')->first();
    
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WSTeamMemberResource($invitation),
                    ]);
                } else {
                    return ZHelpers::sendBackBadRequestResponse([
                        'invite' => ['invalid invitation!']
                    ]);
                }
            } else {
                ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function createShortLinkId(Request $request,  $memberId, $itemId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            $request->validate([
                'status' => 'required|string',
            ]);

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

            if ($workspace) {
                $item = WSTeamMember::where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                if ($item) {
                    $shortUrlIdLength = 12;
                    $shortUrlId = ZHelpers::zGenerateRandomString($shortUrlIdLength);

                   $result = $item->update([
                        'shortUrlId' => $shortUrlId,
                    ]);

                    if($result){
                        $item = WSTeamMember::where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new WSTeamMemberResource($item),
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['Member not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $memberId, $itemId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::delete_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();

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

    public function updateRole(Request $request, $memberId, $itemId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_role_sws_member->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            $request->validate([
                'role' => 'required|string',
            ]);
            
            // $member->userId => id of owner of the workspace
            $workspace = WorkSpace::where('uniqueId', $member->workspace->uniqueId)->where('userId', $member->userId)->first();
            
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
                            'item' => new WSTeamMemberResource($item),
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
}
