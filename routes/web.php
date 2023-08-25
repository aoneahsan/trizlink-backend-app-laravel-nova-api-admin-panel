<?php

use App\Http\Controllers\Zaions\TestingController;
use App\Mail\sendmail;
use App\Models\ZLink\ShortLinks\ShortLink;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Enums\RoleTypesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/z-testing', [TestingController::class, 'zTestingRouteRes']);
Route::get('/z-testing', function () {

    // [$urlSafeEncodedId, $uniqueId] = ZHelpers::zGenerateAndEncryptUniqueId();

    // dd($urlSafeEncodedId, $uniqueId);
});

Route::redirect('/', config('nova.path'));

// Route::get('/send-mail', function () {
//     Mail::to('invalid@invalid.com')->send(new sendmail());
//     dd('okay');
// });
