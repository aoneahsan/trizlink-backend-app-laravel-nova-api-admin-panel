<?php

namespace Database\Seeders\Default;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Enums\RoleTypesEnum;
use App\Zaions\Enums\WSPermissionsEnum;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Roles
        $superAdminRole = Role::create(['name' => RolesEnum::superAdmin->name, 'roleType' => RoleTypesEnum::mainAppRole->name]);
        $adminRole = Role::create(['name' => RolesEnum::admin->name, 'roleType' => RoleTypesEnum::mainAppRole->name]);
        $userRole = Role::create(['name' => RolesEnum::user->name, 'roleType' => RoleTypesEnum::mainAppRole->name]);

        // All App Permissions
        // Dashboard Permissions
        $viewDashboardPermission = Permission::create(['name' => PermissionsEnum::view_dashboard->name]);
        // Users Model Permissions
        $viewAnyUserPermission = Permission::create(['name' => PermissionsEnum::viewAny_user->name]);
        $viewUserPermission = Permission::create(['name' => PermissionsEnum::view_user->name]);
        $addUserPermission = Permission::create(['name' => PermissionsEnum::create_user->name]);
        $updateUserPermission = Permission::create(['name' => PermissionsEnum::update_user->name]);
        $deleteUserPermission = Permission::create(['name' => PermissionsEnum::delete_user->name]);
        $replicateUserPermission = Permission::create(['name' => PermissionsEnum::replicate_user->name]);
        $restoreUserPermission = Permission::create(['name' => PermissionsEnum::restore_user->name]);
        $forceDeleteUserPermission = Permission::create(['name' => PermissionsEnum::forceDelete_user->name]);
        // Emails Model Permissions
        $viewAnyEmailsPermission = Permission::create(['name' => PermissionsEnum::viewAny_emails->name]);
        $viewEmailsPermission = Permission::create(['name' => PermissionsEnum::view_email->name]);
        $addEmailsPermission = Permission::create(['name' => PermissionsEnum::add_email->name]);
        $emailOptCheckPermission = Permission::create(['name' => PermissionsEnum::email_opt_check->name]);
        $updateEmailsPermission = Permission::create(['name' => PermissionsEnum::update_email->name]);
        $deleteEmailsPermission = Permission::create(['name' => PermissionsEnum::delete_email->name]);
        $restoreEmailsPermission = Permission::create(['name' => PermissionsEnum::restore_email->name]);
        $forceDeleteEmailsPermission = Permission::create(['name' => PermissionsEnum::forceDelete_email->name]);
        // Notification Model Permissions
        $viewAnyNotificationPermission = Permission::create(['name' => PermissionsEnum::viewAny_notification->name]);
        $viewNotificationPermission = Permission::create(['name' => PermissionsEnum::view_notification->name]);
        $addNotificationPermission = Permission::create(['name' => PermissionsEnum::create_notification->name]);
        $updateNotificationPermission = Permission::create(['name' => PermissionsEnum::update_notification->name]);
        $deleteNotificationPermission = Permission::create(['name' => PermissionsEnum::delete_notification->name]);
        $replicateNotificationPermission = Permission::create(['name' => PermissionsEnum::replicate_notification->name]);
        $restoreNotificationPermission = Permission::create(['name' => PermissionsEnum::restore_notification->name]);
        $forceDeleteNotificationPermission = Permission::create(['name' => PermissionsEnum::forceDelete_notification->name]);
        // Roles Model Permissions
        $viewAnyRolePermission = Permission::create(['name' => PermissionsEnum::viewAny_role->name]);
        $viewRolePermission = Permission::create(['name' => PermissionsEnum::view_role->name]);
        $addRolePermission = Permission::create(['name' => PermissionsEnum::create_role->name]);
        $updateRolePermission = Permission::create(['name' => PermissionsEnum::update_role->name]);
        $deleteRolePermission = Permission::create(['name' => PermissionsEnum::delete_role->name]);
        $replicateRolePermission = Permission::create(['name' => PermissionsEnum::replicate_role->name]);
        $restoreRolePermission = Permission::create(['name' => PermissionsEnum::restore_role->name]);
        $forceDeleteRolePermission = Permission::create(['name' => PermissionsEnum::forceDelete_role->name]);
        // Permissions Model Permissions
        $viewAnyPermissionPermission = Permission::create(['name' => PermissionsEnum::viewAny_permission->name]);
        $viewPermissionPermission = Permission::create(['name' => PermissionsEnum::view_permission->name]);
        $addPermissionPermission = Permission::create(['name' => PermissionsEnum::create_permission->name]);
        $updatePermissionPermission = Permission::create(['name' => PermissionsEnum::update_permission->name]);
        $deletePermissionPermission = Permission::create(['name' => PermissionsEnum::delete_permission->name]);
        $replicatePermissionPermission = Permission::create(['name' => PermissionsEnum::replicate_permission->name]);
        $restorePermissionPermission = Permission::create(['name' => PermissionsEnum::restore_permission->name]);
        $forceDeletePermissionPermission = Permission::create(['name' => PermissionsEnum::forceDelete_permission->name]);
        // Tasks Model Permissions
        $viewAnyTaskPermission = Permission::create(['name' => PermissionsEnum::viewAny_task->name]);
        $viewTaskPermission = Permission::create(['name' => PermissionsEnum::view_task->name]);
        $addTaskPermission = Permission::create(['name' => PermissionsEnum::create_task->name]);
        $updateTaskPermission = Permission::create(['name' => PermissionsEnum::update_task->name]);
        $deleteTaskPermission = Permission::create(['name' => PermissionsEnum::delete_task->name]);
        $replicateTaskPermission = Permission::create(['name' => PermissionsEnum::replicate_task->name]);
        $restoreTaskPermission = Permission::create(['name' => PermissionsEnum::restore_task->name]);
        $forceDeleteTaskPermission = Permission::create(['name' => PermissionsEnum::forceDelete_task->name]);
        // History Model Permissions
        $viewAnyHistoryPermission = Permission::create(['name' => PermissionsEnum::viewAny_history->name]);
        $viewHistoryPermission = Permission::create(['name' => PermissionsEnum::view_history->name]);
        $addHistoryPermission = Permission::create(['name' => PermissionsEnum::create_history->name]);
        $updateHistoryPermission = Permission::create(['name' => PermissionsEnum::update_history->name]);
        $deleteHistoryPermission = Permission::create(['name' => PermissionsEnum::delete_history->name]);
        $replicateHistoryPermission = Permission::create(['name' => PermissionsEnum::replicate_history->name]);
        $restoreHistoryPermission = Permission::create(['name' => PermissionsEnum::restore_history->name]);
        $forceDeleteHistoryPermission = Permission::create(['name' => PermissionsEnum::forceDelete_history->name]);
        // Attachments Model Permissions
        $viewAnyAttachmentPermission = Permission::create(['name' => PermissionsEnum::viewAny_attachment->name]);
        $viewAttachmentPermission = Permission::create(['name' => PermissionsEnum::view_attachment->name]);
        $addAttachmentPermission = Permission::create(['name' => PermissionsEnum::create_attachment->name]);
        $updateAttachmentPermission = Permission::create(['name' => PermissionsEnum::update_attachment->name]);
        $deleteAttachmentPermission = Permission::create(['name' => PermissionsEnum::delete_attachment->name]);
        $replicateAttachmentPermission = Permission::create(['name' => PermissionsEnum::replicate_attachment->name]);
        $restoreAttachmentPermission = Permission::create(['name' => PermissionsEnum::restore_attachment->name]);
        $forceDeleteAttachmentPermission = Permission::create(['name' => PermissionsEnum::forceDelete_attachment->name]);
        // Comments Model Permissions
        $viewAnyCommentPermission = Permission::create(['name' => PermissionsEnum::viewAny_comment->name]);
        $viewCommentPermission = Permission::create(['name' => PermissionsEnum::view_comment->name]);
        $addCommentPermission = Permission::create(['name' => PermissionsEnum::create_comment->name]);
        $updateCommentPermission = Permission::create(['name' => PermissionsEnum::update_comment->name]);
        $deleteCommentPermission = Permission::create(['name' => PermissionsEnum::delete_comment->name]);
        $replicateCommentPermission = Permission::create(['name' => PermissionsEnum::replicate_comment->name]);
        $restoreCommentPermission = Permission::create(['name' => PermissionsEnum::restore_comment->name]);
        $forceDeleteCommentPermission = Permission::create(['name' => PermissionsEnum::forceDelete_comment->name]);
        // Replies Model Permissions
        $viewAnyReplyPermission = Permission::create(['name' => PermissionsEnum::viewAny_reply->name]);
        $viewReplyPermission = Permission::create(['name' => PermissionsEnum::view_reply->name]);
        $addReplyPermission = Permission::create(['name' => PermissionsEnum::create_reply->name]);
        $updateReplyPermission = Permission::create(['name' => PermissionsEnum::update_reply->name]);
        $deleteReplyPermission = Permission::create(['name' => PermissionsEnum::delete_reply->name]);
        $replicateReplyPermission = Permission::create(['name' => PermissionsEnum::replicate_reply->name]);
        $restoreReplyPermission = Permission::create(['name' => PermissionsEnum::restore_reply->name]);
        $forceDeleteReplyPermission = Permission::create(['name' => PermissionsEnum::forceDelete_reply->name]);
        // Workspace Model Permissions
        $viewAnyWorkspacePermission = Permission::create(['name' => PermissionsEnum::viewAny_workspace->name]);
        $viewWorkspacePermission = Permission::create(['name' => PermissionsEnum::view_workspace->name]);
        $addWorkspacePermission = Permission::create(['name' => PermissionsEnum::create_workspace->name]);
        $updateWorkspacePermission = Permission::create(['name' => PermissionsEnum::update_workspace->name]);
        $deleteWorkspacePermission = Permission::create(['name' => PermissionsEnum::delete_workspace->name]);
        $replicateWorkspacePermission = Permission::create(['name' => PermissionsEnum::replicate_workspace->name]);
        $restoreWorkspacePermission = Permission::create(['name' => PermissionsEnum::restore_workspace->name]);
        $forceDeleteWorkspacePermission = Permission::create(['name' => PermissionsEnum::forceDelete_workspace->name]);
        // Share Workspace Model Permissions
        $viewAnyShareWSPermission = Permission::create(['name' => PermissionsEnum::viewAny_shareWS->name]);
        $viewShareWSPermission = Permission::create(['name' => PermissionsEnum::view_shareWS->name]);
        $addShareWSPermission = Permission::create(['name' => PermissionsEnum::create_shareWS->name]);
        $updateShareWSPermission = Permission::create(['name' => PermissionsEnum::update_shareWS->name]);
        $deleteShareWSPermission = Permission::create(['name' => PermissionsEnum::delete_shareWS->name]);
        $replicateShareWSPermission = Permission::create(['name' => PermissionsEnum::replicate_shareWS->name]);
        $restoreShareWSPermission = Permission::create(['name' => PermissionsEnum::restore_shareWS->name]);
        $forceDeleteShareWSPermission = Permission::create(['name' => PermissionsEnum::forceDelete_shareWS->name]);
        // Workspace Team Model Permissions
        $viewAnyWorkspaceTeamPermission = Permission::create(['name' => PermissionsEnum::viewAny_workspaceTeam->name]);
        $viewWorkspaceTeamPermission = Permission::create(['name' => PermissionsEnum::view_workspaceTeam->name]);
        $addWorkspaceTeamPermission = Permission::create(['name' => PermissionsEnum::create_workspaceTeam->name]);
        $updateWorkspaceTeamPermission = Permission::create(['name' => PermissionsEnum::update_workspaceTeam->name]);
        $deleteWorkspaceTeamPermission = Permission::create(['name' => PermissionsEnum::delete_workspaceTeam->name]);
        $replicateWorkspaceTeamPermission = Permission::create(['name' => PermissionsEnum::replicate_workspaceTeam->name]);
        $restoreWorkspaceTeamPermission = Permission::create(['name' => PermissionsEnum::restore_workspaceTeam->name]);
        $forceDeleteWorkspaceTeamPermission = Permission::create(['name' => PermissionsEnum::forceDelete_workspaceTeam->name]);

        // Team members
        $viewAnyWSTeamMemberPermission = Permission::create(['name' => PermissionsEnum::viewAny_WSTeamMember->name]);
        $viewWSTeamMemberPermission = Permission::create(['name' => PermissionsEnum::view_WSTeamMember->name]);
        $addWSTeamMemberPermission = Permission::create(['name' => PermissionsEnum::create_WSTeamMember->name]);
        $updateWSTeamMemberPermission = Permission::create(['name' => PermissionsEnum::update_WSTeamMember->name]);
        $deleteWSTeamMemberPermission = Permission::create(['name' => PermissionsEnum::delete_WSTeamMember->name]);
        $replicateWSTeamMemberPermission = Permission::create(['name' => PermissionsEnum::replicate_WSTeamMember->name]);
        $restoreWSTeamMemberPermission = Permission::create(['name' => PermissionsEnum::restore_WSTeamMember->name]);
        $forceDeleteWSTeamMemberPermission = Permission::create(['name' => PermissionsEnum::forceDelete_WSTeamMember->name]);
        $inviteDeleteWSTeamMemberPermission = Permission::create(['name' => PermissionsEnum::invite_WSTeamMember->name]);
        // UserSetting Model Permissions
        $viewAnyUSSettingsPermission = Permission::create(['name' => PermissionsEnum::viewAny_USSettings->name]);
        $viewUSSettingsPermission = Permission::create(['name' => PermissionsEnum::view_USSettings->name]);
        $addUSSettingsPermission = Permission::create(['name' => PermissionsEnum::create_USSettings->name]);
        $updateUSSettingsPermission = Permission::create(['name' => PermissionsEnum::update_USSettings->name]);
        $deleteUSSettingsPermission = Permission::create(['name' => PermissionsEnum::delete_USSettings->name]);
        $replicateUSSettingsPermission = Permission::create(['name' => PermissionsEnum::replicate_USSettings->name]);
        $restoreUSSettingsPermission = Permission::create(['name' => PermissionsEnum::restore_USSettings->name]);
        $forceDeleteUSSettingsPermission = Permission::create(['name' => PermissionsEnum::forceDelete_USSettings->name]);
        // Pixel Model Permissions
        $viewAnyPixelPermission = Permission::create(['name' => PermissionsEnum::viewAny_pixel->name]);
        $viewPixelPermission = Permission::create(['name' => PermissionsEnum::view_pixel->name]);
        $addPixelPermission = Permission::create(['name' => PermissionsEnum::create_pixel->name]);
        $updatePixelPermission = Permission::create(['name' => PermissionsEnum::update_pixel->name]);
        $deletePixelPermission = Permission::create(['name' => PermissionsEnum::delete_pixel->name]);
        $replicatePixelPermission = Permission::create(['name' => PermissionsEnum::replicate_pixel->name]);
        $restorePixelPermission = Permission::create(['name' => PermissionsEnum::restore_pixel->name]);
        $forceDeletePixelPermission = Permission::create(['name' => PermissionsEnum::forceDelete_pixel->name]);
        // Utm tag Model Permissions
        $viewAnyUtmTagPermission = Permission::create(['name' => PermissionsEnum::viewAny_utmTag->name]);
        $viewUtmTagPermission = Permission::create(['name' => PermissionsEnum::view_utmTag->name]);
        $addUtmTagPermission = Permission::create(['name' => PermissionsEnum::create_utmTag->name]);
        $updateUtmTagPermission = Permission::create(['name' => PermissionsEnum::update_utmTag->name]);
        $deleteUtmTagPermission = Permission::create(['name' => PermissionsEnum::delete_utmTag->name]);
        $replicateUtmTagPermission = Permission::create(['name' => PermissionsEnum::replicate_utmTag->name]);
        $restoreUtmTagPermission = Permission::create(['name' => PermissionsEnum::restore_utmTag->name]);
        $forceDeleteUtmTagPermission = Permission::create(['name' => PermissionsEnum::forceDelete_utmTag->name]);
        // Short link Model Permissions
        $viewAnyShortLinkPermission = Permission::create(['name' => PermissionsEnum::viewAny_shortLink->name]);
        $viewShortLinkPermission = Permission::create(['name' => PermissionsEnum::view_shortLink->name]);
        $addShortLinkPermission = Permission::create(['name' => PermissionsEnum::create_shortLink->name]);
        $updateShortLinkPermission = Permission::create(['name' => PermissionsEnum::update_shortLink->name]);
        $deleteShortLinkPermission = Permission::create(['name' => PermissionsEnum::delete_shortLink->name]);
        $replicateShortLinkPermission = Permission::create(['name' => PermissionsEnum::replicate_shortLink->name]);
        $restoreShortLinkPermission = Permission::create(['name' => PermissionsEnum::restore_shortLink->name]);
        $forceDeleteShortLinkPermission = Permission::create(['name' => PermissionsEnum::forceDelete_shortLink->name]);
        // Time slot Model Permissions
        $viewAnyTimeSlotPermission = Permission::create(['name' => PermissionsEnum::viewAny_timeSlot->name]);
        $viewTimeSlotPermission = Permission::create(['name' => PermissionsEnum::view_timeSlot->name]);
        $addTimeSlotPermission = Permission::create(['name' => PermissionsEnum::create_timeSlot->name]);
        $updateTimeSlotPermission = Permission::create(['name' => PermissionsEnum::update_timeSlot->name]);
        $deleteTimeSlotPermission = Permission::create(['name' => PermissionsEnum::delete_timeSlot->name]);
        $replicateTimeSlotPermission = Permission::create(['name' => PermissionsEnum::replicate_timeSlot->name]);
        $restoreTimeSlotPermission = Permission::create(['name' => PermissionsEnum::restore_timeSlot->name]);
        $forceDeleteTimeSlotPermission = Permission::create(['name' => PermissionsEnum::forceDelete_timeSlot->name]);
        // Label Model Permissions
        $viewAnyLabelPermission = Permission::create(['name' => PermissionsEnum::viewAny_label->name]);
        $viewLabelPermission = Permission::create(['name' => PermissionsEnum::view_label->name]);
        $addLabelPermission = Permission::create(['name' => PermissionsEnum::create_label->name]);
        $updateLabelPermission = Permission::create(['name' => PermissionsEnum::update_label->name]);
        $deleteLabelPermission = Permission::create(['name' => PermissionsEnum::delete_label->name]);
        $replicateLabelPermission = Permission::create(['name' => PermissionsEnum::replicate_label->name]);
        $restoreLabelPermission = Permission::create(['name' => PermissionsEnum::restore_label->name]);
        $forceDeleteLabelPermission = Permission::create(['name' => PermissionsEnum::forceDelete_label->name]);
        // Link-in-bio Model Permissions
        $viewAnyLinkInBioPermission = Permission::create(['name' => PermissionsEnum::viewAny_linkInBio->name]);
        $viewLinkInBioPermission = Permission::create(['name' => PermissionsEnum::view_linkInBio->name]);
        $addLinkInBioPermission = Permission::create(['name' => PermissionsEnum::create_linkInBio->name]);
        $updateLinkInBioPermission = Permission::create(['name' => PermissionsEnum::update_linkInBio->name]);
        $deleteLinkInBioPermission = Permission::create(['name' => PermissionsEnum::delete_linkInBio->name]);
        $replicateLinkInBioPermission = Permission::create(['name' => PermissionsEnum::replicate_linkInBio->name]);
        $restoreLinkInBioPermission = Permission::create(['name' => PermissionsEnum::restore_linkInBio->name]);
        $forceDeleteLinkInBioPermission = Permission::create(['name' => PermissionsEnum::forceDelete_linkInBio->name]);
        // Link-in-bio block Model Permissions
        $viewAnyLibBlockPermission = Permission::create(['name' => PermissionsEnum::viewAny_libBlock->name]);
        $viewLibBlockPermission = Permission::create(['name' => PermissionsEnum::view_libBlock->name]);
        $addLibBlockPermission = Permission::create(['name' => PermissionsEnum::create_libBlock->name]);
        $updateLibBlockPermission = Permission::create(['name' => PermissionsEnum::update_libBlock->name]);
        $deleteLibBlockPermission = Permission::create(['name' => PermissionsEnum::delete_libBlock->name]);
        $replicateLibBlockPermission = Permission::create(['name' => PermissionsEnum::replicate_libBlock->name]);
        $restoreLibBlockPermission = Permission::create(['name' => PermissionsEnum::restore_libBlock->name]);
        $forceDeleteLibBlockPermission = Permission::create(['name' => PermissionsEnum::forceDelete_libBlock->name]);
        // Link-in-bio per defined data Model Permissions
        $viewAnyLibPerDefinedDataPermission = Permission::create(['name' => PermissionsEnum::viewAny_libPerDefinedData->name]);
        $viewLibPerDefinedDataPermission = Permission::create(['name' => PermissionsEnum::view_libPerDefinedData->name]);
        $addLibPerDefinedDataPermission = Permission::create(['name' => PermissionsEnum::create_libPerDefinedData->name]);
        $updateLibPerDefinedDataPermission = Permission::create(['name' => PermissionsEnum::update_libPerDefinedData->name]);
        $deleteLibPerDefinedDataPermission = Permission::create(['name' => PermissionsEnum::delete_libPerDefinedData->name]);
        $replicateLibPerDefinedDataPermission = Permission::create(['name' => PermissionsEnum::replicate_libPerDefinedData->name]);
        $restoreLibPerDefinedDataPermission = Permission::create(['name' => PermissionsEnum::restore_libPerDefinedData->name]);
        $forceDeleteLibPerDefinedDataPermission = Permission::create(['name' => PermissionsEnum::forceDelete_libPerDefinedData->name]);
        // custom domain Model Permissions
        $viewAnyCustomDomainPermission = Permission::create(['name' => PermissionsEnum::viewAny_customDomain->name]);
        $viewCustomDomainPermission = Permission::create(['name' => PermissionsEnum::view_customDomain->name]);
        $addCustomDomainPermission = Permission::create(['name' => PermissionsEnum::create_customDomain->name]);
        $updateCustomDomainPermission = Permission::create(['name' => PermissionsEnum::update_customDomain->name]);
        $deleteCustomDomainPermission = Permission::create(['name' => PermissionsEnum::delete_customDomain->name]);
        $replicateCustomDomainPermission = Permission::create(['name' => PermissionsEnum::replicate_customDomain->name]);
        $restoreCustomDomainPermission = Permission::create(['name' => PermissionsEnum::restore_customDomain->name]);
        $forceDeleteCustomDomainPermission = Permission::create(['name' => PermissionsEnum::forceDelete_customDomain->name]);
        // Api key Model Permissions
        $viewAnyApiKeyPermission = Permission::create(['name' => PermissionsEnum::viewAny_apiKey->name]);
        $viewApiKeyPermission = Permission::create(['name' => PermissionsEnum::view_apiKey->name]);
        $addApiKeyPermission = Permission::create(['name' => PermissionsEnum::create_apiKey->name]);
        $updateApiKeyPermission = Permission::create(['name' => PermissionsEnum::update_apiKey->name]);
        $deleteApiKeyPermission = Permission::create(['name' => PermissionsEnum::delete_apiKey->name]);
        $replicateApiKeyPermission = Permission::create(['name' => PermissionsEnum::replicate_apiKey->name]);
        $restoreApiKeyPermission = Permission::create(['name' => PermissionsEnum::restore_apiKey->name]);
        $forceDeleteApiKeyPermission = Permission::create(['name' => PermissionsEnum::forceDelete_apiKey->name]);
        // Folder Model Permissions
        $viewAnyFolderPermission = Permission::create(['name' => PermissionsEnum::viewAny_folder->name]);
        $viewFolderPermission = Permission::create(['name' => PermissionsEnum::view_folder->name]);
        $addFolderPermission = Permission::create(['name' => PermissionsEnum::create_folder->name]);
        $updateFolderPermission = Permission::create(['name' => PermissionsEnum::update_folder->name]);
        $deleteFolderPermission = Permission::create(['name' => PermissionsEnum::delete_folder->name]);
        $sortFolderPermission = Permission::create(['name' => PermissionsEnum::sort_folder->name]);
        $replicateFolderPermission = Permission::create(['name' => PermissionsEnum::replicate_folder->name]);
        $restoreFolderPermission = Permission::create(['name' => PermissionsEnum::restore_folder->name]);
        $forceDeleteFolderPermission = Permission::create(['name' => PermissionsEnum::forceDelete_folder->name]);
        // Short links folder Model Permissions
        $viewAnySLFolderPermission = Permission::create(['name' => PermissionsEnum::viewAny_sl_folder->name]);
        $viewSLFolderPermission = Permission::create(['name' => PermissionsEnum::view_sl_folder->name]);
        $addSLFolderPermission = Permission::create(['name' => PermissionsEnum::create_sl_folder->name]);
        $updateSLFolderPermission = Permission::create(['name' => PermissionsEnum::update_sl_folder->name]);
        $deleteSLFolderPermission = Permission::create(['name' => PermissionsEnum::delete_sl_folder->name]);
        $sortSLFolderPermission = Permission::create(['name' => PermissionsEnum::sort_sl_folder->name]);
        $replicateSLFolderPermission = Permission::create(['name' => PermissionsEnum::replicate_sl_folder->name]);
        $restoreSLFolderPermission = Permission::create(['name' => PermissionsEnum::restore_sl_folder->name]);
        $forceDeleteSLFolderPermission = Permission::create(['name' => PermissionsEnum::forceDelete_sl_folder->name]);
        // Link in bio folder Model Permissions
        $viewAnyLIBFolderPermission = Permission::create(['name' => PermissionsEnum::viewAny_lib_folder->name]);
        $viewLIBFolderPermission = Permission::create(['name' => PermissionsEnum::view_lib_folder->name]);
        $addLIBFolderPermission = Permission::create(['name' => PermissionsEnum::create_lib_folder->name]);
        $updateLIBFolderPermission = Permission::create(['name' => PermissionsEnum::update_lib_folder->name]);
        $deleteLIBFolderPermission = Permission::create(['name' => PermissionsEnum::delete_lib_folder->name]);
        $sortLIBFolderPermission = Permission::create(['name' => PermissionsEnum::sort_lib_folder->name]);
        $replicateLIBFolderPermission = Permission::create(['name' => PermissionsEnum::replicate_lib_folder->name]);
        $restoreLIBFolderPermission = Permission::create(['name' => PermissionsEnum::restore_lib_folder->name]);
        $forceDeleteLIBFolderPermission = Permission::create(['name' => PermissionsEnum::forceDelete_lib_folder->name]);
        // Embeded widgets
        $viewAnyEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::viewAny_embededWidget->name]);
        $viewEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::view_embededWidget->name]);
        $addEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::create_embededWidget->name]);
        $updateEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::update_embededWidget->name]);
        $deleteEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::delete_embededWidget->name]);
        $replicateEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::replicate_embededWidget->name]);
        $restoreEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::restore_embededWidget->name]);
        $forceDeleteEmbededWidgetPermission = Permission::create(['name' => PermissionsEnum::forceDelete_embededWidget->name]);
        // Workspace pixel connections
        $attachPixelToWorkspacePermission = Permission::create(['name' => PermissionsEnum::attach_pixel_to_workspace->name]);
        $detachPixelFromWorkspacePermission = Permission::create(['name' => PermissionsEnum::detach_pixel_from_workspace->name]);
        $updateWorkspacePixelPermission = Permission::create(['name' => PermissionsEnum::update_workspace_pixel->name]);
        // Workspace utm tags connections
        $attachUtmTagToWorkspacePermission = Permission::create(['name' => PermissionsEnum::attach_utm_tag_to_workspace->name]);
        $detachUtmTagFromWorkspacePermission = Permission::create(['name' => PermissionsEnum::detach_utm_tag_from_workspace->name]);
        $updateWorkspaceUtmTagPermission = Permission::create(['name' => PermissionsEnum::update_workspace_utm_tag->name]);

        // Workspace Members
        $attachWorkspaceMemberPermission = Permission::create(['name' => PermissionsEnum::attach_workspace_members->name]);
        $detachWorkspaceMemberPermission = Permission::create(['name' => PermissionsEnum::detach_workspace_members->name]);
        $updateWorkspaceMemberPermission = Permission::create(['name' => PermissionsEnum::update_workspace_members->name]);

        // Impersonation Permissions
        $canImpersonatePermission = Permission::create(['name' => PermissionsEnum::can_impersonate->name]);
        $canBeImpersonatePermission = Permission::create(['name' => PermissionsEnum::canBe_impersonate->name]);

        $superAdminRolePermissions = [
            // Dashboard
            $viewDashboardPermission,
            // User
            $viewAnyUserPermission,
            $viewUserPermission,
            $addUserPermission,
            $updateUserPermission,
            $deleteUserPermission,
            $replicateUserPermission,
            $restoreUserPermission,
            $forceDeleteUserPermission,
            // Emails
            $viewAnyEmailsPermission,
            $viewEmailsPermission,
            $addEmailsPermission,
            $emailOptCheckPermission,
            $updateEmailsPermission,
            $deleteEmailsPermission,
            $restoreEmailsPermission,
            $forceDeleteEmailsPermission,
            // Notification
            $viewAnyNotificationPermission,
            $viewNotificationPermission,
            $addNotificationPermission,
            $updateNotificationPermission,
            $deleteNotificationPermission,
            $replicateNotificationPermission,
            $restoreNotificationPermission,
            $forceDeleteNotificationPermission,
            // Role
            $viewAnyRolePermission,
            $viewRolePermission,
            $addRolePermission,
            $updateRolePermission,
            $deleteRolePermission,
            $replicateRolePermission,
            $restoreRolePermission,
            $forceDeleteRolePermission,
            // Permission
            $viewAnyPermissionPermission,
            $viewPermissionPermission,
            $addPermissionPermission,
            $updatePermissionPermission,
            $deletePermissionPermission,
            $replicatePermissionPermission,
            $restorePermissionPermission,
            $forceDeletePermissionPermission,
            // Task
            $viewAnyTaskPermission,
            $viewTaskPermission,
            $addTaskPermission,
            $updateTaskPermission,
            $deleteTaskPermission,
            $replicateTaskPermission,
            $restoreTaskPermission,
            $forceDeleteTaskPermission,
            // History
            $viewAnyHistoryPermission,
            $viewHistoryPermission,
            $addHistoryPermission,
            $updateHistoryPermission,
            $deleteHistoryPermission,
            $replicateHistoryPermission,
            $restoreHistoryPermission,
            $forceDeleteHistoryPermission,
            // Attachment
            $viewAnyAttachmentPermission,
            $viewAttachmentPermission,
            $addAttachmentPermission,
            $updateAttachmentPermission,
            $deleteAttachmentPermission,
            $replicateAttachmentPermission,
            $restoreAttachmentPermission,
            $forceDeleteAttachmentPermission,
            // Comment
            $viewAnyCommentPermission,
            $viewCommentPermission,
            $addCommentPermission,
            $updateCommentPermission,
            $deleteCommentPermission,
            $replicateCommentPermission,
            $restoreCommentPermission,
            $forceDeleteCommentPermission,
            // Reply
            $viewAnyReplyPermission,
            $viewReplyPermission,
            $addReplyPermission,
            $updateReplyPermission,
            $deleteReplyPermission,
            $replicateReplyPermission,
            $restoreReplyPermission,
            $forceDeleteReplyPermission,
            // Workspace
            $viewAnyWorkspacePermission,
            $viewWorkspacePermission,
            $addWorkspacePermission,
            $updateWorkspacePermission,
            $deleteWorkspacePermission,
            $replicateWorkspacePermission,
            $restoreWorkspacePermission,
            $forceDeleteWorkspacePermission,

            // Share Workspace
            $viewAnyShareWSPermission,
            $viewShareWSPermission,
            $addShareWSPermission,
            $updateShareWSPermission,
            $deleteShareWSPermission,
            $replicateShareWSPermission,
            $restoreShareWSPermission,
            $forceDeleteShareWSPermission,
            // 
            $viewAnyWorkspaceTeamPermission,
            $viewWorkspaceTeamPermission,
            $addWorkspaceTeamPermission,
            $updateWorkspaceTeamPermission,
            $deleteWorkspaceTeamPermission,
            $replicateWorkspaceTeamPermission,
            $restoreWorkspaceTeamPermission,
            $forceDeleteWorkspaceTeamPermission,

            // Team members
            $viewAnyWSTeamMemberPermission,
            $viewWSTeamMemberPermission,
            $addWSTeamMemberPermission,
            $updateWSTeamMemberPermission,
            $deleteWSTeamMemberPermission,
            $replicateWSTeamMemberPermission,
            $restoreWSTeamMemberPermission,
            $forceDeleteWSTeamMemberPermission,
            $inviteDeleteWSTeamMemberPermission,
            // UserSetting
            $viewAnyUSSettingsPermission,
            $viewUSSettingsPermission,
            $addUSSettingsPermission,
            $updateUSSettingsPermission,
            $deleteUSSettingsPermission,
            $replicateUSSettingsPermission,
            $restoreUSSettingsPermission,
            $forceDeleteUSSettingsPermission,
            // Pixel
            $viewAnyPixelPermission,
            $viewPixelPermission,
            $addPixelPermission,
            $updatePixelPermission,
            $deletePixelPermission,
            $replicatePixelPermission,
            $restorePixelPermission,
            $forceDeletePixelPermission,
            // UTM Tag
            $viewAnyUtmTagPermission,
            $viewUtmTagPermission,
            $addUtmTagPermission,
            $updateUtmTagPermission,
            $deleteUtmTagPermission,
            $replicateUtmTagPermission,
            $restoreUtmTagPermission,
            $forceDeleteUtmTagPermission,
            // Short link
            $viewAnyShortLinkPermission,
            $viewShortLinkPermission,
            $addShortLinkPermission,
            $updateShortLinkPermission,
            $deleteShortLinkPermission,
            $replicateShortLinkPermission,
            $restoreShortLinkPermission,
            $forceDeleteShortLinkPermission,
            // Time slot
            $viewAnyTimeSlotPermission,
            $viewTimeSlotPermission,
            $addTimeSlotPermission,
            $updateTimeSlotPermission,
            $deleteTimeSlotPermission,
            $replicateTimeSlotPermission,
            $restoreTimeSlotPermission,
            $forceDeleteTimeSlotPermission,
            // Label
            $viewAnyLabelPermission,
            $viewLabelPermission,
            $addLabelPermission,
            $updateLabelPermission,
            $deleteLabelPermission,
            $replicateLabelPermission,
            $restoreLabelPermission,
            $forceDeleteLabelPermission,
            // Link-in-bio
            $viewAnyLinkInBioPermission,
            $viewLinkInBioPermission,
            $addLinkInBioPermission,
            $updateLinkInBioPermission,
            $deleteLinkInBioPermission,
            $replicateLinkInBioPermission,
            $restoreLinkInBioPermission,
            $forceDeleteLinkInBioPermission,
            // lib Block
            $viewAnyLibBlockPermission,
            $viewLibBlockPermission,
            $addLibBlockPermission,
            $updateLibBlockPermission,
            $deleteLibBlockPermission,
            $replicateLibBlockPermission,
            $restoreLibBlockPermission,
            $forceDeleteLibBlockPermission,
            // lib pre defined data
            $viewAnyLibPerDefinedDataPermission,
            $viewLibPerDefinedDataPermission,
            $addLibPerDefinedDataPermission,
            $updateLibPerDefinedDataPermission,
            $deleteLibPerDefinedDataPermission,
            $replicateLibPerDefinedDataPermission,
            $restoreLibPerDefinedDataPermission,
            $forceDeleteLibPerDefinedDataPermission,
            // Custom domain
            $viewAnyCustomDomainPermission,
            $viewCustomDomainPermission,
            $addCustomDomainPermission,
            $updateCustomDomainPermission,
            $deleteCustomDomainPermission,
            $replicateCustomDomainPermission,
            $restoreCustomDomainPermission,
            $forceDeleteCustomDomainPermission,
            // Api key
            $viewAnyApiKeyPermission,
            $viewApiKeyPermission,
            $addApiKeyPermission,
            $updateApiKeyPermission,
            $deleteApiKeyPermission,
            $sortFolderPermission,
            $replicateApiKeyPermission,
            $restoreApiKeyPermission,
            $forceDeleteApiKeyPermission,
            // Folder
            $viewAnyFolderPermission,
            $viewFolderPermission,
            $addFolderPermission,
            $updateFolderPermission,
            $deleteFolderPermission,
            $replicateFolderPermission,
            $restoreFolderPermission,
            $forceDeleteFolderPermission,
            // shortlink folder
            $viewAnySLFolderPermission,
            $viewSLFolderPermission,
            $addSLFolderPermission,
            $updateSLFolderPermission,
            $deleteSLFolderPermission,
            $sortSLFolderPermission,
            $replicateSLFolderPermission,
            $restoreSLFolderPermission,
            $forceDeleteSLFolderPermission,
            // link-in-bio folders
            $viewAnyLIBFolderPermission,
            $viewLIBFolderPermission,
            $addLIBFolderPermission,
            $updateLIBFolderPermission,
            $deleteLIBFolderPermission,
            $sortLIBFolderPermission,
            $replicateLIBFolderPermission,
            $restoreLIBFolderPermission,
            $forceDeleteLIBFolderPermission,
            // Embeded Widget
            $viewAnyEmbededWidgetPermission,
            $viewEmbededWidgetPermission,
            $addEmbededWidgetPermission,
            $updateEmbededWidgetPermission,
            $deleteEmbededWidgetPermission,
            $replicateEmbededWidgetPermission,
            $restoreEmbededWidgetPermission,
            $forceDeleteEmbededWidgetPermission,
            // Workspace pixel connections
            $attachPixelToWorkspacePermission,
            $detachPixelFromWorkspacePermission,
            $updateWorkspacePixelPermission,
            // Workspace utm tag connections
            $attachUtmTagToWorkspacePermission,
            $detachUtmTagFromWorkspacePermission,
            $updateWorkspaceUtmTagPermission,
            // Workspace member
            $attachWorkspaceMemberPermission,
            $detachWorkspaceMemberPermission,
            $updateWorkspaceMemberPermission,

            // Impersonation
            $canImpersonatePermission,
            // $canBeImpersonatePermission // this is commented by ahsan, as no one in app should impersonate super admin user account
        ];

        // can 
        $adminRolePermissions = array_filter($superAdminRolePermissions, function ($permission) {
            return !Str::of($permission->name)->contains('restore_') && !Str::of($permission->name)->contains('forceDelete_');
        });

        // add canBeImpersonatePermission Permission
        array_push($adminRolePermissions, $canBeImpersonatePermission);

        // can only update/delete his own data
        $userRolePermissions = array_filter($adminRolePermissions, function ($permission) {
            return (!Str::of($permission->name)->contains('_user') && !Str::of($permission->name)->endsWith('_userSetting')) && !Str::of($permission->name)->contains('_role') && !Str::of($permission->name)->contains('_permission') && !Str::of($permission->name)->contains('Impersonate_');
        });

        // Assign permissions to roles
        $superAdminRole->syncPermissions($superAdminRolePermissions);
        $adminRole->syncPermissions($adminRolePermissions);
        $userRole->syncPermissions($userRolePermissions);
    }
}
