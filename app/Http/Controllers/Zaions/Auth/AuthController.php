<?php

namespace App\Http\Controllers\Zaions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\User\UserDataResource;
use App\Models\Default\User;
use App\Models\Default\UserEmail;
use App\Notifications\TestNotification;
use App\Notifications\UserAccount\LastLogoutNotification;
use App\Notifications\UserAccount\NewDeviceLoginNotification;
use App\Zaions\Enums\EmailStatusEnum;
use App\Zaions\Enums\EncryptKeysEnum;
use App\Zaions\Enums\NotificationTypeEnum;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Laravel\Fortify\Rules\Password;
use Laravel\Socialite\Facades\Socialite;
use App\Zaions\Enums\SignUpTypeEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique(User::class),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => ['required', 'string', new Password, 'confirmed'],
        ]);
        $user = User::create([
            'uniqueId' => uniqid(),
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        $userRole = Role::where('name', RolesEnum::user->name)->get();

        $user->assignRole($userRole);

        $token = $user->createToken('auth');

        // adding a default email entry from user in userEmail.
        UserEmail::create([
            'uniqueId' => uniqid(),
            'userId' => $user->id,
            'email' => $user->email,
            'status' => EmailStatusEnum::Verified->value,
            'isDefault' => true,
            'isPrimary' => true,
        ]);

        return response()->json([
            'success' => true,
            'errors' => [],
            'data' => [
                'user' => new UserDataResource($user),
                'token' => $token
            ],
            'message' => 'Request Completed Successfully!',
            'status' => 201
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->where('signUpType', SignUpTypeEnum::normal->value)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth');

                // $notificationData = [
                //     'userId' => $user->id,
                //     'message' => 'login ' . $token->accessToken->created_at->diffForHumans(),
                // ];

                // $user->notify(new NewDeviceLoginNotification($notificationData));

                return response()->json([
                    'success' => true,
                    'errors' => [],
                    'data' => [
                        'user' => new UserDataResource($user),
                        'token' => $token
                    ],
                    'message' => 'Request Completed Successfully!',
                    'status' => 201
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'password' => ['Invalid Password']
                    ],
                    'data' => [],
                    'message' => 'Request Failed.',
                    'status' => 400
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'errors' => [
                    'email' => ['No User found with this email.']
                ],
                'data' => [],
                'message' => 'Request Failed.',
                'status' => 400
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();
        if ($user) {
            $user->tokens()->delete();

            // $notificationData = [
            //     'userId' => $user->id,
            //     'message' => 'logout',
            // ];

            // $user->notify(new LastLogoutNotification($notificationData));

            return response()->json([
                'success' => true,
                'errors' => [],
                'data' => [
                    'isSuccess' => true
                ],
                'message' => 'Request Completed Successfully!',
                'status' => 200
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'errors' => [],
                'data' => [
                    'isSuccess' => false
                ],
                'message' => 'Request failed.',
                'status' => 400
            ], 400);
        }
    }

    public function verifyAuthState(Request $request)
    {
        return response()->json(['data' => true]);
    }

    public function updateUserIsActiveStatus(Request $request)
    {
        $currentUser = $request->user();
        $currentUser->lastSeen = Carbon::now()->addMinutes(3);
        // lastSeenAt (when), lastLogin (), isOnline = true, false     (3,4)
        $currentUser->save();
        return response()->json(['data' => true]);
    }

    public function socialLoginGoogle(Request $request)
    {
        try {
            $request->validate([
                EncryptKeysEnum::accessToken->value => 'required|string',
                EncryptKeysEnum::time->value => 'required|string',
            ]);

            $userAccessToken = $request->{EncryptKeysEnum::accessToken->value};

            $client = new Client([
                'verify' => false  // Path to your cacert.pem file
            ]);
            // return response()->json(['data' => '$response', 'token' => $userAccessToken]);

            // $response = $client->get('https://www.googleapis.com/oauth2/v1/userinfo', [
            //     'headers' => [
            //         'Authorization' => 'Bearer ' . $userAccessToken
            //     ],
            // ]);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
                ->withToken($userAccessToken)
                ->withoutVerifying()
                ->get('https://www.googleapis.com/oauth2/v1/userinfo');

            return response()->json(['data' => $response->json(), 'token' => $userAccessToken]);

            // if ($response->successful()) {
            //     $userInfo = $response->json();

            //     if ($userInfo) {
            //         $userExist = User::where('email', $userInfo['email'])->where('signUpType', SignUpTypeEnum::normal->value)->first();

            //         if ($userExist) {
            //             $token = $userExist->createToken('auth');

            //             return response()->json([
            //                 'success' => true,
            //                 'errors' => [],
            //                 'data' => [
            //                     'user' => new UserDataResource($userExist),
            //                     'token' => $token
            //                 ],
            //                 'message' => 'Request Completed Successfully!',
            //                 'status' => 201
            //             ], 201);
            //         } else {
            //             return response()->json([
            //                 'success' => false,
            //                 'errors' => [
            //                     'email' => ['No User found with this email.'],
            //                     'message' => ['Try signing up or use a different email.'],
            //                 ],
            //                 'data' => [],
            //                 'message' => 'Request Failed.',
            //                 'status' => 400
            //             ], 400);
            //         }
            //     }
            // }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
