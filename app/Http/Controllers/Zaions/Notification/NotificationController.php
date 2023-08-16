<?php

namespace App\Http\Controllers\Zaions\Notification;

use App\Http\Controllers\Controller;
use App\Zaions\Enums\NotificationTypeEnum;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unReadNotification(Request $request, $type)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_notification->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);
            $NotificationType = NotificationTypeEnum::newDeviceLogin->name;
            if ($currentUser->id) {
                $unreadNotifications = $currentUser->unreadNotifications()->get();

                return response()->json([
                    'success' => true,
                    'errors' => [],
                    'message' => 'Request Completed Successfully!',
                    'data' => [
                        'items' => $unreadNotifications,
                        // 'itemsCount' => $itemsCount
                    ],
                    'status' => 200
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function markAsRead(Request $request, $type, $id)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_notification->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if ($currentUser->id) {
                $currentNotification = $currentUser->notifications()->where('id', $id)->first();

                if ($currentNotification) {
                    if ($currentNotification->read_at === null) {
                        $currentNotification->update(['read_at' => now()]);
                    }
                    // $updatedNotification = $currentUser->readNotifications()->where('id', $id)->first();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => [
                            'success' => true
                        ]
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Not found']
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function markAllAsRead(Request $request, $type)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_notification->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if ($currentUser->id) {
                $allNotification = $currentUser->unreadNotifications;

                if ($allNotification) {
                    $allNotification->markAsRead();

                    // $updatedNotifications = $currentUser->notifications;

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => [
                            'success' => true
                        ]
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Not found']
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
