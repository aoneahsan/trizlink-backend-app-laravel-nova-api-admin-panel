<?php

use App\Http\Controllers\Zaions\Auth\AuthController;
use App\Http\Controllers\Zaions\Common\FileUploadController;
use App\Http\Controllers\Zaions\Notification\NotificationController;
use App\Http\Controllers\Zaions\Notification\USNotificationSettingController;
use App\Http\Controllers\Zaions\Notification\WSNotificationSettingController;
use App\Http\Controllers\Zaions\StaticPageController;
use App\Http\Controllers\Zaions\Testing\TestController;
use App\Http\Controllers\Zaions\User\SWSUserSettingController;
use App\Http\Controllers\Zaions\User\UserController;
use App\Http\Controllers\Zaions\User\UserEmailController;
use App\Http\Controllers\Zaions\User\UserSettingController;
use App\Http\Controllers\Zaions\WorkSpace\SharedWSController;
use App\Http\Controllers\Zaions\WorkSpace\WorkSpaceController;
use App\Http\Controllers\Zaions\WorkSpace\WorkspaceModalConnectionsController;
use App\Http\Controllers\Zaions\Workspace\WorkspaceTeamController;
use App\Http\Controllers\Zaions\Workspace\MemberController;
use App\Http\Controllers\Zaions\Workspace\SWSMemberController;
use App\Http\Controllers\Zaions\WorkSpace\WSMemberController;
use App\Http\Controllers\Zaions\ZLink\Analytics\PixelController;
use App\Http\Controllers\Zaions\ZLink\Analytics\SWSPixelController;
use App\Http\Controllers\Zaions\ZLink\Analytics\SWSUtmTagController;
use App\Http\Controllers\Zaions\ZLink\Analytics\UtmTagController;
use App\Http\Controllers\Zaions\ZLink\Common\ApiKeyController;
use App\Http\Controllers\Zaions\ZLink\Common\FolderController;
use App\Http\Controllers\Zaions\ZLink\Common\SWSFolderController;
use App\Http\Controllers\Zaions\ZLink\Label\LabelController;
use App\Http\Controllers\Zaions\ZLink\Label\SWSLabelController;
use App\Http\Controllers\Zaions\ZLink\LinkInBios\LibBlockController;
use App\Http\Controllers\Zaions\ZLink\LinkInBios\LibPredefinedDataController;
use App\Http\Controllers\Zaions\ZLink\LinkInBios\LinkInBioController;
use App\Http\Controllers\Zaions\ZLink\Plans\PlanController;
use App\Http\Controllers\Zaions\ZLink\Plans\UserSubscriptionController;
use App\Http\Controllers\Zaions\Zlink\Plans\WSSubscriptionController;
use App\Http\Controllers\Zaions\ZLink\ShortLinks\ShortLinkController;
use App\Http\Controllers\Zaions\ZLink\ShortLinks\CustomDomainController;
use App\Http\Controllers\Zaions\ZLink\ShortLinks\EmbededWidgetController;
use App\Http\Controllers\Zaions\Zlink\ShortLinks\SLAnalyticsController;
use App\Http\Controllers\Zaions\ZLink\ShortLinks\SWSShortLinkController;
use App\Http\Controllers\Zaions\ZLink\TimeSlot\SWSTimeSlotController;
use App\Http\Controllers\Zaions\ZLink\TimeSlot\TimeSlotController;
use App\Zaions\Enums\PlansEnum;
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
        Route::post('/data-list-test', 'testingPaginationInPhp'); // pagination, sorting, ordering, filters, searching
        Route::post('/getPageMetaData', 'getPageMetaData');
    });

    // Guest Routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/social-login', 'socialLogin');
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

        // Subscription 
        Route::controller(UserSubscriptionController::class)->group(function () {
            Route::post('/user/subscribe/{planType}', 'assignPlan');

            Route::get('/user-subscription', 'userSubscription');

            Route::put('/user/upgrade/subscribe', 'upgradeUserSubscription');
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
            Route::put('/user/update-account-info', 'updateAccountInfo');
            Route::put('/user/password-resend-otp', 'resendPassword');
            Route::put('/user/update-user-status', 'updateUserStatus');
            Route::put('/user/update-password', 'updatePassword');
            Route::put('/user/validate-password', 'validateCurrentPassword');
            Route::put('/user/validate-password-otp', 'confirmValidateCurrentPasswordOtp');
            Route::post('/user/username/update', 'updateUsername');
            // Route::get('/user/{token}', '')->name('password.reset');
            Route::post('/user/delete', 'destroy');
            Route::get('/user/ws-roles', 'getWSPermissions');
            Route::get('/user/limits', 'limits');
        });

        // user emails routes
        Route::controller(UserEmailController::class)->group(function () {
            Route::get('/user/list-emails', 'index');
            Route::post('/user/add-email', 'addEmail');
            Route::put('/user/confirm-email-otp/{itemId}', 'confirmOtp');
            Route::put('/user/resend-email-otp/{itemId}', 'resendOtp');
            Route::put('/user/make-email-primary/{itemId}', 'makeEmailPrimary');
            Route::delete('/user/delete-email/{itemId}', 'deleteEmail');
        });

        // user
        Route::controller(UserSettingController::class)->group(function () {
            // Route::get('/user/workspace/{workspaceId}/modal-settings', 'index');
            Route::get('/user/{type}/{uniqueId}/modal-settings', 'index');
            // Route::post('/user/workspace/{workspaceId}/modal-settings', 'store');
            Route::post('/user/{type}/{uniqueId}/modal-settings', 'store');
            // Route::get('/user/workspace/{workspaceId}/modal-settings/{type}', 'show');
            Route::get('/user/{type}/{uniqueId}/modal-settings/{swType}', 'show');
            // Route::put('/user/workspace/{workspaceId}/modal-settings/{type}', 'update');
            Route::put('/user/{type}/{uniqueId}/modal-settings/{swType}', 'update');
            // Route::delete('/user/workspace/{workspaceId}/modal-settings/{type}', 'destroy');
            Route::delete('/user/{type}/{uniqueId}/modal-settings/{swType}', 'destroy');
        });

        Route::controller(SWSUserSettingController::class)->group(function () {
            Route::get('/user/sws/member/{memberId}/modal-settings', 'index');
            Route::post('/user/sws/member/{memberId}/modal-settings', 'store');
            Route::get('/user/sws/member/{memberId}/modal-settings/{type}', 'show');
            Route::put('/user/sws/member/{memberId}/modal-settings/{type}', 'update');
            Route::delete('/user/sws/member/{memberId}/modal-settings/{type}', 'destroy');
        });

        // User notification settings
        Route::controller(USNotificationSettingController::class)->group(function () {
            // Route::get('/user/us-notification-setting', 'index');
            Route::post('/user/us-notification-setting', 'store');
            Route::get('/user/us-notification-setting', 'show');
            Route::put('/user/us-notification-setting/{itemId}', 'update');
        });
        // Workspace notification notification settings
        Route::controller(WSNotificationSettingController::class)->group(function () {
            Route::post('/user/ws-notification-setting/{workspaceId}', 'store');
            Route::get('/user/ws-notification-setting/{workspaceId}/{type}', 'show');
            Route::put('/user/ws-notification-setting/{workspaceId}/wsn/{itemId}', 'update');
        });

        // notification
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/user/notification/type/{type}', 'allNotification');
            Route::put('/user/notification/markAsRead/{id}', 'markAsRead');
            Route::put('/user/notification/markAllAsRead', 'markAllAsRead');
            // Route::post('/user/settings', 'store');
            // Route::get('/user/settings/{type}/{workspaceId}', 'show');
            // Route::put('/user/settings/{itemId}', 'update');
            // Route::delete('/user/settings/{itemId}', 'destroy');
        });

        // Workspace
        Route::controller(WorkSpaceController::class)->group(function () {
            Route::get('/user/workspaces', 'index');
            Route::get('/user/workspaces/page-number/{pageNumber}/limit/{paginationLimit}', 'indexWithPagination');
            Route::post('/user/workspaces', 'store');
            Route::get('/user/workspaces/{itemId}', 'show');
            Route::put('/user/workspaces/{itemId}', 'update');
            Route::delete('/user/workspaces/{itemId}', 'destroy');
            Route::put('/user/workspaces/update-is-favorite/{itemId}', 'updateIsFavorite');
            Route::get('/user/{type}/{itemId}/limits', 'limits');
        });

        Route::controller(WSSubscriptionController::class)->group(function () {
            Route::post('/user/workspace/{wsUniqueId}/subscribe', 'assignPlan'); 
            Route::get('/user/{type}/{wsUniqueId}/ws-subscription', 'workspaceSubscription'); 
            Route::put('/user/workspace/{wsUniqueId}/update/subscription', 'upgradeUserSubscription'); 
         });

        // Workspace Team
        Route::controller(WorkspaceTeamController::class)->group(function () {
            Route::get('/user/workspace/{workspaceId}/teams', 'index');
            Route::post('/user/workspace/{workspaceId}/teams', 'store');
            Route::get('/user/workspace/{workspaceId}/team/{itemId}', 'show');
            Route::put('/user/workspace/{workspaceId}/team/{itemId}', 'update');
            Route::delete('/user/workspace/{workspaceId}/team/{itemId}', 'destroy');
        });

        // Workspace Team member
        Route::controller(WSMemberController::class)->group(function () {
            Route::get('/user/{type}/{uniqueId}/member', 'getAllInvitationData');
            Route::post('/user/{type}/{uniqueId}/member/send-invitation', 'sendInvitation');
            Route::put('/user/{type}/{uniqueId}/member/resend-invitation/{itemId}', 'resendInvitation');
            Route::get('/user/{type}/{uniqueId}/member/{itemId}', 'getInvitationData');
            // Route::put('/user/validate-and-update-invitation', 'validateAndUpdateInvitation');
            Route::put('/user/{type}/{uniqueId}/update-invitation/{itemId}', 'updateInvitationStatus');
            Route::put('/user/{type}/{uniqueId}/update-role/{itemId}', 'updateRole');
            Route::put('/user/{type}/{uniqueId}/create-short-url/{itemId}', 'createShortLinkId');

            Route::delete('/user/{type}/{uniqueId}/member/{itemId}', 'destroy');
        });

        Route::controller(SWSMemberController::class)->group(function () {
            Route::get('/user/sws/member/{memberId}/ws/member', 'getAllInvitationData');
            Route::post('/user/sws/member/{memberId}/ws/member/send-invitation', 'sendInvitation');
            Route::put('/user/sws/member/{memberId}/ws/member/resend-invitation/{itemId}', 'resendInvitation');
            Route::get('/user/sws/member/{memberId}/ws/member/{itemId}', 'getInvitationData');
            // Route::put('/user/validate-and-update-invitation', 'validateAndUpdateInvitation');
            Route::put('/user/sws/member/{memberId}/ws/update-invitation/{itemId}', 'updateInvitationStatus');
            Route::put('/user/sws/member/{memberId}/ws/update-role/{itemId}', 'updateRole');
            Route::put('/user/sws/member/{memberId}/ws/create-short-url/{itemId}', 'createShortLinkId');

            Route::delete('/user/sws/member/{memberId}/ws/member/{itemId}', 'destroy');
        });

        // Attach modal (pixel, UTM tag etc.) to workspace.
        Route::controller(WorkspaceModalConnectionsController::class)->group(function () {
            Route::get('/user/wmc/{workspaceId}/modal/{modalType}', 'viewAll');
            Route::post('/user/wmc/{workspaceId}/modal/{modalType}/modalId/{modalId}', 'attach');
            Route::delete('/user/wmc/{workspaceId}/modal/{modalType}/modalId/{modalId}', 'detach');
        });

        // Workspace member
        // Route::controller(WorkspaceMemberController::class)->group(function () {
        //     Route::post('/user/workspace/{workspaceId}/add-member', 'attachMember');
        //     Route::delete('/user/workspace/{workspaceId}/remove-member/{memberId}', 'detachMember');
        //     Route::get('/user/workspace/{workspaceId}/members', 'viewWorkspaceMembers');
        //     Route::get(
        //         '/user/workspace/user-collaborated',
        //         'collaboratedWorkspaces'
        //     );
        //     Route::get('/user/workspace/{workspaceId}/user-collaborated-role', 'collaboratedWorkspaceRole');
        // });

        // ShortLink
        Route::controller(ShortLinkController::class)->group(function () {
            // Route::get('/user/{type}/{uniqueId}/link-in-bio', 'index');
            Route::get('/user/{type}/{uniqueId}/short-links', 'index');
            Route::get('/user/{type}/{uniqueId}/short-links/page-number/{pageNumber}/limit/{paginationLimit}', 'indexWithPagination');
            Route::post('/user/{type}/{uniqueId}/short-links', 'store');
            Route::get('/user/{type}/{uniqueId}/short-links/{itemId}', 'show');
            Route::put('/user/{type}/{uniqueId}/short-links/{itemId}', 'update');
            Route::delete('/user/{type}/{uniqueId}/short-links/{itemId}', 'destroy');

            Route::get('/user/{type}/{uniqueId}/sl/is-path-available/{value}', 'checkShortUrlPathAvailable');
            Route::get('/user/{type}/{uniqueId}/short-links/preview/{privateUrlPath}', 'getTargetInfoByPrivateUrl');
        });

        // ShortLink Analytics
        Route::controller(SLAnalyticsController::class)->group(function () {
            Route::get('/user/{type}/{wsUniqueId}/sl/{slUniqueId}/analytics', 'index');
        });


        // Share workspace short links
        Route::controller(SWSShortLinkController::class)->group(function () {
            Route::get('/user/sws/member/{memberId}/short-links', 'index');
            Route::post('/user/sws/member/{memberId}/short-links', 'store');
            Route::get('/user/sws/member/{memberId}/short-link/{itemId}', 'show');
            Route::put('/user/sws/member/{memberId}/short-link/{itemId}', 'update');
            Route::delete('/user/sws/member/{memberId}/short-link/{itemId}', 'destroy');

            Route::get('/user/sws/member/{memberId}/sl/is-path-available/{value}', 'checkShortUrlPathAvailable');
        });

        // Pixel
        Route::controller(PixelController::class)->group(function () {
            Route::get('/user/workspace/{workspaceId}/pixel', 'index');
            Route::post('/user/workspace/{workspaceId}/pixel', 'store');
            Route::get('/user/workspace/{workspaceId}/pixel/{itemId}', 'show');
            Route::put('/user/workspace/{workspaceId}/pixel/{itemId}', 'update');
            Route::delete('/user/workspace/{workspaceId}/pixel/{itemId}', 'destroy');
        });

        // Share workspace pixel 
        Route::controller(SWSPixelController::class)->group(function () {
            Route::get('/user/sws/member/{memberId}/pixel', 'index');
            Route::post('/user/sws/member/{memberId}/pixel', 'store');
            Route::get('/user/sws/member/{memberId}/pixel/{itemId}', 'show');
            Route::put('/user/sws/member/{memberId}/pixel/{itemId}', 'update');
            Route::delete('/user/sws/member/{memberId}/pixel/{itemId}', 'destroy');
        });

        // UTM Tags
        Route::controller(UtmTagController::class)->group(function () {
            Route::get('/user/workspace/{workspaceId}/utm-tag', 'index');
            Route::post('/user/workspace/{workspaceId}/utm-tag', 'store');
            Route::get('/user/workspace/{workspaceId}/utm-tag/{itemId}', 'show');
            Route::put('/user/workspace/{workspaceId}/utm-tag/{itemId}', 'update');
            Route::delete('/user/workspace/{workspaceId}/utm-tag/{itemId}', 'destroy');
        });

        // Share UTM Tags
        Route::controller(SWSUtmTagController::class)->group(function () {
            Route::get('/user/sws/member/{memberId}/utm-tag', 'index');
            Route::post('/user/sws/member/{memberId}/utm-tag', 'store');
            Route::get('/user/sws/member/{memberId}/utm-tag/{itemId}', 'show');
            Route::put('/user/sws/member/{memberId}/utm-tag/{itemId}', 'update');
            Route::delete('/user/sws/member/{memberId}/utm-tag/{itemId}', 'destroy');
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
            // Route::get('/user/workspaces/{workspaceId}/folder', 'index');
            Route::get('/user/{type}/{uniqueId}/folder/{modal}', 'index');

            // 
            Route::post('/user/{type}/{uniqueId}/folder', 'store');
            // Route::put('/user/workspaces/{workspaceId}/folders/reorder', 'updateSortOrderNo');
            Route::get('/user/{type}/{uniqueId}/folder/{itemId}', 'show');
            // Route::get('/user/workspaces/{workspaceId}/folder/{itemId}', 'show');
            Route::put('/user/{type}/{uniqueId}/folder/{itemId}', 'update');
            // Route::put('/user/workspaces/{workspaceId}/folder/{itemId}', 'update');
            Route::delete('/user/{type}/{uniqueId}/folder/{itemId}', 'destroy');
            // Route::delete('/user/workspaces/{workspaceId}/folder/{itemId}', 'destroy');
            Route::get('/user/{type}/{uniqueId}/get/shortLink/folders', 'getShortLinksFolders');
            Route::get('/user/{type}/{uniqueId}/get/linkInBio/folders', 'getLinkInBioFolders');
        });

        // Share workspaces folder
        Route::controller(SWSFolderController::class)->group(function () {
            Route::get('/user/sws/member/{memberId}/folder', 'index');
            Route::post('/user/sws/member/{memberId}/folder', 'store');
            // Route::put('/user/sws/member/{memberId}/folders/reorder', 'updateSortOrderNo');
            Route::get('/user/sws/member/{memberId}/folder/{itemId}', 'show');
            Route::put('/user/sws/member/{memberId}/folder/{itemId}', 'update');
            Route::delete('/user/sws/member/{memberId}/folder/{itemId}', 'destroy');
            Route::get('/user/sws/member/{memberId}/get/shortLink/folders', 'getShortLinksFolders');
            Route::get('/user/sws/member/{memberId}/get/linkInBio/folders', 'getLinkInBioFolders');
        });

        // Time slot
        Route::controller(TimeSlotController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/time-slot', 'index');
            Route::post('/user/workspaces/{workspaceId}/time-slot', 'store');
            Route::get('/user/workspaces/{workspaceId}/time-slot/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/time-slot/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/time-slot/{itemId}', 'destroy');
        });

        // Share workspace time slot
        Route::controller(SWSTimeSlotController::class)->group(function () {
            // sws => share-workspace
            Route::get('/user/sws/member/{itemId}/time-slot', 'index');
            Route::post('/user/sws/member/{itemId}/time-slot', 'store');
            Route::get('/user/sws/{memberId}/time-slot/{itemId}', 'show');
            Route::put('/user/sws/{memberId}/time-slot/{itemId}', 'update');
            Route::delete('/user/sws/{memberId}/time-slot/{itemId}', 'destroy');
        });

        // Label
        Route::controller(LabelController::class)->group(function () {
            Route::get('/user/workspaces/{workspaceId}/label', 'index');
            Route::post('/user/workspaces/{workspaceId}/label', 'store');
            Route::get('/user/workspaces/{workspaceId}/label/{itemId}', 'show');
            Route::put('/user/workspaces/{workspaceId}/label/{itemId}', 'update');
            Route::delete('/user/workspaces/{workspaceId}/label/{itemId}', 'destroy');
        });

        // Share ws Label
        Route::controller(SWSLabelController::class)->group(function () {
            Route::get('/user/sws/member/{itemId}/label', 'index');
            Route::post('/user/sws/member/{itemId}/label', 'store');
            Route::get('/user/sws/{memberId}/label/{itemId}', 'show');
            Route::put('/user/sws/{memberId}/label/{itemId}', 'update');
            Route::delete('/user/sws/{memberId}/label/{itemId}', 'destroy');
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
            // Route::get('/user/workspaces/{uniqueId}/link-in-bio/{type}', 'index');
            Route::get('/user/{type}/{uniqueId}/link-in-bio', 'index');
            // Route::post('/user/workspaces/{workspaceId}/link-in-bio', 'store');
            Route::post('/user/{type}/{uniqueId}/link-in-bio', 'store');
            // Route::get('/user/workspaces/{workspaceId}/link-in-bio/{itemId}', 'show');
            Route::get('/user/{type}/{uniqueId}/link-in-bio/{itemId}', 'show');
            // Route::put('/user/workspaces/{workspaceId}/link-in-bio/{itemId}', 'update');
            Route::put('/user/{type}/{uniqueId}/link-in-bio/{itemId}', 'update');
            // Route::delete('/user/workspaces/{workspaceId}/link-in-bio/{itemId}', 'destroy');
            Route::delete('/user/{type}/{uniqueId}/link-in-bio/{itemId}', 'destroy');
        });

        // LinkInBio block
        Route::controller(LibBlockController::class)->group(function () {
            // Route::get('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block', 'index');
            Route::get('/user/{type}/{uniqueId}/lib/{linkInBioId}/lib-block', 'index');
            // Route::post('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block', 'store');
            Route::post('/user/{type}/{uniqueId}/lib/{linkInBioId}/lib-block', 'store');
            // Route::get('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block/{itemId}', 'show');
            Route::get('/user/{type}/{uniqueId}/lib/{linkInBioId}/lib-block/{itemId}', 'show');
            // Route::put('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block/{itemId}', 'update');
            Route::put('/user/{type}/{uniqueId}/lib/{linkInBioId}/lib-block/{itemId}', 'update');
            // Route::delete('/user/ws/{workspaceId}/lib/{linkInBioId}/lib-block/{itemId}', 'destroy');
            Route::delete('/user/{type}/{uniqueId}/lib/{linkInBioId}/lib-block/{itemId}', 'destroy');
        });

        // LinkInBio pre defined data
        Route::controller(LibPredefinedDataController::class)->group(function () {
            // Route::get('/user/lib-pre-dd/{pddType}', 'index');
            Route::get('/user/{type}/{uniqueId}/lib-pre-dd/{pddType}', 'index');
            Route::post('/user/{type}/{uniqueId}/lib-pdd/{pddType}', 'store');
            Route::get('/user/{type}/{uniqueId}/lib-pdd/{pddType}/{itemId}', 'show');
            Route::put('/user/{type}/{uniqueId}/lib-pdd/{pddType}/{itemId}', 'update');
            Route::delete('/user/{type}/{uniqueId}/lib-pdd/{pddType}/{itemId}', 'destroy');
        });

        // Get Shared Workspaces
        Route::controller(SharedWSController::class)->group(function () {
            Route::get('/user/shared-ws', 'index');
            Route::post('/user/shared-ws/list', 'index');
            Route::get('/user/shared-ws/get-member-role-permissions/{itemId}', 'getUserRoleAndPermissions');
            Route::get('/user/shared-ws/get-share-ws-info-data/{itemId}', 'getShareWSInfoData');
            Route::put('/user/shared-ws/{itemId}/member-id/{memberId}', 'updateShareWSInfoData');
            Route::put('/user/shared-ws/{itemId}/leave-ws/member-id/{memberId}', 'leaveShareWS');
            Route::put('/user/shared-ws/update-is-favorite/{itemId}', 'updateIsFavorite');
        });
    });

    // API - UnAuthenticated Routes
    Route::controller(ShortLinkController::class)->group(function () {
        Route::post('/public/s/{urlPath}', 'getTargetUrlInfo');
        Route::post('/public/s/{urlPath}/check-password', 'checkShortLinkPassword');
    });

    Route::controller(UserController::class)->group(function () {
        Route::put('/user/send-otp', 'generateOtp');
        // Route::put('/user/confirm-otp', 'confirmOtp');
        Route::put('/user/set-password', 'setPassword');
        Route::post('/user/username/check', 'checkIfUsernameIsAvailable');
        Route::post('/user/send-signup-otp', 'sendSignUpOTP');
        Route::put('/user/resend-user-otp', 'resendOTP');
        Route::put('/user/send-forget-password-otp', 'sendForgetPasswordOTP');
        Route::put('/user/set-username-password', 'setUsernamePassword');
        Route::put('/user/confirm-otp', 'confirmSignUpOtp');
    });

    // Workspace Team member
    Route::controller(WSMemberController::class)->group(function () {
        Route::put('/user/validate-and-update-invitation', 'validateAndUpdateInvitation');
        Route::get('/user/ws-member/short-url/check/{shortUrlId}', 'shortUrlCheck');
    });

    // Plans
    Route::controller(PlanController::class)->group(function () {
        Route::get('/plans', 'index');
    });
});
