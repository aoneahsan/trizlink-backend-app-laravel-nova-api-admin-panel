<?php

namespace Database\Seeders\ZLink\Plans;

use App\Models\ZLink\Plans\Plan;
use App\Models\ZLink\Plans\PlanLimit;
use App\Zaions\Enums\PlanFeatures;
use App\Zaions\Enums\PlansEnum;
use App\Zaions\Enums\PlansLimitFeaturesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 
        $freePlan = Plan::where('name', PlansEnum::free->value)->first();
        $corePlan = Plan::where('name', PlansEnum::core->value)->first();
        $growthPlan = Plan::where('name', PlansEnum::growth->value)->first();
        $premiumPlan = Plan::where('name', PlansEnum::premium->value)->first();

        $planLimits = [
            // All Link Management limits
            [
                'type' => PlansLimitFeaturesEnum::linkManagement->value,
                'planId' => $freePlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinks->value,
                'displayName' => 'Short Links',
                'maxLimit' => 2,
                'timeLine' => 'monthly',
                'description' => 'Total number of URLs you can shorten on a monthly basis'
            ],
            [
                'type' => PlansLimitFeaturesEnum::linkManagement->value,
                'planId' => $corePlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinks->value,
                'displayName' => 'Short Links',
                'maxLimit' => 100,
                'timeLine' => 'monthly',
                'description' => 'Total number of URLs you can shorten on a monthly basis'
            ],
            [
                'type' => PlansLimitFeaturesEnum::linkManagement->value,
                'planId' => $growthPlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinks->value,
                'displayName' => 'Short Links',
                'maxLimit' => 500,
                'timeLine' => 'monthly',
                'description' => 'Total number of URLs you can shorten on a monthly basis'
            ],
            [
                'type' => PlansLimitFeaturesEnum::linkManagement->value,
                'planId' => $premiumPlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinks->value,
                'displayName' => 'Short Links',
                'maxLimit' => 3000,
                'timeLine' => 'monthly',
                'description' => 'Total number of URLs you can shorten on a monthly basis'
            ],
        ];

        foreach ($planLimits as $planLimit) {
            PlanLimit::create($planLimit);
        }
    }
}
