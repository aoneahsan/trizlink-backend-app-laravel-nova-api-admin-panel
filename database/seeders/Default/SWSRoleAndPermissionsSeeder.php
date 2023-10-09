<?php

namespace Database\Seeders\Default;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Enums\RoleTypesEnum;
use App\Zaions\Enums\WSPermissionsEnum;

class SWSRoleAndPermissionsSeeder extends Seeder
{

  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // ***  workspace member roles *** 
    $wsAdministrator = Role::create(['name' => RolesEnum::ws_administrator->value, 'roleType' => RoleTypesEnum::inAppWSRole->name]);

    $wsManager = Role::create(['name' => RolesEnum::ws_manager->value, 'roleType' => RoleTypesEnum::inAppWSRole->name]);

    $wsContributor = Role::create(['name' => RolesEnum::ws_contributor->value, 'roleType' => RoleTypesEnum::inAppWSRole->name]);

    $wsWriter = Role::create(['name' => RolesEnum::ws_writer->value, 'roleType' => RoleTypesEnum::inAppWSRole->name]);

    $wsApprover = Role::create(['name' => RolesEnum::ws_approver->value, 'roleType' => RoleTypesEnum::inAppWSRole->name]);

    $wsCommenter = Role::create(['name' => RolesEnum::ws_commenter->value, 'roleType' => RoleTypesEnum::inAppWSRole->name]);

    $wsGuest = Role::create(['name' => RolesEnum::ws_guest->value, 'roleType' => RoleTypesEnum::inAppWSRole->name]);


    // *** All Share workspace permissions *** 
    // Share workspace model permissions
    $viewAnySWSWorkspacePermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_workspace->name]);
    $viewSWSWorkspacePermission = Permission::create(['name' => WSPermissionsEnum::view_sws_workspace->name]);
    $addSWSWorkspacePermission = Permission::create(['name' => WSPermissionsEnum::create_sws_workspace->name]);
    $leaveSWSWorkspacePermission = Permission::create(['name' => WSPermissionsEnum::leave_sws_workspace->name]);
    $updateSWSWorkspacePermission = Permission::create(['name' => WSPermissionsEnum::update_sws_workspace->name]);
    $deleteSWSWorkspacePermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_workspace->name]);
    $replicateSWSWorkspacePermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_workspace->name]);
    $restoreSWSWorkspacePermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_workspace->name]);
    $forceDeleteSWSWorkspacePermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_workspace->name]);

    // Share member model permissions
    $viewAnySWSMemberPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_member->name]);
    $viewSWSMemberPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_member->name]);
    $addSWSMemberPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_member->name]);
    $updateSWSMemberPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_member->name]);
    $deleteSWSMemberPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_member->name]);
    $replicateSWSMemberPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_member->name]);
    $restoreSWSMemberPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_member->name]);
    $forceDeleteSWSMemberPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_member->name]);

    // Share comment model permissions
    $viewAnySWSCommentPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_comment->name]);
    $viewSWSCommentPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_comment->name]);
    $addSWSCommentPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_comment->name]);
    $updateSWSCommentPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_comment->name]);
    $deleteSWSCommentPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_comment->name]);
    $replicateSWSCommentPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_comment->name]);
    $restoreSWSCommentPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_comment->name]);
    $forceDeleteSWSCommentPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_comment->name]);

    // Share pixel model permissions
    $viewAnySWSPixelPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_pixel->name]);
    $viewSWSPixelPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_pixel->name]);
    $addSWSPixelPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_pixel->name]);
    $updateSWSPixelPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_pixel->name]);
    $deleteSWSPixelPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_pixel->name]);
    $replicateSWSPixelPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_pixel->name]);
    $restoreSWSPixelPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_pixel->name]);
    $forceDeleteSWSPixelPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_pixel->name]);

    // Share utm model permissions
    $viewAnySWSUtmTagPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_utmTag->name]);
    $viewSWSUtmTagPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_utmTag->name]);
    $addSWSUtmTagPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_utmTag->name]);
    $updateSWSUtmTagPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_utmTag->name]);
    $deleteSWSUtmTagPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_utmTag->name]);
    $replicateSWSUtmTagPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_utmTag->name]);
    $restoreSWSUtmTagPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_utmTag->name]);
    $forceDeleteSWSUtmTagPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_utmTag->name]);

    // User settings.
    $viewAnySWSUSSettings = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_USSettings->name]);
    $viewSWSUSSettings = Permission::create(['name' => WSPermissionsEnum::view_sws_USSettings->name]);
    $addSWSUSSettings = Permission::create(['name' => WSPermissionsEnum::create_sws_USSettings->name]);
    $updateSWSUSSettings = Permission::create(['name' => WSPermissionsEnum::update_sws_USSettings->name]);
    $deleteSWSUSSettings = Permission::create(['name' => WSPermissionsEnum::delete_sws_USSettings->name]);
    $replicateSWSUSSettings = Permission::create(['name' => WSPermissionsEnum::replicate_sws_USSettings->name]);
    $restoreSWSUSSettings = Permission::create(['name' => WSPermissionsEnum::restore_sws_USSettings->name]);
    $forceDeleteSWSUSSettings = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_USSettings->name]);

    // Share embed widget model permissions
    $viewAnySWSEmbededWidgetPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_embededWidget->name]);
    $viewSWSEmbededWidgetPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_embededWidget->name]);
    $addSWSEmbededWidgetPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_embededWidget->name]);
    $updateSWSEmbededWidgetPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_embededWidget->name]);
    $deleteSWSEmbededWidgetPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_embededWidget->name]);
    $replicateSWSEmbededWidgetPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_embededWidget->name]);
    $restoreSWSEmbededWidgetPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_embededWidget->name]);
    $forceDeleteSWSEmbededWidgetPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_embededWidget->name]);

    // Share short link model permissions
    $viewAnySWSShortLinkPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_shortLink->name]);
    $viewSWSShortLinkPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_shortLink->name]);
    $addSWSShortLinkPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_shortLink->name]);
    $updateSWSShortLinkPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_shortLink->name]);
    $deleteSWSShortLinkPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_shortLink->name]);
    $replicateSWSShortLinkPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_shortLink->name]);
    $restoreSWSShortLinkPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_shortLink->name]);
    $forceDeleteSWSShortLinkPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_shortLink->name]);

    // Share time slot model permissions
    $viewAnySWSTimeSlotPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_timeSlot->name]);
    $viewSWSTimeSlotPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_timeSlot->name]);
    $addSWSTimeSlotPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_timeSlot->name]);
    $updateSWSTimeSlotPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_timeSlot->name]);
    $deleteSWSTimeSlotPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_timeSlot->name]);
    $replicateSWSTimeSlotPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_timeSlot->name]);
    $restoreSWSTimeSlotPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_timeSlot->name]);
    $forceDeleteSWSTimeSlotPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_timeSlot->name]);

    // Share label model permissions
    $viewAnySWSLabelPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_label->name]);
    $viewSWSLabelPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_label->name]);
    $addSWSLabelPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_label->name]);
    $updateSWSLabelPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_label->name]);
    $deleteSWSLabelPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_label->name]);
    $replicateSWSLabelPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_label->name]);
    $restoreSWSLabelPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_label->name]);
    $forceDeleteSWSLabelPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_label->name]);

    // Share link-in-bio model permissions
    $viewAnySWSLinkInBioPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_linkInBio->name]);
    $viewSWSLinkInBioPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_linkInBio->name]);
    $addSWSLinkInBioPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_linkInBio->name]);
    $deleteSWSLinkInBioPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_linkInBio->name]);
    $replicateSWSLinkInBioPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_linkInBio->name]);
    $restoreSWSLinkInBioPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_linkInBio->name]);
    $forceDeleteSWSLinkInBioPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_linkInBio->name]);
    $updateSWSLinkInBioPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_linkInBio->name]);

    // Folder Permissions
    $viewAnySWSFolderPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_folder->name]);
    $viewSWSFolderPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_folder->name]);
    $addSWSFolderPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_folder->name]);
    $updateSWSFolderPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_folder->name]);
    $deleteSWSFolderPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_folder->name]);
    $sortSWSFolderPermission = Permission::create(['name' => WSPermissionsEnum::sort_sws_folder->name]);
    $replicateSWSFolderPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_folder->name]);
    $restoreSWSFolderPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_folder->name]);
    $forceDeleteSWSFolderPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_folder->name]);

    // Short links folder Model Permissions
    $viewAnySwsSLFolderPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_sl_folder->name]);
    $viewSwsSLFolderPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_sl_folder->name]);
    $addSwsSLFolderPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_sl_folder->name]);
    $updateSwsSLFolderPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_sl_folder->name]);
    $deleteSwsSLFolderPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_sl_folder->name]);
    $sortSwsSLFolderPermission = Permission::create(['name' => WSPermissionsEnum::sort_sws_sl_folder->name]);
    $replicateSwsSLFolderPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_sl_folder->name]);
    $restoreSwsSLFolderPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_sl_folder->name]);
    $forceSwsSLDeleteFolderPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_sl_folder->name]);

    // Link in bio folder Model Permissions
    $viewAnySwsLIBFolderPermission = Permission::create(['name' => WSPermissionsEnum::viewAny_sws_lib_folder->name]);
    $viewSwsLIBFolderPermission = Permission::create(['name' => WSPermissionsEnum::view_sws_lib_folder->name]);
    $addSwsLIBFolderPermission = Permission::create(['name' => WSPermissionsEnum::create_sws_lib_folder->name]);
    $updateSwsLIBFolderPermission = Permission::create(['name' => WSPermissionsEnum::update_sws_lib_folder->name]);
    $deleteSwsLIBFolderPermission = Permission::create(['name' => WSPermissionsEnum::delete_sws_lib_folder->name]);
    $sortSwsLIBFolderPermission = Permission::create(['name' => WSPermissionsEnum::sort_sws_lib_folder->name]);
    $replicateSwsLIBFolderPermission = Permission::create(['name' => WSPermissionsEnum::replicate_sws_lib_folder->name]);
    $restoreSwsLIBFolderPermission = Permission::create(['name' => WSPermissionsEnum::restore_sws_lib_folder->name]);
    $forceSwsLIBDeleteFolderPermission = Permission::create(['name' => WSPermissionsEnum::forceDelete_sws_lib_folder->name]);


    $wsAdminRolePermissions = [
      // Workspace
      $viewAnySWSWorkspacePermission,
      $viewSWSWorkspacePermission,
      $addSWSWorkspacePermission,
      $updateSWSWorkspacePermission,
      $deleteSWSWorkspacePermission,
      $replicateSWSWorkspacePermission,
      $restoreSWSWorkspacePermission,
      $forceDeleteSWSWorkspacePermission,
      $leaveSWSWorkspacePermission,

      // Pixel
      $viewAnySWSPixelPermission,
      $viewSWSPixelPermission,
      $addSWSPixelPermission,
      $updateSWSPixelPermission,
      $deleteSWSPixelPermission,
      $replicateSWSPixelPermission,
      $restoreSWSPixelPermission,
      $forceDeleteSWSPixelPermission,

      // UTM Tag
      $viewAnySWSUtmTagPermission,
      $viewSWSUtmTagPermission,
      $addSWSUtmTagPermission,
      $updateSWSUtmTagPermission,
      $deleteSWSUtmTagPermission,
      $replicateSWSUtmTagPermission,
      $restoreSWSUtmTagPermission,
      $forceDeleteSWSUtmTagPermission,

      // User settings.
      $viewAnySWSUSSettings,
      $viewSWSUSSettings,
      $addSWSUSSettings,
      $updateSWSUSSettings,
      $deleteSWSUSSettings,
      $replicateSWSUSSettings,
      $restoreSWSUSSettings,
      $forceDeleteSWSUSSettings,

      // Embeded widget
      $viewAnySWSEmbededWidgetPermission,
      $viewSWSEmbededWidgetPermission,
      $addSWSEmbededWidgetPermission,
      $updateSWSEmbededWidgetPermission,
      $deleteSWSEmbededWidgetPermission,
      $replicateSWSEmbededWidgetPermission,
      $restoreSWSEmbededWidgetPermission,
      $forceDeleteSWSEmbededWidgetPermission,

      // Short link
      $viewAnySWSShortLinkPermission,
      $viewSWSShortLinkPermission,
      $addSWSShortLinkPermission,
      $updateSWSShortLinkPermission,
      $deleteSWSShortLinkPermission,
      $replicateSWSShortLinkPermission,
      $restoreSWSShortLinkPermission,
      $forceDeleteSWSShortLinkPermission,

      // Time slot
      $viewAnySWSTimeSlotPermission,
      $viewSWSTimeSlotPermission,
      $addSWSTimeSlotPermission,
      $updateSWSTimeSlotPermission,
      $deleteSWSTimeSlotPermission,
      $replicateSWSTimeSlotPermission,
      $restoreSWSTimeSlotPermission,
      $forceDeleteSWSTimeSlotPermission,

      // Label
      $viewAnySWSLabelPermission,
      $viewSWSLabelPermission,
      $addSWSLabelPermission,
      $updateSWSLabelPermission,
      $deleteSWSLabelPermission,
      $replicateSWSLabelPermission,
      $restoreSWSLabelPermission,
      $forceDeleteSWSLabelPermission,

      // Link-in-bio
      $viewAnySWSLinkInBioPermission,
      $viewSWSLinkInBioPermission,
      $addSWSLinkInBioPermission,
      $updateSWSLinkInBioPermission,
      $deleteSWSLinkInBioPermission,
      $replicateSWSLinkInBioPermission,
      $restoreSWSLinkInBioPermission,
      $forceDeleteSWSLinkInBioPermission,

      // Members.
      $viewAnySWSMemberPermission,
      $viewSWSMemberPermission,
      $addSWSMemberPermission,
      $updateSWSMemberPermission,
      $deleteSWSMemberPermission,
      $replicateSWSMemberPermission,
      $restoreSWSMemberPermission,
      $forceDeleteSWSMemberPermission,

      // Comments.
      $viewAnySWSCommentPermission,
      $viewSWSCommentPermission,
      $addSWSCommentPermission,
      $updateSWSCommentPermission,
      $deleteSWSCommentPermission,
      $replicateSWSCommentPermission,
      $restoreSWSCommentPermission,
      $forceDeleteSWSCommentPermission,

      // Folders
      $viewAnySWSFolderPermission,
      $viewSWSFolderPermission,
      $addSWSFolderPermission,
      $updateSWSFolderPermission,
      $deleteSWSFolderPermission,
      $sortSWSFolderPermission,
      $replicateSWSFolderPermission,
      $restoreSWSFolderPermission,
      $forceDeleteSWSFolderPermission,

      // Short link folder
      $viewAnySwsSLFolderPermission,
      $viewSwsSLFolderPermission,
      $addSwsSLFolderPermission,
      $updateSwsSLFolderPermission,
      $deleteSwsSLFolderPermission,
      $sortSwsSLFolderPermission,
      $replicateSwsSLFolderPermission,
      $restoreSwsSLFolderPermission,
      $forceSwsSLDeleteFolderPermission,

      // Link in bio folder
      $viewAnySwsLIBFolderPermission,
      $viewSwsLIBFolderPermission,
      $addSwsLIBFolderPermission,
      $updateSwsLIBFolderPermission,
      $deleteSwsLIBFolderPermission,
      $sortSwsLIBFolderPermission,
      $replicateSwsLIBFolderPermission,
      $restoreSwsLIBFolderPermission,
      $forceSwsLIBDeleteFolderPermission,
    ];

    /**
     * 1) Manager: will have all permission except members_permission.
     * 2) Contributor: can update, create, approve, and comments.
     * 3) Writer: create, and comments.
     * 4) Approver: view and comments.
     * 5) Commenter: comments.
     * 6) Guest: view.
     */

    $wsManagerRolePermissions = array_filter($wsAdminRolePermissions, function ($permission) {
      return !Str::of($permission->name)->contains('restore_') && !Str::of($permission->name)->contains('_member') && !Str::of($permission->name)->contains('replicate_') && !Str::of($permission->name)->contains('forceDelete_');
    });

    $wsContributorRolePermissions = array_filter($wsAdminRolePermissions, function ($permission) {
      return !Str::of($permission->name)->contains('restore_') && !Str::of($permission->name)->contains('_member') && !Str::of($permission->name)->contains('replicate_') && !Str::of($permission->name)->contains('delete_') && !Str::of($permission->name)->contains('forceDelete_');
    });

    $wsWriterRolePermissions =
      array_filter($wsAdminRolePermissions, function ($permission) {
        return !Str::of($permission->name)->contains('restore_') && !Str::of($permission->name)->contains('_member') && !Str::of($permission->name)->contains('update_') && !Str::of($permission->name)->contains('replicate_') && !Str::of($permission->name)->contains('delete_') && !Str::of($permission->name)->contains('forceDelete_');
      });

    $wsApproverRolePermissions =
      array_filter($wsAdminRolePermissions, function ($permission) {
        return !Str::of($permission->name)->contains('restore_') && !Str::of($permission->name)->contains('_member') && !Str::of($permission->name)->contains('create_') && !Str::of($permission->name)->contains('update_') && !Str::of($permission->name)->contains('replicate_') && !Str::of($permission->name)->contains('delete_') && !Str::of($permission->name)->contains('forceDelete_');
      });

    $wsCommenterRolePermissions =
      array_filter($wsAdminRolePermissions, function ($permission) {
        return Str::of($permission->name)->contains('_comment') && Str::of($permission->name)->contains('viewAny_') && Str::of($permission->name)->contains('view_') && !Str::of($permission->name)->contains('_member');
      });

    $wsGuestRolePermissions =
      array_filter($wsAdminRolePermissions, function ($permission) {
        return Str::of($permission->name)->contains('viewAny_') && Str::of($permission->name)->contains('view_');
      });;

    // Assign permissions to roles
    $wsAdministrator->syncPermissions($wsAdminRolePermissions);
    $wsManager->syncPermissions($wsManagerRolePermissions);
    $wsContributor->syncPermissions($wsContributorRolePermissions);
    $wsWriter->syncPermissions($wsWriterRolePermissions);
    $wsApprover->syncPermissions($wsApproverRolePermissions);
    $wsCommenter->syncPermissions($wsCommenterRolePermissions);
    $wsGuest->syncPermissions($wsGuestRolePermissions);
  }
}
