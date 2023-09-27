<?php

namespace App\Http\Controllers\Zaions\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\User\UserEmailResource;
use App\Mail\OTPMail;
use App\Models\Default\UserEmail;
use App\Zaions\Enums\EmailStatusEnum;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class UserEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_emails->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $itemsCount = UserEmail::where('userId', $currentUser->id)->count();
            $items = UserEmail::where('userId', $currentUser->id)->with('user')->get();

            return ZHelpers::sendBackRequestCompletedResponse([
                'items' => UserEmailResource::collection($items),
                'itemsCount' => $itemsCount
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function addEmail(Request $request)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::add_email->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


            $request->validate([
                'email' => 'required|string',
            ]);

            $otp = ZHelpers::generateUniqueNumericOTP();
            $otpValidTime =  Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();

            $result = UserEmail::create([
                'uniqueId' => uniqid(),

                'userId' => $currentUser->id,
                'email' => $request->has('email') ? $request->email : null,
                'optExpireTime' => $otpValidTime,
                'optCode' => $otp,
                'status' => EmailStatusEnum::Unverified->value,
                'isDefault' =>  false,
                'isPrimary' =>  false,
                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : true,
                'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null,
            ]);

            if ($result) {

                // Send the email verification opt
                $mailSubject = 'Email verification OTP';
                Mail::send(new OTPMail($currentUser, $result->optCode, $mailSubject));

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserEmailResource($result)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function confirmOtp(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::email_opt_check->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'email' => 'required|string',
                'otp' => 'required|string|max:6',
            ]);

            $item = UserEmail::where('uniqueId', $itemId)->where('email', $request->email)->first();
            if ($item) {
                $currentTime = Carbon::now();
                if ($item->optExpireTime >= $currentTime) {
                    if ($item->optCode === $request->otp) {
                        $item->update([
                            'optExpireTime' => null,
                            'optCode' => null,
                            'status' => EmailStatusEnum::Verified->value,
                            'verifiedAt' => $currentTime
                        ]);
                        $item = UserEmail::where('uniqueId', $itemId)->where('email', $request->email)->first();

                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new UserEmailResource($item),
                        ]);
                    } else {
                        return ZHelpers::sendBackBadRequestResponse([
                            'item' => ['Incorrect OTP.']
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackBadRequestResponse([
                        'item' => ['Invalid OTP, please resend otp.']
                    ]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Email not found!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function resendOtp(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::email_opt_check->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'email' => 'required|string',
            ]);

            $item = UserEmail::where('uniqueId', $itemId)->where('email', $request->email)->first();
            if ($item) {
                $otp = ZHelpers::generateUniqueNumericOTP();
                $otpValidTime =  Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();

                $item->update([
                    'optExpireTime' => $otpValidTime,
                    'optCode' => $otp,
                ]);

                $item = UserEmail::where('uniqueId', $itemId)->where('email', $request->email)->first();

                // Resend the email verification opt
                $mailSubject = 'Email verification OTP';
                Mail::send(new OTPMail($currentUser, $item->optCode, $mailSubject));

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserEmailResource($item)
                ]);


                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserEmailResource($item),
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Email not found!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function deleteEmail(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_email->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


            $item = UserEmail::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                if (!$item->isPrimary) {
                    $item->forceDelete();
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => ['success' => true]
                    ]);
                } else {
                    return ZHelpers::sendBackBadRequestResponse([
                        'item' => ["You are not allowed to delete primary email!"]
                    ]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Email not found!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function makeEmailPrimary(Request $request, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_email->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'email' => 'required|string'
            ]);

            $item = UserEmail::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

            if ($item) {
                if (!$item->isPrimary) {

                    $currentPrimaryEmail = UserEmail::where('isPrimary', true)->where('userId', $currentUser->id)->first();

                    $currentPrimaryEmail->update([
                        'isPrimary' => false
                    ]);

                    $item->update([
                        'isPrimary' => true
                    ]);



                    $item = UserEmail::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

                    $oldPrimaryEmail = UserEmail::where('uniqueId', $currentPrimaryEmail->uniqueId)->where('userId', $currentUser->id)->first();

                    $currentUser->update([
                        'email' => $item->email,
                    ]);

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => [
                            'primaryEmail' => new UserEmailResource($item),
                            'oldPrimaryEmail' => new UserEmailResource($oldPrimaryEmail)
                        ],
                    ]);
                } else {
                    return ZHelpers::sendBackBadRequestResponse([
                        'item' => ["This email is already primary email!"]
                    ]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Email not found!']
                ]);
            }
        } catch (\Throwable $th) {
            ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
