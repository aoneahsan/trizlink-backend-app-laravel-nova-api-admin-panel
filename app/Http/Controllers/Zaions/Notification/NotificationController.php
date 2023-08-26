<?php

namespace App\Http\Controllers\Zaions\Notification;

use App\Http\Controllers\Controller;
use App\Zaions\Enums\NotificationTypeEnum;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
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

            if ($currentUser->id) {
                if ($type) {
                    if ($type === NotificationTypeEnum::wsTeamMemberInvitation->name) {
                        $allNotification =  DatabaseNotification::where('zlNotificationType', $type)->where('ZLInviteeId', $currentUser->id)->get();
                        $allNotificationCount =  DatabaseNotification::where('zlNotificationType', $type)->where('ZLInviteeId', $currentUser->id)->count();

                        return ZHelpers::sendBackRequestCompletedResponse([
                            'items' => $allNotification,
                            'itemsCount' => $allNotificationCount
                        ]);
                    }

                    $unreadNotifications = $currentUser->unreadNotifications()->where('zlNotificationType', $type)->get();
                    $itemsCount = $currentUser->unreadNotifications()->where('zlNotificationType', $type)->count();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'items' => $unreadNotifications,
                        'itemsCount' => $itemsCount
                    ]);
                } else {
                    ZHelpers::sendBackInvalidParamsResponse([
                        'type' => 'param notification type is required.'
                    ]);
                }
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

    public function getAllInvitationRequestNotification(Request $request, $type)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_notification->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            if ($currentUser->id) {
                $allNotification =  DatabaseNotification::where('zlNotificationType', $type)->where('ZLInviteeId', $currentUser->id)->get();
                $allNotificationCount =  DatabaseNotification::where('zlNotificationType', $type)->where('ZLInviteeId', $currentUser->id)->count();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'items' => $allNotification,
                    'itemsCount' => $allNotificationCount
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
