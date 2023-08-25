<?php

namespace App\Http\Controllers\Zaions\Workspace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Workspace\WSTeamMemberResource;
use App\Mail\MemberInvitationMail;
use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use App\Models\Default\WorkspaceTeam;
use App\Models\Default\WSTeamMember;
use App\Notifications\UserAccount\MemberInvitationNotification;
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

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                $team = WorkspaceTeam::where('userId', $currentUser->id)->where('uniqueId', $teamId)->first();

                if ($team) {
                    $request->validate([
                        'email' => 'required|string|max:65',
                        'role' => 'required|string|max:65',

                        'sortOrderNo' => 'nullable|integer',
                        'isActive' => 'nullable|boolean',
                        'extraAttributes' => 'nullable|json',
                    ]);

                    $role = Role::where('name', $request->role)->first();

                    if ($role) {

                        $isMemberExist = User::where('email', $request->email)->first();

                        if (!$isMemberExist) {
                            $user = User::create([
                                'uniqueId' => uniqid(),
                                // 'name' => $request->name,
                                'email' => $request->email,
                                'signUpType' => SignUpTypeEnum::invite->value,
                                // 'password' => Hash::make($request->password),
                            ]);


                            $userRole = Role::where('name', RolesEnum::user->name)->get();

                            $user->assignRole($userRole);
                        }

                        $memberUser = User::where('email', $request->email)->first();



                        // WorkspaceInviteLinkToken
                        [$urlSafeEncodedId, $uniqueId] = ZHelpers::zGenerateAndEncryptUniqueId();

                        // Send the invitation notification to the user, passing the user instance
                        Mail::send(new MemberInvitationMail($currentUser, $memberUser,  $workspace, $team, $urlSafeEncodedId));

                        $result = WSTeamMember::create([
                            'uniqueId' => uniqid(),
                            'wilToken' => $uniqueId,
                            'userId' => $currentUser->id,
                            'workspaceId' => $workspace->id,
                            'teamId' => $team->id,
                            'memberRoleId' => $role->id,

                            'email' => $request->has('email') ? $request->email : null,
                            'accountStatus' => WSMemberAccountStatusEnum::pending->value,
                            'invitedAt' => Carbon::now($currentUser->getUserTimezoneAttribute()),
                        ]);


                        if ($result) {
                            return ZHelpers::sendBackRequestCompletedResponse([
                                'item' => new WSTeamMemberResource($result),
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

    public function validateAndUpdateInvitation(Request $request)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::invite_WSTeamMember->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'email' => 'required|string|max:65',
                'token' => 'required|string'
            ]);

            $token = ZHelpers::zDecryptUniqueId($request->token);

            if ($token) {
                $memberInvitation = WSTeamMember::where('wilToken', $token)->where('email', $request->email)->first();

                if ($memberInvitation) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => $memberInvitation,
                    ]);
                } else {
                    return ZHelpers::sendBackUnauthorizedResponse([]);
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
