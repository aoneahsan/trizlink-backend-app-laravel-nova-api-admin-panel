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
            $sharedWSs = WSTeamMember::where('memberId', $userId)->where('accountStatus',  '!=', WSMemberAccountStatusEnum::rejected->value)->with('workspace')->get();
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

            return ZHelpers::sendBackRequestCompletedResponse([
                'item' => $item->workspace ? new WorkSpaceResource($item->workspace) : null
            ]);

            if ($item) {
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
}
