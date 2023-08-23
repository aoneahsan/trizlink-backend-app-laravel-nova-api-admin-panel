<?php

namespace App\Zaions\Enums;


enum RolesEnum: string
{
  case superAdmin = 'superAdmin';
  case admin = 'admin';
  case user = 'user';

    // Workspace roles
  case ws_contributor = 'ws_contributor';
  case ws_administrator = 'ws_administrator';
  case ws_writer = 'ws_writer';
  case ws_approver = 'ws_approver';
  case ws_guest = 'ws_guest';
}
