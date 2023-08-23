<!-- < ?php

use App\Http\Controllers\Zaions\Auth\AuthController;
use App\Http\Controllers\Zaions\StaticPageController;
use App\Http\Controllers\Zaions\Testing\TestController;
use App\Http\Controllers\Zaions\User\UserController;
use App\Http\Controllers\Zaions\WorkSpace\WorkSpaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['api'])->name('zaions.')->prefix('zaions/v1')->group(function () {
    Route::controller(TestController::class)->group(function () {
        Route::get('/notify-user', 'notifyUser');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/verify-authentication-status', 'verifyAuthState');
            Route::post('/logout', 'logout');
        });


        Route::controller(UserController::class)->group(function () {
            Route::get('/list-users', 'listUsers');
            Route::get('/user', 'index');
            Route::post('/user', 'updateAccountInfo');
            Route::post('/user/username/check', 'checkIfUsernameIsAvailable');
            Route::post('/user/username/update', 'updateUsername');
            Route::post('/user/delete', 'destroy');
        });
    });
}); -->