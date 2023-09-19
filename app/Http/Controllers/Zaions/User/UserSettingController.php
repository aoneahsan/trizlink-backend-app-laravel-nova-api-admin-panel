<?php

namespace App\Http\Controllers\Zaions\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\User\UserSettingResource;
use App\Models\Default\UserSetting;
use App\Models\Default\WorkSpace;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_USSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $itemsCount = UserSetting::where('userId', $currentUser->id)->count();
            $items = UserSetting::where('userId', $currentUser->id)->with('user')->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => UserSettingResource::collection($items),
                    'itemsCount' => $itemsCount
                ],
                'status' => 200
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_USSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'type' => 'required|string|max:200',
                'settings' => 'nullable|json',
                'workspaceUniqueId' => 'nullable|string|max:200',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);


            $workspaceId = null;

            if ($request->has('workspaceUniqueId')) {
                $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $request->workspaceUniqueId)->first();

                if (!$workspace) {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['workspace not found!']
                    ]);
                } else {
                    $workspaceId = $workspace->id;
                }
            }

            $result = UserSetting::create([
                'uniqueId' => uniqid(),
                'workspaceUniqueId' => $workspaceId,

                'userId' => $currentUser->id,
                'type' => $request->has('type') ? $request->type : null,
                'settings' => $request->has('settings') ? ZHelpers::zJsonDecode($request->settings) : null,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : true,
                'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserSettingResource($result)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $type,)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_USSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $item = UserSetting::where('type', $type)->where('userId', $currentUser->id)->first();


            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserSettingResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => []
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $type)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_USSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'type' => 'required|string|max:200',
                'workspaceUniqueId' => 'nullable|string|max:200',
                'settings' => 'nullable|json',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);


            $item = UserSetting::where('type', $type)->where('userId', $currentUser->id)->first();

            $workspaceId = $item->workspaceUniqueId;

            if ($request->has('workspaceUniqueId')) {
                $workspace = WorkSpace::where('userId', $currentUser->id)->where('uniqueId', $request->workspaceUniqueId)->first();

                if (!$workspace) {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['workspace not found!']
                    ]);
                } else {
                    $workspaceId = $workspace->id;
                }
            }


            if ($item) {
                $item->update([
                    'type' => $request->has('type') ? $request->type : $item->type,
                    'workspaceUniqueId' => $workspaceId,
                    'settings' => $request->has('settings') ? ZHelpers::zJsonDecode($request->settings) : $request->settings,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->isActive,
                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : $request->extraAttributes,
                ]);

                $item = UserSetting::where('type', $type)->where('userId', $currentUser->id)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserSettingResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Setting not found!']
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
    public function destroy(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_USSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


            $item = UserSetting::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => ['success' => true]
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Setting not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
