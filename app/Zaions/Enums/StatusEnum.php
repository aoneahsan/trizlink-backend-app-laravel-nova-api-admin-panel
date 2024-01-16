<?php

namespace App\Zaions\Enums;

enum StatusEnum: string
{
  case publish = 'publish';
  case draft = 'draft';
  case private = 'private';
}
