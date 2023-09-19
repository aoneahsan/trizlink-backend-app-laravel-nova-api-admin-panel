<?php

namespace App\Zaions\Enums;

enum NotificationTypeEnum: string
{
  case newDeviceLogin = 'newDeviceLogin';
  case lastLogout = 'lastLogout';
  case wsTeamMemberInvitation = 'wsTeamMemberInvitation';
  case wsMemberInviteAction = 'wsMemberInviteAction';
}
