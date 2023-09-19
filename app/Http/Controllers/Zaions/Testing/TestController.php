<?php

namespace App\Http\Controllers\Zaions\Testing;

use App\Http\Controllers\Controller;
use App\Models\Default\User;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class TestController extends Controller
{
    public function notifyUser(Request $request)
    {
        $user = User::where('email', 'ahsan@zaions.com')->first();

        if ($user) {
            $user->notify(
                NovaNotification::make()
                    ->message('Your report is ready to download.')
                    ->action('Download', URL::remote('https://example.com/report.pdf'))
                    ->icon('download')
                    ->type('info')
            );
            return ZHelpers::sendBackRequestCompletedResponse(['message' => 'Notified']);
        } else {
            return ZHelpers::sendBackRequestFailedResponse([
                'item' => 'Not found!'
            ]);
        }
    }
}
