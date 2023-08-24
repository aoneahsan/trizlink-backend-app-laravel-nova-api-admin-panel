<?php

namespace App\Zaions\Enums;


enum WSMemberAccountStatusEnum: string
{
  case pending = 'pending';
  case active = 'active';
  case suspended = 'suspended';
  case blocked = 'blocked';
}
