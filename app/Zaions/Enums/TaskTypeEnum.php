<?php

namespace App\Zaions\Enums;


enum TaskTypeEnum: string
{
  case namaz = 'namaz';
  case exercise = 'exercise';
  case quran = 'quran';
  case dailyOfficeTime = 'dailyOfficeTime';
  case course = 'course';
  case officeWorkTask = 'officeWorkTask';
  case other = 'other';
}
