<?php

namespace App\Http\Controllers\Zaions\Workspace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Workspace\WorkspaceMemberResource;
use App\Http\Resources\Zaions\WorkSpace\WorkSpaceResource;
use App\Models\Default\WorkSpace;
use App\Models\Default\WorkspaceMember;
use App\Models\Default\workspaceMembers;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class WorkspaceMemberController extends Controller
{

    public function viewWorkspaceMembers(Request $request, $workspaceId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_workspace_members->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => WorkspaceMemberResource::collection($workspace->members)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['workspace not found!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    // 
    public function collaboratedWorkspaces(Request $request)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_workspace_members->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $result = $currentUser->asMember()->get();

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => WorkSpaceResource::collection($result),
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    // 
    public function collaboratedWorkspaceRole(Request $request, $workspaceId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_workspace_members->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['workspace not found!']
                ]);
            }

            // Role::where('id', $roleId)->first();

            // $role->permissions()

            // $result = $workspace->members()->where('user_id', $userId)->get();
            // $result = $user->asMember()->where('work_space_id', $workspace->id)->first();
            $result = workspaceMembers::where('user_id', $currentUser->id)->get();

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'is_success' => true,
                        'result' => $result,
                    ]
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => [
                        'is_success' => false,
                        'result' => $result
                    ]
                ]);
            }
        } catch (\Throwable $th) {
        }
    }

    //
    public function attachMember(Request $request, $workspaceId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::attach_workspace_members->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['workspace not found!']
                ]);
            }

            $request->validate([
                'userId' => 'required|integer',
                'roleId' => 'required|integer'
            ]);

            $result =
                $workspace->members()->attach(
                    $currentUser->id,
                    [
                        'roleId' => $request->has('roleId') ? $request->roleId : null,
                        'user_id' => $request->has('userId') ? $request->userId : null
                    ]
                );

            if ($result === null) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'attached' => true,
                    ]
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => [
                        'attached' => false,
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    // 
    public function detachMember(Request $request, $workspaceId, $memberId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::detach_workspace_members->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $workspaceId)->first();

            if (!$workspace) {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['workspace not found!']
                ]);
            }

            $result =
                $workspace->members()->detach($memberId);

            if ($result === 1) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'detached' => true,
                    ]
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => [
                        'detached' => false,
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
