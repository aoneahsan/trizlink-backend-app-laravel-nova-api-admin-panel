<?php

namespace App\Http\Controllers\Zaions\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\User\UserDataResource;
use App\Jobs\Zaions\Mail\SendMailJob;
use App\Mail\OTPMail;
use App\Models\Default\User;
use App\Models\Default\UserEmail;
use App\Models\Default\WSTeamMember;
use App\Zaions\Enums\EmailStatusEnum;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Enums\RoleTypesEnum;
use App\Zaions\Enums\SignUpTypeEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Laravel\Fortify\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function listUsers(Request $request)
    {
        $items = User::where('email', 'ahsan@zaions.com')->with('actions', 'nova_notifications')->get();
        return ZHelpers::sendBackRequestCompletedResponse([
            // 'items' => UserDataResource::collection($items),
            'items' => $items,
        ]);
    }

    public function index(Request $request)
    {
        try {
            $item = User::where('id', $request->user()->id)->first();
            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserDataResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function updateAccountInfo(Request $request)
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:250',
                'firstname' => 'nullable|string|max:250',
                'username' => 'nullable|string|max:250',
                'lastname' => 'nullable|string|max:250',
                'phoneNumber' => 'nullable|string|max:250',
                'description' => 'nullable|string|max:1000',
                'website' => 'nullable|string|max:250',
                'language' => 'nullable|string|max:250',
                'country' => 'nullable|string|max:250',
                'address' => 'nullable|string|max:250',
                'city' => 'nullable|string|max:250',
                'profileImage' => 'nullable|json', // use to store user profile detail json, for example json containing filePath, fileUrl, etc.
                'avatar' => 'nullable|string|max:250' // use to store one fileUrl so where we need just url we will get from here.
            ]);
            $user = User::where('id', $request->user()->id)->first();
            if ($user) {
                $user->forceFill([
                    'name' => $request->has('name') ? $request->name : $user->name,
                    'firstname' => $request->has('firstname') ? $request->firstname : $user->firstname,
                    'username' => $request->has('username') ? $request->username : $user->username,
                    'lastname' => $request->has('lastname') ? $request->lastname : $user->lastname,
                    'phoneNumber' => $request->has('phoneNumber') ? $request->phoneNumber : $user->phoneNumber,
                    'description' => $request->has('description') ? $request->description : $user->description,
                    'website' => $request->has('website') ? $request->website : $user->website,
                    'language' => $request->has('language') ? $request->language : $user->language,
                    'country' => $request->has('country') ? $request->country : $user->country,
                    'address' => $request->has('address') ? $request->address : $user->address,
                    'city' => $request->has('city') ? $request->city : $user->city,
                    'profileImage' => $request->has('profileImage') ? (is_string($request->profileImage) ? json_decode($request->profileImage) : $request->profileImage) : $user->profileImage,
                    'avatar' => $request->has('avatar') ? $request->avatar : $user->avatar
                ])->save();
                $updatedUserInfo = User::where('id', $request->user()->id)->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserDataResource($updatedUserInfo)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'status' => 200,
                    'data' => [],
                    'errors' => [
                        'user' => 'Invalid Request, No User found.'
                    ],
                    'message' => 'No User Found.'
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'accountDeleteReason' => 'required|string|max:250'
            ]);

            $user = User::where('id', $request->user()->id)->first();
            if ($user) {
                $user->update([
                    'account_delete_reason' => $request->accountDeleteReason ? $request->accountDeleteReason : ''
                ]);

                // $user->delete();
                $user->forceDelete();
                return response()->json([
                    'success' => true,
                    'status' => 200,
                    'data' => [],
                    'errors' => [],
                    'message' => 'Request Completed.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'status' => 200,
                    'data' => [],
                    'errors' => [
                        'user' => $user
                    ],
                    'message' => 'No User Found.'
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function checkIfUsernameIsAvailable(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:250'
        ]);
        try {
            $userFound = User::where('id', '!=', $request->user()->id)->where('username', $request->username)->first();
            if ($userFound) {
                return response()->json([
                    'errors' => [
                        'username' => ['username already in use.']
                    ],
                    'data' => [],
                    'success' => false,
                    'status' => 400,
                    'message' => 'username already in use.'
                ]);
            } else {
                return response()->json([
                    'errors' => [],
                    'data' => [
                        'username' => 'username is available.'
                    ],
                    'success' => true,
                    'status' => 200,
                    'message' => 'username is available.'
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:250'
        ]);
        try {
            $userFound = User::where('id', '!=', $request->user()->id)->where('username', $request->username)->first();
            if ($userFound) {
                return response()->json([
                    'errors' => [
                        'username' => ['username already in use.']
                    ],
                    'data' => [],
                    'success' => false,
                    'status' => 400,
                    'message' => 'username already in use.'
                ]);
            } else {
                $result = User::where('id', $request->user()->id)->update([
                    'username' => $request->username
                ]);

                if ($result) {
                    return response()->json([
                        'errors' => [],
                        'data' => [
                            'username' => 'username updated successfully.'
                        ],
                        'success' => true,
                        'status' => 200,
                        'message' => 'username updated successfully.'
                    ]);
                } else {
                    return response()->json([
                        'errors' => [
                            'username' => 'username update request failed.'

                        ],
                        'data' => [],
                        'success' => false,
                        'status' => 500,
                        'message' => 'username update request failed.'
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function getUserPermissions(Request $request)
    {
        try {
            $user = $request->user();

            $result = $user->roles()->first();

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'isSuccess' => true,
                        'result' => [
                            'role' => $result->name,
                            'permissions' =>  $result->permissions()->pluck('name')
                        ],
                    ]
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => [
                        'isSuccess' => false,
                        'result' => $result
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function getWSPermissions(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                $WSRoles = Role::where('roleType', RoleTypesEnum::inAppWSRole->name)->get();

                if ($WSRoles) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => ['WSRoles' => $WSRoles]
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function generateOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'inviteToken'
                => 'required|string',
            ]);

            $token = ZHelpers::zDecryptUniqueId($request->inviteToken);

            if ($token) {
                $memberInvitation = WSTeamMember::where('wilToken', $token)->first();

                if ($memberInvitation) {
                    $memberEmail = $memberInvitation->email;

                    if ($memberEmail === $request->email) {
                        $user = User::where('email', $request->email)->first();
                        if ($user) {
                            $otp = ZHelpers::generateUniqueNumericOTP();
                            $otpValidTime =  Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();
                            $user->update([
                                'OTPCode' => $otp,
                                'OTPCodeValidTill' => $otpValidTime
                            ]);

                            $user = User::where('email', $request->email)->first();

                            if ($user->OTPCode) {
                                // Send the invitation mail to the memberUser.
                                Mail::send(new OTPMail($user, $user->OTPCode, 'Member Invitation Mail'));


                                return ZHelpers::sendBackRequestCompletedResponse([
                                    'item' => [
                                        'success' => true
                                    ],
                                ]);
                            }
                        } else {
                            return ZHelpers::sendBackNotFoundResponse([
                                'item' => ['User with this email not found.']
                            ]);
                        }
                    } else {
                        return ZHelpers::sendBackBadRequestResponse([
                            'item' => ['email does not match.']
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackBadRequestResponse([
                        'item' => ['invalid invitation!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackBadRequestResponse([
                    'token' => ['invalid token!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    // function confirmOtp(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|string',
    //             'otp' => 'required|string|max:6',
    //             'inviteToken' => 'required|string',
    //         ]);


    //         $token = ZHelpers::zDecryptUniqueId($request->inviteToken);

    //         if ($token) {

    //             $memberInvitation = WSTeamMember::where('wilToken', $token)->first();

    //             if ($memberInvitation) {

    //                 $memberEmail = $memberInvitation->email;

    //                 if ($memberEmail === $request->email) {
    //                     $user = User::where('email', $request->email)->first();

    //                     if ($user) {
    //                         $currentTime = Carbon::now();
    //                         if ($user->OTPCodeValidTill >= $currentTime) {
    //                             if ($user->OTPCode === $request->otp) {
    //                                 return ZHelpers::sendBackRequestCompletedResponse([
    //                                     'item' => [
    //                                         'success' => true
    //                                     ],
    //                                 ]);
    //                             } else {
    //                                 return ZHelpers::sendBackBadRequestResponse([
    //                                     'item' => ['Incorrect OTP.']
    //                                 ]);
    //                             }
    //                         } else {
    //                             return ZHelpers::sendBackBadRequestResponse([
    //                                 'item' => ['Invalid OTP, please resend otp.']
    //                             ]);
    //                         }
    //                     } else {
    //                         return ZHelpers::sendBackNotFoundResponse([
    //                             'item' => ['User with this email not found.']
    //                         ]);
    //                     }
    //                 } else {
    //                     return ZHelpers::sendBackBadRequestResponse([
    //                         'item' => ['email does not match.']
    //                     ]);
    //                 }
    //             } else {
    //                 return ZHelpers::sendBackBadRequestResponse([
    //                     'item' => ['invalid invitation!']
    //                 ]);
    //             }
    //         } else {
    //             return ZHelpers::sendBackBadRequestResponse([
    //                 'token' => ['invalid token!']
    //             ]);
    //         }
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         return ZHelpers::sendBackServerErrorResponse($th);
    //     }
    // }

    function setPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'username' => 'required|string',
                'password' => ['required', 'string', new Password, 'confirmed'],
                'inviteToken' => 'required|string',
            ]);
            $token = ZHelpers::zDecryptUniqueId($request->inviteToken);

            if ($token) {

                $memberInvitation = WSTeamMember::where('wilToken', $token)->first();

                if ($memberInvitation) {

                    $memberEmail = $memberInvitation->email;

                    if ($memberEmail === $request->email) {
                        $user = User::where('email', $request->email)->first();

                        if ($user) {
                            $user->update([
                                'password' => Hash::make($request->password),
                                'signUpType' => SignUpTypeEnum::normal->value,
                                'username' => $request->username,
                            ]);

                            $user = User::where('email', $request->email)->first();

                            $token = $user->createToken('auth');

                            return ZHelpers::sendBackRequestCompletedResponse([
                                'item' => [
                                    'user' => new UserDataResource($user),
                                    'token' => $token
                                ]
                            ]);
                        } else {
                            return ZHelpers::sendBackNotFoundResponse([
                                'item' => ['User with this email not found.']
                            ]);
                        }
                    } else {
                        return ZHelpers::sendBackBadRequestResponse([
                            'item' => ['email does not match.']
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackBadRequestResponse([
                        'item' => ['invalid invitation!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackBadRequestResponse([
                    'token' => ['invalid token!']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    function sendSignUpOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique(User::class),
                ],
            ]);
            //code...

            $otp = ZHelpers::generateUniqueNumericOTP();
            $otpValidTime =  Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();

            $user = User::create([
                'uniqueId' => uniqid(),
                'email' => $request->email,
                'OTPCode' => $otp,
                'signUpType' => SignUpTypeEnum::normal->value,
                'OTPCodeValidTill' => $otpValidTime
            ]);

            $userRole = Role::where('name', RolesEnum::user->name)->get();

            $user->assignRole($userRole);

            $user = User::where('email', $request->email)->first();

            if ($user->OTPCode) {
                // adding a default email entry from user in userEmail.
                UserEmail::create([
                    'uniqueId' => uniqid(),
                    'userId' => $user->id,
                    'email' => $user->email,
                    'status' => EmailStatusEnum::Verified->value,
                    'isDefault' => true,
                    'isPrimary' => true,
                ]);
                // Send the invitation mail to the memberUser.
                // SendMailJob::dispatch($user);
                Mail::send(new OTPMail($user, $user->OTPCode, 'Sign up confirm OTP'));

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'success' => true,
                        'OTPCodeValidTill' => $otpValidTime
                    ],
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    function resendOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => [
                    'required',
                    'string',
                ],
            ]);

            $user = User::where('email', $request->email)->first();

            if ($user) {
                $otp = ZHelpers::generateUniqueNumericOTP();
                $otpValidTime =  Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();

                $user->update([
                    'OTPCode' => $otp,
                    'OTPCodeValidTill' => $otpValidTime
                ]);

                $user = User::where('email', $request->email)->first();

                if ($user->OTPCode) {
                    // Send the invitation mail to the memberUser.
                    // SendMailJob::dispatch($user);
                    // Sign up confirm OTP => Confirm OTP
                    Mail::send(new OTPMail($user, $user->OTPCode, 'Confirm OTP'));

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => [
                            'success' => true,
                            'OTPCodeValidTill' => $otpValidTime
                        ],
                    ]);
                }
            } else {
                ZHelpers::sendBackNotFoundResponse([
                    'item' => ['No user fount which this email.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    function sendForgetPasswordOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                ],
            ]);
            //code...

            $user = User::where('email', $request->email)->first();

            if ($user) {
                $otp = ZHelpers::generateUniqueNumericOTP();
                $otpValidTime =  Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();

                $user = $user->update([
                    'OTPCode' => $otp,
                    'OTPCodeValidTill' => $otpValidTime
                ]);

                $user = User::where('email', $request->email)->first();

                if ($user->OTPCode) {
                    // Send the invitation mail to the memberUser.
                    // SendMailJob::dispatch($user);
                    Mail::send(new OTPMail($user, $user->OTPCode, 'Confirm OTP'));

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => [
                            'success' => true,
                            'OTPCodeValidTill' =>  $otpValidTime
                        ],
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['User with this email not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    function confirmSignUpOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'otp' => 'required|string|max:6',
            ]);


            $user = User::where('email', $request->email)->first();

            if ($user) {
                $currentTime = Carbon::now();
                if ($user->OTPCodeValidTill >= $currentTime) {
                    if ($user->OTPCode && $user->OTPCode === $request->otp) {

                        $user->update([
                            'OTPCode' => null,
                            'OTPCodeValidTill' => null
                        ]);

                        $userEmail = UserEmail::where('email', $user->email)->first();

                        $userEmail->update([
                            'verifiedAt' => $currentTime
                        ]);

                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => [
                                'success' => true
                            ],
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
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['User with this email not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    function setUsernamePassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'username' => 'nullable|string',
                'password' => ['required', 'string', new Password, 'confirmed'],
            ]);

            $user = User::where('email', $request->email)->first();

            if ($user) {
                $user->update([
                    'password' => Hash::make($request->password),
                    'username' => $request->username ? $request->username : $user->username,
                ]);

                $user = User::where('email', $request->email)->first();

                $token = $user->createToken('auth');

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'user' => new UserDataResource($user),
                        'token' => $token
                    ]
                ]);
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['User with this email not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    // If password change request come first validate the current password and send an OTP(one-time-password) to the user primary email from verification.
    function validateCurrentPassword(Request $request)
    {
        try {
            $currentUser = $request->user();

            $request->validate([
                'password' => 'required|string',
            ]);

            if ($currentUser) {
                if (Hash::check($request->password, $currentUser->password)) {
                    // If password validated the send opt in user primary email.
                    $otp = ZHelpers::generateUniqueNumericOTP();
                    $otpValidTime =  Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();

                    $currentUser->update([
                        'OTPCode' => $otp,
                        'OTPCodeValidTill' => $otpValidTime
                    ]);

                    $mailSubject = 'Password change verification OTP';
                    Mail::send(new OTPMail($currentUser, $otp, $mailSubject));

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => [
                            'success' => true,
                            'OTPCodeValidTill' => $otpValidTime
                        ]
                    ]);
                } else {
                    return ZHelpers::sendBackBadRequestResponse([
                        'password' => ['Incorrect password.']
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['User not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    function confirmValidateCurrentPasswordOtp(Request $request)
    {
        try {
            $currentUser = $request->user();

            $request->validate([
                'otp' => 'required|string'
            ]);
            if ($currentUser) {
                $currentTime = Carbon::now();
                if ($currentUser->OTPCodeValidTill >= $currentTime) {
                    if ($currentUser->OTPCode && $currentUser->OTPCode === $request->otp) {

                        $currentUser->update([
                            'OTPCode' => null,
                            'OTPCodeValidTill' => null
                        ]);

                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => [
                                'success' => true
                            ],
                        ]);
                    } else {
                        return ZHelpers::sendBackBadRequestResponse([
                            'otp' => ['Incorrect OTP.']
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackBadRequestResponse([
                        'otp' => ['Invalid OTP, please resend otp.']
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['User with this email not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    function updatePassword(Request $request)
    {
        $currentUser = $request->user();
        try {
            $request->validate([
                'newPassword' => ['required', 'string', new Password, 'confirmed'],
            ]);

            if ($currentUser) {
                $updatedUser = $currentUser->update([
                    'password' => Hash::make($request->newPassword),
                ]);

                if ($updatedUser) {
                    $user = User::where('email', $currentUser->email)->first();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => [
                            'user' => new UserDataResource($user),
                        ]
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['User with this email not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    function resendPassword(Request $request)
    {
        $currentUser = $request->user();
        try {
            if ($currentUser) {
                $otp = ZHelpers::generateUniqueNumericOTP();
                $otpValidTime =  Carbon::now()->addMinutes(config('zLinkConfig.optExpireAddTime'))->toDateTimeString();

                $updatedUser = $currentUser->update([
                    'OTPCode' => $otp,
                    'OTPCodeValidTill' => $otpValidTime
                ]);

                if ($updatedUser) {

                    $mailSubject = 'Password change verification OTP';
                    Mail::send(new OTPMail($currentUser, $otp, $mailSubject));

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => [
                            'success' => true,
                            'OTPCodeValidTill' => $otpValidTime
                        ]
                    ]);
                }
            } else {
                return ZHelpers::sendBackNotFoundResponse([
                    'item' => ['User with this email not found.']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
