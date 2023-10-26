<?php

namespace App\Http\Controllers\Zaions\WorkSpace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\WorkSpace\SharedWSResource;
use App\Http\Resources\Zaions\WorkSpace\WorkSpaceResource;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
use App\Zaions\Enums\WSPermissionsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class SharedWSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            // auth
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_shareWS->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            // check user
            $userId = $currentUser->id;
            $sharedWSs = WSTeamMember::where('memberId', $userId)->where('accountStatus',  '!=', WSMemberAccountStatusEnum::rejected->value)->where('accountStatus',  '!=', WSMemberAccountStatusEnum::leaved->value)->with('workspace')->with('inviter')->get();
            $sharedWSsCount = WSTeamMember::where('memberId', $userId)->with('workspace')->count();


            return ZHelpers::sendBackRequestCompletedResponse(
                [
                    // 'items' => SharedWSResource::collection($sharedWSs),
                    'items' => SharedWSResource::collection($sharedWSs),
                    'count' => $sharedWSsCount
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Update is favorite resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateIsFavorite(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();

            $request->validate([
                'isFavorite' => 'required|boolean',
            ]);

            $item = WSTeamMember::where('uniqueId', $itemId)->where('memberId', $currentUser->id)->where('accountStatus',  WSMemberAccountStatusEnum::accepted->value)->with('workspace')->first();

            if ($item) {

                $item->update([
                    'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : $item->isFavorite,
                ]);

                $item = WSTeamMember::where('uniqueId', $itemId)->where('memberId', $currentUser->id)->where('accountStatus',  '!=', WSMemberAccountStatusEnum::rejected->value)->with('workspace')->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new SharedWSResource($item)
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

    /**
     * Get role and permissions assign to user in this share workspace.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserRoleAndPermissions(Request $request,  $itemId)
    {
        try {
            $currentUser = $request->user();

            $item = WSTeamMember::where('uniqueId', $itemId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('user')->with('memberRole')->first();

            if ($item) {

                $role = Role::where('name', $item->memberRole->name)->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'memberRole' => $role ? $role->name : null,
                        'memberPermissions' => $role->permissions->pluck('name')
                    ]
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Get share workspace info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getShareWSInfoData(Request $request,  $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_shareWS->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $item = WSTeamMember::where('uniqueId', $itemId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->first();


            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => $item->workspace ? new WorkSpaceResource($item->workspace) : null
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

    /**
     * Update share workspace info.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateShareWSInfoData(Request $request, $itemId, $memberId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            // $member->userId => id of owner of the workspace
            $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $member->userId)->first();

            if (!$item) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No workspace found!']
                ]);
            }

            $request->validate([
                'title' => 'required|string|max:200',
                'timezone' => 'nullable|string|max:200',
                'workspaceImage' => 'nullable|string',
            ]);

            $item->update([
                'title' => $request->has('title') ? $request->title : $item->title,
                'timezone' => $request->has('timezone') ? $request->timezone : $item->timezone,
                'workspaceImage' => $request->has('workspaceImage') ? $request->workspaceImage : $item->workspaceImage,
            ]);

            $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $member->userId)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WorkSpaceResource($item)
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

    /**
     * Leave share workspace info.
     *
     * @return \Illuminate\Http\Response
     */
    public function leaveShareWS(Request $request, $itemId, $memberId)
    {
        try {
            $currentUser = $request->user();
            // first getting the member from member we will get share workspace
            $member = WSTeamMember::where('uniqueId', $memberId)->where('memberId', $currentUser->id)->where('accountStatus', WSMemberAccountStatusEnum::accepted->value)->with('workspace')->with('memberRole')->first();

            Gate::allowIf($member->memberRole->hasPermissionTo(WSPermissionsEnum::update_sws_workspace->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if (!$member) {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['Share workspace not found!']
                ]);
            }

            $result = $member->update([
                'accountStatus' => WSMemberAccountStatusEnum::leaved->value,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'success' => true
                    ]
                ]);
            } else {
                return ZHelpers::sendBackBadRequestResponse([
                    'item' => ['Something went wrong here!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
