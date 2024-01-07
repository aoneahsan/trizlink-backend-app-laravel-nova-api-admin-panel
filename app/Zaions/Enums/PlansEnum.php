<?php

namespace App\Zaions\Enums;

enum PlansEnum: string
{
  case free = 'free';
  case core = 'core';
  case growth = 'growth';
  case premium = 'premium';
  case enterprise = 'enterprise';
}
