<?php

use App\Http\Controllers\Zaions\TestingController;
use App\Models\ZLink\ShortLinks\ShortLink;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Helpers\ZHelpers;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/z-testing', [TestingController::class, 'zTestingRouteRes']);
// Route::get('/z-testing', function () {

//     $generatedShortUrlPath = ZHelpers::zGenerateRandomString();
//     $checkShortUrlPath = ShortLink::where('shortUrlPath', $generatedShortUrlPath)->exists();


//     dd($checkShortUrlPath);
// });

Route::redirect('/', config('nova.path'));
