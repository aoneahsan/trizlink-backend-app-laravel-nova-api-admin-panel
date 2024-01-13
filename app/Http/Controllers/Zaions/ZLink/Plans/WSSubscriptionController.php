<?php

namespace App\Http\Controllers\Zaions\Zlink\Plans;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Zlink\Plans\WSSubscriptionResource;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\Plans\Plan;
use App\Models\ZLink\Plans\WSSubscription;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Database\Seeders\ZLink\Plans\WSSubscriptionSeeder;
use Illuminate\Http\Request;

class WSSubscriptionController extends Controller
{
    // 
    public function workspaceSubscription(Request $request, $type, $wsUniqueId)
    {
        try {
            $currentUser = $request->user();

            // getting workspace
            $workspace = WorkSpace::where('uniqueId', $wsUniqueId)->where('userId', $currentUser->id)->first();

            $wsSubscription = WSSubscription::where('workspaceId', $workspace->id)->with('plan')->first();

            return ZHelpers::sendBackRequestCompletedResponse([
                'item' =>  new WSSubscriptionResource($wsSubscription)
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            ZHelpers::sendBackBadRequestResponse($th);
        }
    }

    //
    public function assignPlan(Request $request, $wsUniqueId)
    {
        try {
            $currentUser = $request->user();

            // getting workspace
            $workspace = WorkSpace::where('uniqueId', $wsUniqueId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $request->validate([
                'plan' => 'required|string|max:250',
                'selectedTimeLine' => 'required|string|max:250',
            ]);

            $plan = Plan::where('name', $request->plan)->first();

            if (!$plan) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No plan found!']
                ]);
            }

            $result = WSSubscription::create([
                'uniqueId' => uniqid(),
                'userId' => $currentUser->id,
                'workspaceId' => $workspace->id,
                'planId' => $plan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => $request->selectedTimeLine, 'extraAttributes' =>  $request->has('extraAttributes') ? ZHelpers::zJsonDecode($request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WSSubscriptionResource($result)
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

    public function upgradeUserSubscription(Request $request, $wsUniqueId)
    {
        try {
            $currentUser = $request->user();

            // getting workspace
            $workspace = WorkSpace::where('uniqueId', $wsUniqueId)->where('userId', $currentUser->id)->first();

            if (!$workspace) {
                return ZHelpers::sendBackNotFoundResponse([
                    "item" => ['Workspace not found!']
                ]);
            }

            $request->validate([
                'plan' => 'required|string|max:250',
                'selectedTimeLine' => 'required|string|max:250',
            ]);

            $plan = Plan::where('name', $request->plan)->first();

            if (!$plan) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No plan found!']
                ]);
            }

            $subscription = WSSubscription::where('userId', $currentUser->id)->first();

            if (!$subscription) {
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

            $subscription = WSSubscription::where('userId', $currentUser->id)->first();

            return ZHelpers::sendBackRequestCompletedResponse([
                'item' => new WSSubscriptionResource($subscription)
            ]);
        } catch (\Throwable $th) {
            ZHelpers::sendBackBadRequestResponse($th);
        }
    }
}
