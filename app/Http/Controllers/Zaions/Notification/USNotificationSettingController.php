<?php

namespace App\Http\Controllers\Zaions\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Notification\USNotificationSettingResource;
use App\Models\Default\Notification\UserNotificationSetting;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class USNotificationSettingController extends Controller
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

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_USNotificationSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $itemsCount = UserNotificationSetting::where('userId', $currentUser->id)->count();
            $items = UserNotificationSetting::where('userId', $currentUser->id)->get();

            return ZHelpers::sendBackRequestCompletedResponse([
                'items' => USNotificationSettingResource::collection($items),
                'itemsCount' => $itemsCount
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

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_USNotificationSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'invitationNotification' => 'required|json',
                'newDeviceLogin' => 'required|json',
                'passwordReset' => 'required|json',
                'otherNotification' => 'required|json',
                'browser' => 'required|json',
                'email' => 'required|json',
                'sms' => 'required|json',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            $result = UserNotificationSetting::create([
                'uniqueId' => uniqid(),
                'userId' => $currentUser->id,

                'invitationNotification' => $request->has('invitationNotification') ? ZHelpers::zJsonDecode($request->invitationNotification) : null,
                'newDeviceLogin' => $request->has('newDeviceLogin') ? ZHelpers::zJsonDecode($request->newDeviceLogin) : null,
                'passwordReset' => $request->has('passwordReset') ? ZHelpers::zJsonDecode($request->passwordReset) : null,
                'otherNotification' => $request->has('otherNotification') ? ZHelpers::zJsonDecode($request->otherNotification) : null,
                'browser' => $request->has('browser') ? ZHelpers::zJsonDecode($request->browser) : null,
                'email' => $request->has('email') ? ZHelpers::zJsonDecode($request->email) : null,
                'sms' => $request->has('sms') ? ZHelpers::zJsonDecode($request->sms) : null,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new USNotificationSettingResource($result)
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
    public function show(Request $request)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_USNotificationSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $item = UserNotificationSetting::where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new USNotificationSettingResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Settings not found!']
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
    public function update(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_USNotificationSettings->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $item = UserNotificationSetting::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                $request->validate([
                    'invitationNotification' => 'nullable|json',
                    'newDeviceLogin' => 'nullable|json',
                    'passwordReset' => 'nullable|json',
                    'otherNotification' => 'nullable|json',
                    'browser' => 'nullable|json',
                    'email' => 'nullable|json',
                    'sms' => 'nullable|json',
    
                    'sortOrderNo' => 'nullable|integer',
                    'isActive' => 'nullable|boolean',
                    'extraAttributes' => 'nullable|json',
                ]);
                
                $item->update([
                    'invitationNotification' => $request->has('invitationNotification') ? ZHelpers::zJsonDecode($request->invitationNotification) : $item->invitationNotification,
                    'newDeviceLogin' => $request->has('newDeviceLogin') ? ZHelpers::zJsonDecode($request->newDeviceLogin) : $item->newDeviceLogin,
                    'passwordReset' => $request->has('passwordReset') ? ZHelpers::zJsonDecode($request->passwordReset) : $item->passwordReset,
                    'otherNotification' => $request->has('otherNotification') ? ZHelpers::zJsonDecode($request->otherNotification) : $item->otherNotification,
                    'browser' => $request->has('browser') ? ZHelpers::zJsonDecode($request->browser) : $item->browser,
                    'email' => $request->has('email') ? ZHelpers::zJsonDecode($request->email) : $item->email,
                    'sms' => $request->has('sms') ? ZHelpers::zJsonDecode($request->sms) : $item->sms,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->sortOrderNo,
                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : $item->extraAttributes,
                ]);

                $item = UserNotificationSetting::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();


                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new USNotificationSettingResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Settings not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
