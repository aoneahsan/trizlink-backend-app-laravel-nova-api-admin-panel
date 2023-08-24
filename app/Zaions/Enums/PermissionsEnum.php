<?php

namespace App\Zaions\Enums;


enum PermissionsEnum: string
{
    // Role
  case viewAny_role = 'viewAny_role';
  case view_role = 'view_role';
  case create_role = 'create_role';
  case update_role = 'update_role';
  case delete_role = 'delete_role';
  case replicate_role = 'replicate_role';
  case restore_role = 'restore_role';
  case forceDelete_role = 'forceDelete_role';

    // Permission
  case viewAny_permission = 'viewAny_permission';
  case view_permission = 'view_permission';
  case create_permission = 'create_permission';
  case update_permission = 'update_permission';
  case delete_permission = 'delete_permission';
  case replicate_permission = 'replicate_permission';
  case restore_permission = 'restore_permission';
  case forceDelete_permission = 'forceDelete_permission';

    // Dashboard
  case view_dashboard = 'view_dashboard';

    // User
  case viewAny_user = 'viewAny_user';
  case view_user = 'view_user';
  case create_user = 'create_user';
  case update_user = 'update_user';
  case delete_user = 'delete_user';
  case replicate_user = 'replicate_user';
  case restore_user = 'restore_user';
  case forceDelete_user = 'forceDelete_user';

    // Workspace
  case viewAny_notification = 'viewAny_notification';
  case view_notification = 'view_notification';
  case create_notification = 'create_notification';
  case update_notification = 'update_notification';
  case delete_notification = 'delete_notification';
  case replicate_notification = 'replicate_notification';
  case restore_notification = 'restore_notification';
  case forceDelete_notification = 'forceDelete_notification';

    // Task
  case viewAny_task = 'viewAny_task';
  case view_task = 'view_task';
  case create_task = 'create_task';
  case update_task = 'update_task';
  case delete_task = 'delete_task';
  case replicate_task = 'replicate_task';
  case restore_task = 'restore_task';
  case forceDelete_task = 'forceDelete_task';

    // History
  case viewAny_history = 'viewAny_history';
  case view_history = 'view_history';
  case create_history = 'create_history';
  case update_history = 'update_history';
  case delete_history = 'delete_history';
  case replicate_history = 'replicate_history';
  case restore_history = 'restore_history';
  case forceDelete_history = 'forceDelete_history';

    // Attachment
  case viewAny_attachment = 'viewAny_attachment';
  case view_attachment = 'view_attachment';
  case create_attachment = 'create_attachment';
  case update_attachment = 'update_attachment';
  case delete_attachment = 'delete_attachment';
  case replicate_attachment = 'replicate_attachment';
  case restore_attachment = 'restore_attachment';
  case forceDelete_attachment = 'forceDelete_attachment';

    // Comment
  case viewAny_comment = 'viewAny_comment';
  case view_comment = 'view_comment';
  case create_comment = 'create_comment';
  case update_comment = 'update_comment';
  case delete_comment = 'delete_comment';
  case replicate_comment = 'replicate_comment';
  case restore_comment = 'restore_comment';
  case forceDelete_comment = 'forceDelete_comment';

    // Reply
  case viewAny_reply = 'viewAny_reply';
  case view_reply = 'view_reply';
  case create_reply = 'create_reply';
  case update_reply = 'update_reply';
  case delete_reply = 'delete_reply';
  case replicate_reply = 'replicate_reply';
  case restore_reply = 'restore_reply';
  case forceDelete_reply = 'forceDelete_reply';

    // Impersonation
  case can_impersonate = 'can_impersonate';
  case canBe_impersonate = 'canBe_impersonate';

    // Workspace
  case viewAny_workspace = 'viewAny_workspace';
  case view_workspace = 'view_workspace';
  case create_workspace = 'create_workspace';
  case update_workspace = 'update_workspace';
  case delete_workspace = 'delete_workspace';
  case replicate_workspace = 'replicate_workspace';
  case restore_workspace = 'restore_workspace';
  case forceDelete_workspace = 'forceDelete_workspace';

    // Workspace team
  case viewAny_workspaceTeam = 'viewAny_workspaceTeam';
  case view_workspaceTeam = 'view_workspaceTeam';
  case create_workspaceTeam = 'create_workspaceTeam';
  case update_workspaceTeam = 'update_workspaceTeam';
  case delete_workspaceTeam = 'delete_workspaceTeam';
  case replicate_workspaceTeam = 'replicate_workspaceTeam';
  case restore_workspaceTeam = 'restore_workspaceTeam';
  case forceDelete_workspaceTeam = 'forceDelete_workspaceTeam';

    // Workspace team
  case viewAny_WSTeamMember = 'viewAny_WSTeamMember';
  case view_WSTeamMember = 'view_WSTeamMember';
  case create_WSTeamMember = 'create_WSTeamMember';
  case update_WSTeamMember = 'update_WSTeamMember';
  case delete_WSTeamMember = 'delete_WSTeamMember';
  case replicate_WSTeamMember = 'replicate_WSTeamMember';
  case restore_WSTeamMember = 'restore_WSTeamMember';
  case forceDelete_WSTeamMember = 'forceDelete_WSTeamMember';
  case invite_WSTeamMember = 'invite_WSTeamMember';

    // Workspace Members
  case attach_workspace_members = 'attach_workspace_members';
  case detach_workspace_members = 'view_workspace_members';
  case update_workspace_members = 'create_workspace_members';

    // Workspace pixel connections
  case attach_pixel_to_workspace = 'attach_pixel_to_workspace';
  case detach_pixel_from_workspace = 'detach_pixel_from_workspace';
  case update_workspace_pixel = 'update_workspace_pixel';

    // Workspace utm tags connections
  case attach_utm_tag_to_workspace = 'attach_utm_tag_to_workspace';
  case detach_utm_tag_from_workspace = 'detach_utm_tag_from_workspace';
  case update_workspace_utm_tag = 'update_workspace_utm_tag';

    // Workspace
  case viewAny_userSetting = 'viewAny_userSetting';
  case view_userSetting = 'view_userSetting';
  case create_userSetting = 'create_userSetting';
  case update_userSetting = 'update_userSetting';
  case delete_userSetting = 'delete_userSetting';
  case replicate_userSetting = 'replicate_userSetting';
  case restore_userSetting = 'restore_userSetting';
  case forceDelete_userSetting = 'forceDelete_userSetting';

    // Pixel
  case viewAny_pixel = 'viewAny_pixel';
  case view_pixel = 'view_pixel';
  case create_pixel = 'create_pixel';
  case update_pixel = 'update_pixel';
  case delete_pixel = 'delete_pixel';
  case replicate_pixel = 'replicate_pixel';
  case restore_pixel = 'restore_pixel';
  case forceDelete_pixel = 'forceDelete_pixel';

    // Utm Tag
  case viewAny_utmTag = 'viewAny_utmTag';
  case view_utmTag = 'view_utmTag';
  case create_utmTag = 'create_utmTag';
  case update_utmTag = 'update_utmTag';
  case delete_utmTag = 'delete_utmTag';
  case replicate_utmTag = 'replicate_utmTag';
  case restore_utmTag = 'restore_utmTag';
  case forceDelete_utmTag = 'forceDelete_utmTag';

    // short link
  case viewAny_shortLink = 'viewAny_shortLink';
  case view_shortLink = 'view_shortLink';
  case create_shortLink = 'create_shortLink';
  case update_shortLink = 'update_shortLink';
  case delete_shortLink = 'delete_shortLink';
  case replicate_shortLink = 'replicate_shortLink';
  case restore_shortLink = 'restore_shortLink';
  case forceDelete_shortLink = 'forceDelete_shortLink';

    // link in bio
  case viewAny_linkInBio = 'viewAny_linkInBio';
  case view_linkInBio = 'view_linkInBio';
  case create_linkInBio = 'create_linkInBio';
  case update_linkInBio = 'update_linkInBio';
  case delete_linkInBio = 'delete_linkInBio';
  case replicate_linkInBio = 'replicate_linkInBio';
  case restore_linkInBio = 'restore_linkInBio';
  case forceDelete_linkInBio = 'forceDelete_linkInBio';

    // time slot
  case viewAny_timeSlot = 'viewAny_timeSlot';
  case view_timeSlot = 'view_timeSlot';
  case create_timeSlot = 'create_timeSlot';
  case update_timeSlot = 'update_timeSlot';
  case delete_timeSlot = 'delete_timeSlot';
  case replicate_timeSlot = 'replicate_timeSlot';
  case restore_timeSlot = 'restore_timeSlot';
  case forceDelete_timeSlot = 'forceDelete_timeSlot';

    // label
  case viewAny_label = 'viewAny_label';
  case view_label = 'view_label';
  case create_label = 'create_label';
  case update_label = 'update_label';
  case delete_label = 'delete_label';
  case replicate_label = 'replicate_label';
  case restore_label = 'restore_label';
  case forceDelete_label = 'forceDelete_label';

    // lib Block
  case viewAny_libBlock = 'viewAny_libBlock';
  case view_libBlock = 'view_libBlock';
  case create_libBlock = 'create_libBlock';
  case update_libBlock = 'update_libBlock';
  case delete_libBlock = 'delete_libBlock';
  case replicate_libBlock = 'replicate_libBlock';
  case restore_libBlock = 'restore_libBlock';
  case forceDelete_libBlock = 'forceDelete_libBlock';

    // lib pre defined data
  case viewAny_libPerDefinedData = 'viewAny_libPerDefinedData';
  case view_libPerDefinedData = 'view_libPerDefinedData';
  case create_libPerDefinedData = 'create_libPerDefinedData';
  case update_libPerDefinedData = 'update_libPerDefinedData';
  case delete_libPerDefinedData = 'delete_libPerDefinedData';
  case replicate_libPerDefinedData = 'replicate_libPerDefinedData';
  case restore_libPerDefinedData = 'restore_libPerDefinedData';
  case forceDelete_libPerDefinedData = 'forceDelete_libPerDefinedData';

    // custom domain
  case viewAny_customDomain = 'viewAny_customDomain';
  case view_customDomain = 'view_customDomain';
  case create_customDomain = 'create_customDomain';
  case update_customDomain = 'update_customDomain';
  case delete_customDomain = 'delete_customDomain';
  case replicate_customDomain = 'replicate_customDomain';
  case restore_customDomain = 'restore_customDomain';
  case forceDelete_customDomain = 'forceDelete_customDomain';

    // api key
  case viewAny_apiKey = 'viewAny_apiKey';
  case view_apiKey = 'view_apiKey';
  case create_apiKey = 'create_apiKey';
  case update_apiKey = 'update_apiKey';
  case delete_apiKey = 'delete_apiKey';
  case replicate_apiKey = 'replicate_apiKey';
  case restore_apiKey = 'restore_apiKey';
  case forceDelete_apiKey = 'forceDelete_apiKey';

    // Folder
  case viewAny_folder = 'viewAny_folder';
  case view_folder = 'view_folder';
  case create_folder = 'create_folder';
  case update_folder = 'update_folder';
  case delete_folder = 'delete_folder';
  case replicate_folder = 'replicate_folder';
  case restore_folder = 'restore_folder';
  case forceDelete_folder = 'forceDelete_folder';

    // Embeded widgets
  case viewAny_embededWidget = 'viewAny_embededWidget';
  case view_embededWidget = 'view_embededWidget';
  case create_embededWidget = 'create_embededWidget';
  case update_embededWidget = 'update_embededWidget';
  case delete_embededWidget = 'delete_embededWidget';
  case replicate_embededWidget = 'replicate_embededWidget';
  case restore_embededWidget = 'restore_embededWidget';
  case forceDelete_embededWidget = 'forceDelete_embededWidget';

  // case viewAny_ = 'viewAny_';
  // case view_ = 'view_';
  // case create_ = 'create_';
  // case update_ = 'update_';
  // case delete_ = 'delete_';
  // case replicate_ = 'replicate_';
  // case restore_ = 'restore_';
  // case forceDelete_ = 'forceDelete_';
}
