<?php

namespace App\Zaions\Enums;


enum SignUpTypeEnum: string
{
  case normal = 'normal';
  case admin = 'admin';
  case invite = 'invite';
}
