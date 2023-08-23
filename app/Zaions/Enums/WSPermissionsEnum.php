<?php

namespace App\Zaions\Enums;


enum WSPermissionsEnum: string
{
    // member
  case viewAny_member = 'viewAny_member';
  case view_member = 'view_member';
  case create_member = 'create_member';
  case update_member = 'update_member';
  case delete_member = 'delete_member';
  case replicate_member = 'replicate_member';
  case restore_member = 'restore_member';
  case forceDelete_member = 'forceDelete_member';


    // comment
  case viewAny_ws_comment = 'viewAny_ws_comment';
  case view_ws_comment = 'view_ws_comment';
  case create_ws_comment = 'create_ws_comment';
  case update_ws_comment = 'update_ws_comment';
  case delete_ws_comment = 'delete_ws_comment';
  case replicate_ws_comment = 'replicate_ws_comment';
  case restore_ws_comment = 'restore_ws_comment';
  case forceDelete_ws_comment = 'forceDelete_ws_comment';
}
