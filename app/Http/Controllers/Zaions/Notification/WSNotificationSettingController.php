<?php

namespace App\Http\Controllers\Zaions\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Notification\WSNotificationSettingResource;
use App\Models\Default\Notification\WSNotificationSetting;
use App\Models\Default\WorkSpace;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WSNotificationSettingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $workspaceId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_WSNotificationSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $currentUser->id)->first();

            if ($workspace) {
                $request->validate([
                    'notificationOnProfile' => 'nullable|boolean',
                    'allowPushNotification' => 'nullable|boolean',

                    'sortOrderNo' => 'nullable|integer',
                    'isActive' => 'nullable|boolean',
                    'extraAttributes' => 'nullable|json',
                ]);

                $result = WSNotificationSetting::create([
                    'uniqueId' => uniqid(),
                    'userId' => $currentUser->id,
                    'workspaceId' => $workspace->id,

                    'type' => $request->has('type') ? $request->type : null,
                    'notificationOnProfile' => $request->has('notificationOnProfile') ? $request->notificationOnProfile : null,
                    'allowPushNotification' => $request->has('allowPushNotification') ? $request->allowPushNotification : null,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                    'isActive' => $request->has('isActive') ? $request->isActive : null,
                    'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
                ]);

                if ($result) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WSNotificationSettingResource($result)
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([]);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $workspaceId, $type)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_WSNotificationSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                $item = WSNotificationSetting::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->where('type', $type)->first();

                if ($item) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WSNotificationSettingResource($item)
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Workspace settings not found!']
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $workspaceId, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_WSNotificationSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $workspace = WorkSpace::where('uniqueId', $workspaceId)->first();

            if ($workspace) {
                $item = WSNotificationSetting::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                if ($item) {
                    $request->validate([
                        'notificationOnProfile' => 'nullable|boolean',
                        'allowPushNotification' => 'nullable|boolean',

                        'sortOrderNo' => 'nullable|integer',
                        'isActive' => 'nullable|boolean',
                        'extraAttributes' => 'nullable|json',
                    ]);

                    $item->update([
                        'notificationOnProfile' => $request->has('notificationOnProfile') ? $request->notificationOnProfile : $item->notificationOnProfile,
                        'allowPushNotification' => $request->has('allowPushNotification') ? $request->allowPushNotification : $item->allowPushNotification,

                        'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->sortOrderNo,
                        'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                        'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : $item->extraAttributes,
                    ]);


                    $item = WSNotificationSetting::where('userId', $currentUser->id)->where('workspaceId', $workspace->id)->where('uniqueId', $itemId)->first();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new WSNotificationSettingResource($item)
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Workspace settings not found!']
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
