<?php

namespace App\Zaions\Enums;


enum ResponseCodesEnum: int
{
  case Success = 200;
  case Created = 201;
  case Updated = 202;
  case Deleted = 203;
  case BadRequest = 400;
  case Unauthorized = 401;
  case Unauthenticated = 403;
  case NotFound = 404;
  case ServerError = 500;
}
