<?php

namespace App\Zaions\Helpers;

use App\Zaions\Enums\NamazEnum;
use App\Zaions\Enums\RolesEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mockery\Undefined;
use \Illuminate\Validation\ValidationException;

class ZHelpers
{
  static public function isNRUserSuperAdmin(NovaRequest $request)
  {
    return $request->user()->hasRole(RolesEnum::superAdmin->name);
  }

  static public function getTimezone($request = null)
  {
    if ($request && $request->user() && $request->user()->userTimezone) {
      return $request->user()?->userTimezone;
    } else {
      return config('app.timezone');
    }
  }

  static public function convertTo12Hour(Carbon $time, $request = null)
  {
    // if ($time) {
    //   $hour = null;

    //   if ($time->hour === 0) {
    //     $hour = 12;
    //   } else if ($time->hour <= 12) {
    //     $hour =  $time->hour;
    //   } else if ($time->hour === 13) {
    //     $hour =  1;
    //   } else if ($time->hour === 14) {
    //     $hour =  2;
    //   } else if ($time->hour === 15) {
    //     $hour =  3;
    //   } else if ($time->hour === 16) {
    //     $hour =  4;
    //   } else if ($time->hour === 17) {
    //     $hour =  5;
    //   } else if ($time->hour === 18) {
    //     $hour =  6;
    //   } else if ($time->hour === 19) {
    //     $hour =  7;
    //   } else if ($time->hour === 20) {
    //     $hour =  8;
    //   } else if ($time->hour === 21) {
    //     $hour =  9;
    //   } else if ($time->hour === 22) {
    //     $hour =  10;
    //   } else if ($time->hour === 23) {
    //     $hour =  11;
    //   }

    //   $minute = $time->minute;
    //   $isAm = $hour < 12;
    //   return $hour != null ? array(
    //     'hour' => $hour,
    //     'minute' => $minute,
    //     'isAm' => $isAm
    //   ) : null;
    // } else {
    //   return null;
    // }
    if ($time) {

      $timeStamp = strtotime($time);
      date_default_timezone_set(ZHelpers::getTimezone($request));
      $hour = date('h', $timeStamp);
      $minute = date('i', $timeStamp);
      $isAm = date('A', $timeStamp) === 'AM';
      return array(
        'hour' => intval($hour),
        'minute' => intval($minute),
        'isAm' => $isAm
      );
    } else {
      return null;
    }
  }

  static public function getNamazTimes()
  {
    // the check for min values will be <=, and for max values will be >=
    // // i will subtract 10mins from minutes from namaz max time, so add values accordingly.
    // right now we are only using this function to define the fields for Task, "namazOffered" select field.
    return [
      // fajar time   -   min: 4:10AM  |  max:  6:40AM
      NamazEnum::fajar->name => [
        'min' => [
          'h' => 4,
          'm' => 10
        ],
        'max' => [
          'h' => 6,
          'm' => 40
        ]
      ],
      // zohar time   -   min: 1:10PM  |  max:  3:40PM
      NamazEnum::zohar->name => [
        'min' => [
          'h' => 1,
          'm' => 10
        ],
        'max' => [
          'h' => 3,
          'm' => 40
        ]
      ],
      // asar time   -   min: 5:10PM  |  max:  6:40PM
      NamazEnum::asar->name => [
        'min' => [
          'h' => 5,
          'm' => 10
        ],
        'max' => [
          'h' => 6,
          'm' => 40
        ]
      ],
      // magrib time   -   min: 6:41PM  |  max:  8:10PM
      NamazEnum::magrib->name => [
        'min' => [
          'h' => 6,
          'm' => 41
        ],
        'max' => [
          'h' => 8,
          'm' => 10
        ]
      ],
      // isha time   -   min: 8:10PM  |  max:  1:10AM
      NamazEnum::isha->name => [
        'min' => [
          'h' => 8,
          'm' => 10
        ],
        'max' => [
          'h' => 1,
          'm' => 10
        ]
      ],
      // juma time   -   min: 12:50PM  |  max:  2:40PM
      NamazEnum::juma->name => [
        'min' => [
          'h' => 12,
          'm' => 50
        ],
        'max' => [
          'h' => 2,
          'm' => 40
        ]
      ]
    ];
  }

  static public function getActiveFileDriver()
  {
    return env('FILESYSTEM_DISK', 'public');
  }


  // Copied from ZaionsHelpers - ZLink Laravel Project

  /** Zaions Role Names 
   *  Additional Role will go in the array.
   */
  public static function ZaionsRoleName()
  {
    return [
      'admin' => 'Admin',
      'shop_manager' => 'Shop Manager',
      'creator' => 'Creator',
      'viewer' => 'Viewer',
    ];
  }

  /** 
   *  Zaions role helper methods
   */

  public function is_admin($user)
  {
    $role = $this->ZaionsRoleName()['admin'];
    if ($user->hasRole($role)) {
      return true;
    }

    return false;
  }

  public static function sendBackRequestFailedResponse($errors)
  {
    return response()->json([
      'errors' => $errors,
      'data' => [],
      'success' => false,
      'status' => 500,
      'message' => 'Error Occurred, try again later.'
    ], 500);
  }

  public static function sendBackUnauthorizedResponse($errors)
  {
    return response()->json([
      'errors' => $errors,
      'data' => [],
      'success' => false,
      'status' => 401,
      'message' => 'Unauthorized.'
    ], 401);
  }

  public static function sendBackBadRequestResponse($errors)
  {
    return response()->json([
      'errors' => $errors,
      'data' => [],
      'success' => false,
      'status' => 400,
      'message' => 'Bad request.'
    ], 400);
  }

  public static function sendBackInvalidParamsResponse($errors)
  {
    return response()->json([
      'errors' => $errors,
      'data' => [],
      'success' => false,
      'status' => 400,
      'message' => 'Invalid params send, please send all required request params.'
    ], 500);
  }

  public static function sendBackNotFoundResponse($errors)
  {
    return response()->json([
      'errors' => $errors,
      'data' => [],
      'success' => false,
      'status' => 404,
      'message' => 'Not found.'
    ], 404);
  }


  public static function sendBackRequestCompletedResponse($data)
  {
    return response()->json([
      'errors' => [],
      'data' => $data,
      'success' => true,
      'status' => 200,
      'message' => 'Request completed successfully.'
    ], 200);
  }

  // send back server error response
  public static function sendBackServerErrorResponse(\Throwable $th)
  {

    if ($th instanceof ValidationException) {
      return response()->json(
        [
          'errors' => $th->errors(),
          'data' => [],
          'success' => false,
          'status' => 500,
          'message' => 'Error Occurred, try again later.'
        ],
        500
      );
    } else {
      return response()->json([
        'errors' => [
          'error' => [$th->getMessage()]
        ],
        'data' => [],
        'success' => false,
        'status' => 500,
        'message' => 'Error Occurred, try again later.'
      ], 500);
    }
  }

  // check if file exists
  public static function checkIfFileExists($filePath)
  {
    return $filePath && Storage::exists($filePath);
  }

  // get full file url
  public static function getFullFileUrl($filePath): string | null
  {
    if (ZHelpers::checkIfFileExists($filePath)) {
      $fileUrl = Storage::url($filePath);

      $appUrl = env('FILESYSTEM_ROOT_URL', 'https://zlink-backend.zaions.com/public');

      return $appUrl . $fileUrl;
      // return $appUrl;
      // return $fileUrl;
    } else {
      return null;
    }
  }

  // store file & return file path
  public static function storeFile(Request $request, $fileKey, $fileStorePath = 'uploaded-files')
  {
    if ($request->file($fileKey)) {
      $filePath = Storage::putFile($fileStorePath, $request->file($fileKey), 'public');

      $appUrl = env('FILESYSTEM_ROOT_URL', 'https://zlink-backend.zaions.com/public');

      return [
        'fileUrl' => $appUrl . '/' . $filePath,
        'filePath' => $filePath,
      ];
    } else {
      return null;
    }
  }

  // store file & return file path
  public static function storeFiles($files, $fileStorePath = 'uploaded-files')
  {
    if ($files) {
      $filesData = [];
      foreach ($files as $file) {
        $filePath = Storage::putFile($fileStorePath, $file, 'public');

        $appUrl = env('FILESYSTEM_ROOT_URL', 'https://zlink-backend.zaions.com/public');

        $filesData[] = [
          'fileUrl' => $appUrl . '/' . $filePath,
          'filePath' => $filePath,
        ];
      }
      return $filesData;
    } else {
      return null;
    }
  }

  // delete file if exists
  public static function deleteFile($filePath)
  {
    if ($filePath && ZHelpers::checkIfFileExists($filePath)) {
      $deleted = Storage::delete($filePath);

      return $deleted;
    } else {
      return false;
    }
  }

  public static function getThirdPartyApiRequestDetails()
  {
    return [
      'limit' => 20,
      'offset' => 0,
      'giphyApiKey' => 'e2ch1pYIBKfkBbEKy5eaCWTAGA8QyUx1'
    ];
  }

  public static function zJsonDecode($value)
  {
    try {
      if ($value && is_string($value)) {
        return json_decode($value);
      } else if (!$value) {
        return null;
      } else {
        return $value;
      }
    } catch (\Throwable $th) {
      return null;
    }
  }

  public static function zGenerateRandomString($length = 6)
  {
    $characters = 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);

    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
      $randomIndex = rand(0, $charactersLength - 1);
      $randomString .= $characters[$randomIndex];
    }

    return $randomString;
  }


  public static function zGenerateAndEncryptUniqueId()
  {
    // Generate a unique ID using Laravel's Str facade
    $uniqueId = \Illuminate\Support\Str::uuid()->toString();

    // Encrypt the unique ID using Laravel's Crypt facade
    $encryptedUniqueId = \Illuminate\Support\Facades\Crypt::encryptString($uniqueId);

    // Encode the encrypted ID using base64 to make it URL-safe
    $encodedEncryptedId = base64_encode($encryptedUniqueId);
    $urlSafeEncodedId = urlencode($encodedEncryptedId);
    return [$urlSafeEncodedId, $uniqueId];
  }

  public static function zDecryptUniqueId($uniqueId)
  {
    $urlSafeDecodedId = urldecode($uniqueId);

    // Decode the uniqueId using base64 to make it URL-safe
    $decodedEncryptedId = base64_decode($urlSafeDecodedId);

    // Decrypt the unique ID using Laravel's Crypt facade
    $decryptedUniqueId = \Illuminate\Support\Facades\Crypt::DecryptString($decodedEncryptedId);

    return $decryptedUniqueId;
  }

  public static function generateUniqueNumericOTP($length = 6)
  {
    $otp = '';
    $characters = '0123456789'; // Only numeric characters

    // Calculate the maximum index for the character set
    $maxIndex = strlen($characters) - 1;

    // Generate the OTP
    for ($i = 0; $i < $length; $i++) {
      // Use modulo to ensure the character is within the numeric character set
      $otp .= $characters[mt_rand(0, $maxIndex)];
    }

    return $otp;
  }
}
