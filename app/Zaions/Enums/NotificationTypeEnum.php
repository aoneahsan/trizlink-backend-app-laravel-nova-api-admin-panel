<?php

namespace App\Zaions\Enums;

enum NotificationTypeEnum: string
{
  case newDeviceLogin = 'newDeviceLogin';
  case lastLogout = 'lastLogout';
  case wsTeamMemberInvitation = 'wsTeamMemberInvitation';
  case wsMemberInviteAction = 'wsMemberInviteAction'; // action by invitee in invitee accept or reject
  case personal = 'personal'; // all user personal notification like any invitation etc.
  case workspace = 'workspace'; // all workspace notification.
  case updates = 'updates'; // all app updates notification.
}
