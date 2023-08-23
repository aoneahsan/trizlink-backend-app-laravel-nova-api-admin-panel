<?php

namespace App\Zaions\Enums;

enum NotificationTypeEnum: string
{
  case newDeviceLogin = 'newDeviceLogin';
  case lastLogout = 'lastLogout';
}
