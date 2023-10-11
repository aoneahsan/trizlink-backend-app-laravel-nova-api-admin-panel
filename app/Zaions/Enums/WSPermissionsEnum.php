<?php

namespace App\Zaions\Enums;


enum WSPermissionsEnum: string
{

    // sws => ShareWorkspace.

    // member
  case viewAny_sws_member = 'viewAny_sws_member';
  case view_sws_member = 'view_sws_member';
  case create_sws_member = 'create_sws_member';
  case update_sws_member = 'update_sws_member';
  case delete_sws_member = 'delete_sws_member';
  case replicate_sws_member = 'replicate_sws_member';
  case restore_sws_member = 'restore_sws_member';
  case forceDelete_sws_member = 'forceDelete_sws_member';

    // Workspace
  case viewAny_sws_workspace = 'viewAny_sws_workspace';
  case view_sws_workspace = 'view_sws_workspace';
  case create_sws_workspace = 'create_sws_workspace';
  case leave_sws_workspace = 'leave_sws_workspace';
  case update_sws_workspace = 'update_sws_workspace';
  case delete_sws_workspace = 'delete_sws_workspace';
  case replicate_sws_workspace = 'replicate_sws_workspace';
  case restore_sws_workspace = 'restore_sws_workspace';
  case forceDelete_sws_workspace = 'forceDelete_sws_workspace';

    // US Settings
  case viewAny_sws_USSettings = 'viewAny_sws_USSettings';
  case view_sws_USSettings = 'view_sws_USSettings';
  case create_sws_USSettings = 'create_sws_USSettings';
  case update_sws_USSettings = 'update_sws_USSettings';
  case delete_sws_USSettings = 'delete_sws_USSettings';
  case replicate_sws_USSettings = 'replicate_sws_USSettings';
  case restore_sws_USSettings = 'restore_sws_USSettings';
  case forceDelete_sws_USSettings = 'forceDelete_sws_USSettings';

    // Pixel
  case viewAny_sws_pixel = 'viewAny_sws_pixel';
  case view_sws_pixel = 'view_sws_pixel';
  case create_sws_pixel = 'create_sws_pixel';
  case update_sws_pixel = 'update_sws_pixel';
  case delete_sws_pixel = 'delete_sws_pixel';
  case replicate_sws_pixel = 'replicate_sws_pixel';
  case restore_sws_pixel = 'restore_sws_pixel';
  case forceDelete_sws_pixel = 'forceDelete_sws_pixel';

    // Utm Tag
  case viewAny_sws_utmTag = 'viewAny_sws_utmTag';
  case view_sws_utmTag = 'view_sws_utmTag';
  case create_sws_utmTag = 'create_sws_utmTag';
  case update_sws_utmTag = 'update_sws_utmTag';
  case delete_sws_utmTag = 'delete_sws_utmTag';
  case replicate_sws_utmTag = 'replicate_sws_utmTag';
  case restore_sws_utmTag = 'restore_sws_utmTag';
  case forceDelete_sws_utmTag = 'forceDelete_sws_utmTag';

    // Embed widgets
  case viewAny_sws_embededWidget = 'viewAny_sws_embededWidget';
  case view_sws_embededWidget = 'view_sws_embededWidget';
  case create_sws_embededWidget = 'create_sws_embededWidget';
  case update_sws_embededWidget = 'update_sws_embededWidget';
  case delete_sws_embededWidget = 'delete_sws_embededWidget';
  case replicate_sws_embededWidget = 'replicate_sws_embededWidget';
  case restore_sws_embededWidget = 'restore_sws_embededWidget';
  case forceDelete_sws_embededWidget = 'forceDelete_sws_embededWidget';

    // short link
  case viewAny_sws_shortLink = 'viewAny_sws_shortLink';
  case view_sws_shortLink = 'view_sws_shortLink';
  case create_sws_shortLink = 'create_sws_shortLink';
  case update_sws_shortLink = 'update_sws_shortLink';
  case delete_sws_shortLink = 'delete_sws_shortLink';
  case replicate_sws_shortLink = 'replicate_sws_shortLink';
  case restore_sws_shortLink = 'restore_sws_shortLink';
  case forceDelete_sws_shortLink = 'forceDelete_sws_shortLink';

    // link in bio
  case viewAny_sws_linkInBio = 'viewAny_sws_linkInBio';
  case view_sws_linkInBio = 'view_sws_linkInBio';
  case create_sws_linkInBio = 'create_sws_linkInBio';
  case update_sws_linkInBio = 'update_sws_linkInBio';
  case delete_sws_linkInBio = 'delete_sws_linkInBio';
  case replicate_sws_linkInBio = 'replicate_sws_linkInBio';
  case restore_sws_linkInBio = 'restore_sws_linkInBio';
  case forceDelete_sws_linkInBio = 'forceDelete_sws_linkInBio';

    // time slot
  case viewAny_sws_timeSlot = 'viewAny_sws_timeSlot';
  case view_sws_timeSlot = 'view_sws_timeSlot';
  case create_sws_timeSlot = 'create_sws_timeSlot';
  case update_sws_timeSlot = 'update_sws_timeSlot';
  case delete_sws_timeSlot = 'delete_sws_timeSlot';
  case replicate_sws_timeSlot = 'replicate_sws_timeSlot';
  case restore_sws_timeSlot = 'restore_sws_timeSlot';
  case forceDelete_sws_timeSlot = 'forceDelete_sws_timeSlot';

    // label
  case viewAny_sws_label = 'viewAny_sws_label';
  case view_sws_label = 'view_sws_label';
  case create_sws_label = 'create_sws_label';
  case update_sws_label = 'update_sws_label';
  case delete_sws_label = 'delete_sws_label';
  case replicate_sws_label = 'replicate_sws_label';
  case restore_sws_label = 'restore_sws_label';
  case forceDelete_sws_label = 'forceDelete_sws_label';

    // comment
  case viewAny_sws_comment = 'viewAny_sws_comment';
  case view_sws_comment = 'view_sws_comment';
  case create_sws_comment = 'create_sws_comment';
  case update_sws_comment = 'update_sws_comment';
  case delete_sws_comment = 'delete_sws_comment';
  case replicate_sws_comment = 'replicate_sws_comment';
  case restore_sws_comment = 'restore_sws_comment';
  case forceDelete_sws_comment = 'forceDelete_sws_comment';

    // Folder
  case viewAny_sws_folder = 'viewAny_sws_folder';
  case view_sws_folder = 'view_sws_folder';
  case create_sws_folder = 'create_sws_folder';
  case update_sws_folder = 'update_sws_folder';
  case delete_sws_folder = 'delete_sws_folder';
  case sort_sws_folder = 'sort_sws_folder';
  case replicate_sws_folder = 'replicate_sws_folder';
  case restore_sws_folder = 'restore_sws_folder';
  case forceDelete_sws_folder = 'forceDelete_sws_folder';

    // Short links Folder
  case viewAny_sws_sl_folder = 'viewAny_sws_sl_folder';
  case view_sws_sl_folder = 'view_sws_sl_folder';
  case create_sws_sl_folder = 'create_sws_sl_folder';
  case update_sws_sl_folder = 'update_sws_sl_folder';
  case delete_sws_sl_folder = 'delete_sws_sl_folder';
  case sort_sws_sl_folder = 'sort_sws_sl_folder';
  case replicate_sws_sl_folder = 'replicate_sws_sl_folder';
  case restore_sws_sl_folder = 'restore_sws_sl_folder';
  case forceDelete_sws_sl_folder = 'forceDelete_sws_sl_folder';

    // Link-in-bio Folder
  case viewAny_sws_lib_folder = 'viewAny_sws_lib_folder';
  case view_sws_lib_folder = 'view_sws_lib_folder';
  case create_sws_lib_folder = 'create_sws_lib_folder';
  case update_sws_lib_folder = 'update_sws_lib_folder';
  case delete_sws_lib_folder = 'delete_sws_lib_folder';
  case sort_sws_lib_folder = 'sort_sws_lib_folder';
  case replicate_sws_lib_folder = 'replicate_sws_lib_folder';
  case restore_sws_lib_folder = 'restore_sws_lib_folder';
  case forceDelete_sws_lib_folder = 'forceDelete_sws_lib_folder';
}
