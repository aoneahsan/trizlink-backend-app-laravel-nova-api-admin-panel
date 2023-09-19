<?php

namespace App\Zaions\Enums;


enum RolesEnum: string
{
  case superAdmin = 'superAdmin';
  case admin = 'admin';
  case user = 'user';

    // Workspace roles
  case ws_administrator = 'Administrator';
  case ws_manager = 'Manager';
  case ws_contributor = 'Contributor';
  case ws_writer = 'Writer';
  case ws_approver = 'Approver';
  case ws_commenter = 'Commenter';
  case ws_guest = 'Guest';
}
