<?php

namespace App\Zaions\Enums;


enum TaskStatusEnum: string
{
  case todo = 'todo';
  case inProgress = 'inProgress';
  case requireInfo = 'requireInfo';
  case availableForReview = 'availableForReview';
  case done = 'done';
  case closed = 'closed';
  case other = 'other';
}
