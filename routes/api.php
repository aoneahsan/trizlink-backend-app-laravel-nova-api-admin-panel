<?php

use App\Http\Controllers\Zaions\Auth\AuthController;
use App\Http\Controllers\Zaions\Common\FileUploadController;
use App\Http\Controllers\Zaions\StaticPageController;
use App\Http\Controllers\Zaions\Testing\TestController;
use App\Http\Controllers\Zaions\User\UserController;
use App\Http\Controllers\Zaions\User\UserSettingController;
use App\Http\Controllers\Zaions\WorkSpace\WorkSpaceController;
use App\Http\Controllers\Zaions\Workspace\WorkspaceMemberController;
use App\Http\Controllers\Zaions\WorkSpace\WorkspaceModalConnectionsController;
use App\Http\Controllers\Zaions\ZLink\Analytics\PixelController;
use App\Http\Controllers\Zaions\ZLink\Analytics\UtmTagController;
use App\Http\Controllers\Zaions\ZLink\Common\ApiKeyController;
use App\Http\Controllers\Zaions\ZLink\Common\FolderController;
use App\Http\Controllers\Zaions\ZLink\Label\LabelController;
use App\Http\Controllers\Zaions\ZLink\LinkInBios\LibBlockController;
use App\Http\Controllers\Zaions\ZLink\LinkInBios\LibPredefinedDataController;
use App\Http\Controllers\Zaions\ZLink\LinkInBios\LinkInBioController;
use App\Http\Controllers\Zaions\ZLink\ShortLinks\ShortLinkController;
use App\Http\Controllers\Zaions\ZLink\ShortLinks\CustomDomainController;
use App\Http\Controllers\Zaions\ZLink\ShortLinks\EmbededWidgetController;
use App\Http\Controllers\Zaions\ZLink\TimeSlot\TimeSlotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['api'])->name('zlink.')->prefix('zlink/v1')->group(function () {
    // Test Routes
    Route::controller(TestController::class)->group(function () {
        Route::get('/notify-user', 'notifyUser');
    });

    // Guest Routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/auth/google/redirect', 'googleRedirect');
        Route::post('/auth/google/callback', 'googleCallback');
    });

    // API - Authenticated Routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // User Auth State Check API
        // Route::middleware([])->group(function () {
        // Auth Routes
        Route::controller(AuthController::class)->group(function () {
            Route::post('/verify-authentication-status', 'verifyAuthState');
            Route::post('/update-active-status', 'updateUserIsActiveStatus');
            Route::post('/logout', 'logout');
        });

        // File Upload Controller APIs
        Route::controller(FileUploadController::class)->group(function () {
            Route::post('/file-upload/getSingleFileUrl', 'getSingleFileUrl');
            Route::post('/file-upload/uploadSingleFile', 'uploadSingleFile');
            Route::put('/file-upload/deleteSingleFile', 'deleteSingleFile');
            Route::post('/file-upload/checkIfSingleFileExists', 'checkIfSingleFileExists');
            Route::post('/file-upload/uploadFiles', 'uploadFiles');

            // Route::post('/file-upload/get-image-from-url/url={ImageUrl}', 'uploadSingleFileFromUrl');
        });

        // User Account Related Routes
        Route::controller(UserController::class)->group(function () {
            Route::get('/list-users', 'listUsers');
            Route::get('/user', 'index');
            Route::get('/user/role/permissions', 'getUserPermissions');
            Route::post('/user', 'updateAccountInfo');
            Route::post('/user/username/check', 'checkIfUsernameIsAvailable');
            Route::post('/user/username/update', 'updateUsername');
            // Route::get('/user/{token}', '')->name('password.reset');
            Route::post('/user/delete', 'destroy');
        });

        // Workspace
        Route::controller(UserSettingController::class)->group(function () {
            Route::get('/user/settings', 'index');
            Route::post('/user/settings', 'store');
            Route::get('/user/settings/{type}/{workspaceUniqueId}', 'show');
            Route::put('/user/settings/{itemId}', 'update');
            Route::delete('/user/settings/{itemId}', 'destroy');
        });

        // Workspace
        Route::controller(WorkSpaceController::class)->group(function () {
            Route::get('/user/workspaces', 'index');
            Route::post('/user/workspaces', 'store');
            Route::get('/user/workspaces/{itemId}', 'show');
            Route::put('/user/workspaces/{itemId}', 'update');
            Route::delete('/user/workspaces/{itemId}', 'destroy');
        });

        // Attach modal (pixel, UTM tag etc.) to workspace.
        Route::controller(WorkspaceModalConnectionsController::class)->group(function () {
            Route::get('/user/wmc/{workspaceId}/modal/{modalType}', 'viewAll');
            Route::post('/user/wmc/{workspaceId}/modal/{modalType}/modalId/{modalId}', 'attach');
            Route::delete('/user/wmc/{workspaceId}/modal/{modalType}/modalId/{modalId}', 'detach');
        });

        // Workspace member
        Route::controller(WorkspaceMemberController::class)->group(function () {
            Route::post('/user/workspace/{workspaceId}/add-member', 'attachMember');
            Route::delete('/user/workspace/{workspaceId}/remove-member/{memberId}', 'detachMember');
            Route::get('/user/workspace/{workspaceId}/members', 'viewWorkspaceMembers');
            Route::get(
                '/user/workspace/user-collaborated',
                'collaboratedWorkspaces'
            );
            Route::get('/user/workspace/{workspaceId}/user-collaborated-role', 'collaboratedWorkspaceRole');
        });

        // ShortLink
        Route::controller(ShortLinkController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/short-links', 'index');
            Route::post('/user/workspaces/{workspaceId}/short-links', 'store');
            Route::get('/user/workspaces/{workspaceId}/short-links/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/short-links/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/short-links/{itemId}', 'destroy');


            Route::get('/user/workspaces/{workspaceId}/sl/is-path-available/{value}', 'checkShortUrlPathAvailable');
        });

        // Pixel
        Route::controller(PixelController::class)->group(function () {
            Route::get('/user/pixel', 'index');
            Route::post('/user/pixel', 'store');
            Route::get('/user/pixel/{itemId}', 'show');
            Route::put('/user/pixel/{itemId}', 'update');
            Route::delete('/user/pixel/{itemId}', 'destroy');
        });


        // UTM Tags
        Route::controller(UtmTagController::class)->group(function () {
            Route::get('/user/utm-tag', 'index');
            Route::post('/user/utm-tag', 'store');
            Route::get('/user/utm-tag/{itemId}', 'show');
            Route::put('/user/utm-tag/{itemId}', 'update');
            Route::delete('/user/utm-tag/{itemId}', 'destroy');
        });

        // API key
        Route::controller(ApiKeyController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/api-key', 'index');
            Route::post('/user/workspaces/{workspaceId}/api-key', 'store');
            Route::get('/user/workspaces/{workspaceId}/api-key/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/api-key/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/api-key/{itemId}', 'destroy');
        });

        // Folder
        Route::controller(FolderController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/folder', 'index');
            Route::post('/user/workspaces/{workspaceId}/folder', 'store');
            // Route::put('/user/workspaces/{workspaceId}/folders/reorder', 'updateSortOrderNo');
            Route::get('/user/workspaces/{workspaceId}/folder/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/folder/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/folder/{itemId}', 'destroy');
            Route::get('/user/workspaces/{workspaceId}/get/shortLink/folders', 'getShortLinksFolders');
            Route::get('/user/workspaces/{workspaceId}/get/linkInBio/folders', 'getLinkInBioFolders');
        });

        // Time slot
        Route::controller(TimeSlotController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/time-slot', 'index');
            Route::post('/user/workspaces/{workspaceId}/time-slot', 'store');
            Route::get('/user/workspaces/{workspaceId}/time-slot/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/time-slot/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/time-slot/{itemId}', 'destroy');
        });

        // Label
        Route::controller(LabelController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/label', 'index');
            Route::post('/user/workspaces/{workspaceId}/label', 'store');
            Route::get('/user/workspaces/{workspaceId}/label/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/label/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/label/{itemId}', 'destroy');
        });

        // ShortLink Custom domain
        Route::controller(CustomDomainController::class)->group(function () {
            Route::get('/user/ws/{workspaceId}/sl/{shortLinkId}/custom-domain', 'index');
            Route::post('/user/ws/{workspaceId}/sl/{shortLinkId}/custom-domain', 'store');
            Route::get('/user/ws/{workspaceId}/sl/{shortLinkId}/custom-domain/{itemId}', 'show');
            Route::put('/user/ws/{workspaceId}/sl/{shortLinkId}/custom-domain/{itemId}', 'update');
            Route::delete('/user/ws/{workspaceId}/sl/{shortLinkId}/custom-domain/{itemId}', 'destroy');
        });

        // ShortLink Embeded widget
        Route::controller(EmbededWidgetController::class)->group(function () {
            Route::get('/user/ws/{workspaceId}/sl/{shortLinkId}/embeded-widget', 'index');
            Route::post('/user/ws/{workspaceId}/sl/{shortLinkId}/embeded-widget', 'store');
            Route::get('/user/ws/{workspaceId}/sl/{shortLinkId}/embeded-widget/{itemId}', 'show');
            Route::put('/user/ws/{workspaceId}/sl/{shortLinkId}/embeded-widget/{itemId}', 'update');
            Route::delete('/user/ws/{workspaceId}/sl/{shortLinkId}/embeded-widget/{itemId}', 'destroy');
        });

        // LinkInBio
        Route::controller(LinkInBioController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/link-in-bio', 'index');
            Route::post('/user/workspaces/{workspaceId}/link-in-bio', 'store');
            Route::get('/user/workspaces/{workspaceId}/link-in-bio/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/link-in-bio/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/link-in-bio/{itemId}', 'destroy');
        });

        // LinkInBio block
        Route::controller(LibBlockController::class)->group(function () {
            Route::get('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block', 'index');
            Route::post('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block', 'store');
            Route::get('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block/{itemId}', 'show');
            Route::put('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block/{itemId}', 'update');
            Route::delete('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block/{itemId}', 'destroy');
        });

        // LinkInBio pre defined data
        Route::controller(LibPredefinedDataController::class)->group(function () {
            Route::get('/user/lib-pre-dd/{pddType}', 'index');
            Route::post('/user/lib-pdd/{pddType}', 'store');
            Route::get('/user/lib-pdd/{pddType}/{itemId}', 'show');
            Route::put('/user/lib-pdd/{pddType}/{itemId}', 'update');
            Route::delete('/user/lib-pdd/{pddType}/{itemId}', 'destroy');
        });
    });
});
