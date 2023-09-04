<?php

namespace App\Http\Controllers\Zaions\Workspace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Workspace\WorkspaceMemberResource;
use App\Http\Resources\Zaions\Workspace\WSTeamMemberResource;
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
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Notification;

class WSTeamMemberController extends Controller
{

    //
    public function getAllInvitationData(Request $request, $workspaceId, $teamId)
    {
        try {
            $currentUser = $request->user();
            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_WSTeamMember->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                $team = WorkspaceTeam::where('userId', $currentUser->id)->where('uniqueId', $teamId)->first();

                if ($team) {
                    $items = WSTeamMember::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->where('teamId', $team->id)->get();
                    $itemsCount = WSTeamMember::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->where('teamId', $team->id)->count();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'items' => WSTeamMemberResource::collection($items),
                        'itemsCount' => $itemsCount
                    ]);
                } else {
                    return ZHelpers::sendBackNotFoundResponse([
                        'item' => ['team not found!']
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
    public function sendInvitation(Request $request, $workspaceId, $teamId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::invite_WSTeamMember->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'email' => 'required|string|max:65',
                'role' => 'required|string|max:65',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            $requestedRole = $request->role;

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                $team = WorkspaceTeam::where('userId', $currentUser->id)->where('uniqueId', $teamId)->first();

                if ($team) {
                    $role = Role::where('name', $request->role)->first();

                    if ($role) {
                        $invitedUserExistsInDB = User::where('email', $request->email)->first();

                        if (!$invitedUserExistsInDB) {
                            $user = User::create([
                                'uniqueId' => uniqid(),
                                // 'name' => $request->name,
                                'email' => $request->email,
                                'signUpType' => SignUpTypeEnum::invite->value,
                            ]);


                            $userRole = Role::where('name', RolesEnum::user->name)->get();

                            $user->assignRole($userRole);
                        }

                        $memberUser = User::where('email', $request->email)->first();



                        // WorkspaceInviteLinkToken
                        [$urlSafeEncodedId, $uniqueId] = ZHelpers::zGenerateAndEncryptUniqueId();

                        $wsTeamMemberInvite = WSTeamMember::create([
                            'uniqueId' => uniqid(),
                            'wilToken' => $uniqueId,
                            'userId' => $currentUser->id,
                            'workspaceId' => $workspace->id,
                            'teamId' => $team->id,
                            'memberRoleId' => $role->id,

                            'email' => $request->has('email') ? $request->email : null,
                            'accountStatus' => WSMemberAccountStatusEnum::pending->value,
                            'invitedAt' => Carbon::now($currentUser->getUserTimezoneAttribute())
                        ]);


                        if ($wsTeamMemberInvite) {
                            // Send the invitation mail to the memberUser.
                            Mail::send(new MemberInvitationMail($currentUser, $memberUser,  $workspace, $team, $urlSafeEncodedId));

                            $message = 'You have received a invitation to join team "' . $team->title . '" in workspace "' . $workspace->title . '" by "' . $currentUser->name . '".';

                            $data = [
                                'userId' => $memberUser->id,
                                'message' => $message,
                                'inviter' => $currentUser->name,
                                'inviterUserId' => $currentUser->id,
                                'teamId' => $team->id, // Team details
                                'teamName' => $team->name,
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
                        'item' => ['team not found!']
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

    public function getInvitationData(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_WSTeamMember->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


            $invitation = WSTeamMember::where('uniqueId', $itemId)->first();

            if ($invitation) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WSTeamMemberResource($invitation),
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['invitation not found!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }



    public function updateInvitationStatus(Request $request, $invitationId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_WSTeamMember->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'status' => 'required|string',
            ]);

            // Getting the invitation.
            $invitation = WSTeamMember::where('uniqueId', $invitationId)->first();

            // Checking in invitation is not null.
            if ($invitation) {
                if ($invitation->inviteAcceptedAt === null && $invitation->inviteRejectedAt === null) {
                    $message = null;

                    if ($request->status === WSMemberAccountStatusEnum::accepted->value) {
                        $invitation->update([
                            'accountStatus' => WSMemberAccountStatusEnum::accepted->value,
                            'inviteAcceptedAt' => Carbon::now($currentUser->getUserTimezoneAttribute()),
                        ]);
                        $message = $currentUser->name . ' has excepted your invitation.';


                        // Send notification to the memberUser.
                    }
                    if ($request->status === WSMemberAccountStatusEnum::rejected->value) {
                        $invitation->update([
                            'accountStatus' => WSMemberAccountStatusEnum::rejected->value,
                            'inviteRejectedAt' => Carbon::now($currentUser->getUserTimezoneAttribute()),
                        ]);
                        $message =
                            $currentUser->name . ' has rejected your invitation.';
                    }

                    $data = [
                        'invitee' => $invitation->memberId,
                        'message' => $message,
                        'inviterUserId' => $invitation->userId,
                        'teamId' => $invitation->teamId, // Team details
                    ];
                    $inviter = User::where('id', $invitation->userId)->first();

                    $inviter->notify(new WSTeamMemberInvitation($data, $inviter, NotificationTypeEnum::wsMemberInviteAction));
                }

                $invitation = WSTeamMember::where('uniqueId', $invitationId)->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WSTeamMemberResource($invitation),
                ]);
            } else {
                return ZHelpers::sendBackBadRequestResponse([
                    'invite' => ['invalid invitation!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }


    // Public
    public function validateAndUpdateInvitation(Request $request)
    {
        try {
            // $currentUser = $request->user();

            // Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::invite_WSTeamMember->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                // 'email' => 'required|string|max:65',
                'token' => 'required|string'
            ]);

            $token = ZHelpers::zDecryptUniqueId($request->token);

            if ($token) {
                // $memberInvitation = WSTeamMember::where('wilToken', $token)->where('email', $request->email)->first();
                $memberInvitation = WSTeamMember::where('wilToken', $token)->first();

                if ($memberInvitation) {
                    $memberEmail = $memberInvitation->email;

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
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
