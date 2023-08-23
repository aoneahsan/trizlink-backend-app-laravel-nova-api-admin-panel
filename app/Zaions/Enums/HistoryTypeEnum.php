<?php

namespace App\Zaions\Enums;


enum HistoryTypeEnum: string
{
  case courseUpdate = 'courseUpdate';
  case officeTaskUpdate = 'officeTaskUpdate';
  case other = 'other';
}
