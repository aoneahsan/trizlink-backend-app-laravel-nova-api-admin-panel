<?php

namespace App\Zaions\Enums;


enum WSMemberAccountStatusEnum: string
{
  case pending = 'pending';
  case active = 'active';
  case resend = 'resend';
  case suspended = 'suspended';
  case blocked = 'blocked';
  case cancel = 'cancel';
  case accepted = 'accepted';
  case rejected = 'rejected';
  case leaved = 'leaved';
}
