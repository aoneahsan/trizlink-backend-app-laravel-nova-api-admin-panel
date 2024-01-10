<?php

namespace App\Http\Controllers\Zaions\ZLink\Plans;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\Plans\UserSubscriptionResource;
use App\Models\ZLink\Plans\Plan;
use App\Models\ZLink\Plans\UserSubscription;
use App\Zaions\Enums\PlansEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserSubscriptionController extends Controller
{
    // 
    public function userSubscription(Request $request)
    {
        try {
            $currentUser = $request->user();

            $userSubscription = UserSubscription::where('userId', $currentUser->id)->with('plan')->first();

            return ZHelpers::sendBackRequestCompletedResponse([
                'item' =>  new UserSubscriptionResource($userSubscription)
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            ZHelpers::sendBackBadRequestResponse($th);
        }
    }
    //
    public function assignPlan(Request $request, $planType)
    {
        try {
            $currentUser = $request->user();

            $request->validate([
                'plan' => 'required|string|max:250',
                'selectedTimeLine' => 'required|string|max:250',
            ]);
            
            $plan = Plan::where('name', $request->plan)->first();

            if(!$plan){
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No plan found!']
                ]);
            }

            $result = UserSubscription::create([
                'uniqueId' => uniqid(),
                'userId' => $currentUser->id,
                'planId' => $plan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => $request->selectedTimeLine,'extraAttributes' =>  $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new UserSubscriptionResource($result)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    "item" => ['something went wrong']
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            ZHelpers::sendBackBadRequestResponse($th);
        }
    }

    public function upgradeUserSubscription(Request $request) {
        try {
            $currentUser = $request->user();

            $request->validate([
                'plan' => 'required|string|max:250',
                'selectedTimeLine' => 'required|string|max:250',
            ]);

            $plan = Plan::where('name', $request->plan)->first();

            if(!$plan){
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No plan found!']
                ]);
            }

            $subscription = UserSubscription::where('userId', $currentUser->id)->first();

            if(!$subscription){
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No subscription found!']
                ]);
            }

            $subscription->update([
                'planId' => $plan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => $request->selectedTimeLine,
                'extraAttributes' =>  $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
            ]);

            $subscription = UserSubscription::where('userId', $currentUser->id)->first();

            return ZHelpers::sendBackRequestCompletedResponse([
                'item' => new UserSubscriptionResource($subscription)
            ]);

        } catch (\Throwable $th) {
            ZHelpers::sendBackBadRequestResponse($th);
        }
    }
}
