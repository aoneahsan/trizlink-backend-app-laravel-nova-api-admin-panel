<?php

namespace App\Zaions\Enums;


enum VerificationStatusEnum: string
{
  case pending = 'pending';
  case verified = 'verified';
  case approved = 'approved';
}
