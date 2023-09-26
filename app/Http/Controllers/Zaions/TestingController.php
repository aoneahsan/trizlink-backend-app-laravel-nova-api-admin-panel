<?php

namespace App\Http\Controllers\Zaions;

use App\Http\Controllers\Controller;
use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use App\Notifications\TestNotification;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;

class TestingController extends Controller
{
    public function zTestingRouteRes(Request $request)
    {
        // $otp = ZHelpers::generateUniqueNumericOTP();
        // $otpTime =  Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();
        // dd($otpTime > Carbon::now());
        // Test check if user is super admin
        // $user = $request->user();
        dd(Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString());

        // Test - working with Carbon date and time
        // $carbonNow = Carbon::now($request->user()?->userTimezone);
        // $carbonNow = Carbon::now();
        // $dateInfo = [
        //     '$carbonNow' => $carbonNow,
        //     '$carbonNow->hour' => $carbonNow->hour,
        //     '$carbonNow->minute' => $carbonNow->minute,
        //     '$carbonNow->month' => $carbonNow->month,
        //     '$carbonNow->weekOfYear' => $carbonNow->weekOfYear,
        //     '$carbonNow->day' => $carbonNow->day,
        //     '$carbonNow->dayOfWeek' => $carbonNow->dayOfWeek,
        //     '$carbonNow->dayName' => $carbonNow->dayName,
        //     '12 hour format' => ZHelpers::convertTo12Hour($carbonNow)
        // ];

        // dd($dateInfo, $request->user()?->userTimezone);

        // $workspace = WorkSpace::with('pixel')->where('userId', 1)->where(
        //     'uniqueId',
        //     '64737b6d45f34'
        // )->first();

        // dd($workspace, $workspace->pixel);

        // $user = \App\Models\Default\User::where('email', 'ahsan@zaions.com')->first();

        // if ($user) {
        // will just get overwrite by the last value
        // $user->notify(
        //     \Laravel\Nova\Notifications\NovaNotification::make()
        //         ->message('Visit our Zaions website, PLEASE!!!.')
        //         ->action('Visit', \Laravel\Nova\URL::remote('https://zaions.com'))
        //         ->action('Visit1', \Laravel\Nova\URL::remote('https://zaions.com'))
        //         ->action('Visit2', \Laravel\Nova\URL::remote('https://zaions.com'))
        //         ->action('Visit3', \Laravel\Nova\URL::remote('https://zaions.com'))
        //         ->message(
        //             'Visit our Zaions website, PLEASE11!!!.'
        //         )
        //         ->message('Visit our Zaions website, PLEASE22!!!.')
        //         ->icon('eye')
        //         ->type('error')
        //         ->type('success')
        //         ->type('info')
        // );
        // return ZHelpers::sendBackRequestCompletedResponse(['message' => 'Notified']);



        // Test 2
        // send mail, database, and nova notification
        // $user->notify(new TestNotification('ahsan', 'talha'));
        // return ZHelpers::sendBackRequestCompletedResponse(['message' => 'Notified test 2']);

        // get the notifications
        // $notifications = $user->notifications;
        // return ZHelpers::sendBackRequestCompletedResponse(['message' => 'getting Notifications', 'notifications' => $notifications]);
        // } else {
        //     return ZHelpers::sendBackRequestFailedResponse([
        //         'item' => 'Not found!'
        //     ]);
        // }

        // return response()->json('working fine');
    }

    function testingCustomColumnInLNotificationsSend()
    {

        // Code to send notification
        $result = Notification::send(User::where('email', 'ahsan@zaions.com')->first(), new TestNotification('ahsan', 'asad'));

        dd($result);
    }

    function testingCustomColumnInLNotificationsGet()
    {

        // code to get notifications
        $all = DatabaseNotification::all();
        // $selected = DatabaseNotification::where('zlNotificationType', 'testNotification')->get();
        // $selected = DatabaseNotification::where('notifiable_type', 'App\Models\User')->where('notifiable_id', 2)->where('zlNotificationType', 'testNotification')->where('read_at', '!=', null)->get();
        $selected = DatabaseNotification::where('zlNotificationType', 'testNotification')->get();

        dd($all, $selected);
    }
}
