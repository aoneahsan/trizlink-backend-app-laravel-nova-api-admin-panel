<?php

namespace App\Zaions\Helpers;

use App\Http\Resources\Zaions\ZLink\Plans\PlanLimitResource;
use App\Models\Default\User;
use App\Models\ZLink\Plans\PlanLimit;
use App\Zaions\Enums\PlanFeatures;
use App\Zaions\Enums\SubscriptionTimeLine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ZAccountHelpers
{
    /** 
     * Get current logged in user all/single services/service limits.
     * $name: $name will be PlanFeatures enum e.g, shortLink, if $name pass then it will fetch that service data only else it will fetch all services data.
     */
    static public function currentUserServicesLimits(User $user, $name = null, $currentServiceLimit = null) {
        try {
            // If no specific service name, return all services
            $services = $user->subscription->planLimits;

            if ($name) {
                // If a specific service name is provided, filter by that service
                $services = $services->where('name', $name)->first();

                if($currentServiceLimit !== null){
                    $subscriptionStartedTime = Carbon::parse($user->subscription->startedAt);
                    $servicesTimeLine = $services->timeLine;
                    $endDate = null;

                    if($servicesTimeLine === SubscriptionTimeLine::monthly->value){
                        $endDate = Carbon::parse($subscriptionStartedTime)->addDays(30);
                    }else if($servicesTimeLine === SubscriptionTimeLine::yearly->value){
                        $endDate = Carbon::parse($subscriptionStartedTime)->addYear(1);
                    }

                    if(Carbon::now()->isAfter($subscriptionStartedTime) && Carbon::now()->isBefore($endDate) && $currentServiceLimit < $services->maxLimit){
                        return true;
                    } else {
                        return false;
                    }
                }
            }

            return $services;
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}