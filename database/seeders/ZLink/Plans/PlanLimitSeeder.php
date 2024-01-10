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
            #region --- All Workspace limits ---
            #region Free plan
            [
                'type' => PlansLimitFeaturesEnum::workspace->value,
                'planId' => $freePlan->id,
                'version' => '',
                'name' => PlanFeatures::workspace->value,
                'displayName' => 'Workspaces',
                'maxLimit' => 2,
                'timeLine' => 'monthly',
                'description' => 'Total number of workspaces you can create on a monthly basis'
            ],
            #endregion

            #region Core plan
            [
                'type' => PlansLimitFeaturesEnum::workspace->value,
                'planId' => $corePlan->id,
                'version' => '',
                'name' => PlanFeatures::workspace->value,
                'displayName' => 'Workspaces',
                'maxLimit' => 4,
                'timeLine' => 'monthly',
                'description' => 'Total number of workspaces you can create on a monthly basis'
            ],
            #endregion

            #region Growth plan
            [
                'type' => PlansLimitFeaturesEnum::workspace->value,
                'planId' => $growthPlan->id,
                'version' => '',
                'name' => PlanFeatures::workspace->value,
                'displayName' => 'Workspaces',
                'maxLimit' => 6,
                'timeLine' => 'monthly',
                'description' => 'Total number of workspaces you can create on a monthly basis'
            ],
            #endregion

            #region Premium plan
            [
                'type' => PlansLimitFeaturesEnum::workspace->value,
                'planId' => $premiumPlan->id,
                'version' => '',
                'name' => PlanFeatures::workspace->value,
                'displayName' => 'Workspaces',
                'maxLimit' => 8,
                'timeLine' => 'monthly',
                'description' => 'Total number of workspaces you can create on a monthly basis'
            ],
            #endregion
            #endregion

            #region --- All Link Management limits ---
            #region Free plan
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
                'planId' => $freePlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinksFolder->value,
                'displayName' => 'Short Links Folders',
                'maxLimit' => 2,
                'timeLine' => 'monthly',
                'description' => 'Total number of folder you can create on a monthly basis'
            ],
            #endregion

            #region Core plan
            [
                'type' => PlansLimitFeaturesEnum::linkManagement->value,
                'planId' => $corePlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinks->value,
                'displayName' => 'Short Links',
                'maxLimit' => 4,
                'timeLine' => 'monthly',
                'description' => 'Total number of URLs you can shorten on a monthly basis'
            ],
            [
                'type' => PlansLimitFeaturesEnum::linkManagement->value,
                'planId' => $corePlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinksFolder->value,
                'displayName' => 'Short Links Folders',
                'maxLimit' => 4,
                'timeLine' => 'monthly',
                'description' => 'Total number of folder you can create on a monthly basis'
            ],
            #endregion

            #region Growth plan
            [
                'type' => PlansLimitFeaturesEnum::linkManagement->value,
                'planId' => $growthPlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinks->value,
                'displayName' => 'Short Links',
                'maxLimit' => 6,
                'timeLine' => 'monthly',
                'description' => 'Total number of URLs you can shorten on a monthly basis'
            ],
            [
                'type' => PlansLimitFeaturesEnum::linkManagement->value,
                'planId' => $growthPlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinksFolder->value,
                'displayName' => 'Short Links Folders',
                'maxLimit' => 6,
                'timeLine' => 'monthly',
                'description' => 'Total number of folder you can create on a monthly basis'
            ],
            #endregion

            #region Premium plan
            [
                'type' => PlansLimitFeaturesEnum::linkManagement->value,
                'planId' => $premiumPlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinks->value,
                'displayName' => 'Short Links',
                'maxLimit' => 8,
                'timeLine' => 'monthly',
                'description' => 'Total number of URLs you can shorten on a monthly basis'
            ],
            [
                'type' => PlansLimitFeaturesEnum::linkManagement->value,
                'planId' => $premiumPlan->id,
                'version' => '',
                'name' => PlanFeatures::shortLinksFolder->value,
                'displayName' => 'Short Links Folders',
                'maxLimit' => 8,
                'timeLine' => 'monthly',
                'description' => 'Total number of folder you can create on a monthly basis'
            ],
            #endregion
            #endregion

            #region --- All Link-in-bio ---
            #region Free plan
            [
                'type' => PlansLimitFeaturesEnum::linkInBio->value,
                'planId' => $freePlan->id,
                'version' => '',
                'name' => PlanFeatures::linkInBio->value,
                'displayName' => 'Link-in-bio',
                'maxLimit' => 2,
                'timeLine' => 'monthly',
                'description' => 'Total number of link-in-bio\'s you can create in a workspace on a monthly basis'
            ],
            [
                'type' => PlansLimitFeaturesEnum::linkInBio->value,
                'planId' => $freePlan->id,
                'version' => '',
                'name' => PlanFeatures::linksInBioFolder->value,
                'displayName' => 'Links-in-bio\'s Folders',
                'maxLimit' => 2,
                'timeLine' => 'monthly',
                'description' => 'Total number of folder you can create on a monthly basis'
            ],
            #endregion

            #region Core plan
            [
                'type' => PlansLimitFeaturesEnum::linkInBio->value,
                'planId' => $corePlan->id,
                'version' => '',
                'name' => PlanFeatures::linkInBio->value,
                'displayName' => 'Link-in-bio',
                'maxLimit' => 4,
                'timeLine' => 'monthly',
                'description' => 'Total number of link-in-bio\'s you can create in a workspace on a monthly basis'
            ],
            [
                'type' => PlansLimitFeaturesEnum::linkInBio->value,
                'planId' => $corePlan->id,
                'version' => '',
                'name' => PlanFeatures::linksInBioFolder->value,
                'displayName' => 'Links-in-bio\'s Folders',
                'maxLimit' => 4,
                'timeLine' => 'monthly',
                'description' => 'Total number of folder you can create on a monthly basis'
            ],
            #endregion

            #region Growth plan
            [
                'type' => PlansLimitFeaturesEnum::linkInBio->value,
                'planId' => $growthPlan->id,
                'version' => '',
                'name' => PlanFeatures::linkInBio->value,
                'displayName' => 'Link-in-bio',
                'maxLimit' => 6,
                'timeLine' => 'monthly',
                'description' => 'Total number of link-in-bio\'s you can create in a workspace on a monthly basis'
            ],
            [
                'type' => PlansLimitFeaturesEnum::linkInBio->value,
                'planId' => $growthPlan->id,
                'version' => '',
                'name' => PlanFeatures::linksInBioFolder->value,
                'displayName' => 'Links-in-bio\'s Folders',
                'maxLimit' => 6,
                'timeLine' => 'monthly',
                'description' => 'Total number of folder you can create on a monthly basis'
            ],
            #endregion

            #region Premium plan
            [
                'type' => PlansLimitFeaturesEnum::linkInBio->value,
                'planId' => $premiumPlan->id,
                'version' => '',
                'name' => PlanFeatures::linkInBio->value,
                'displayName' => 'Link-in-bio',
                'maxLimit' => 8,
                'timeLine' => 'monthly',
                'description' => 'Total number of link-in-bio\'s you can create in a workspace on a monthly basis'
            ],
            [
                'type' => PlansLimitFeaturesEnum::linkInBio->value,
                'planId' => $premiumPlan->id,
                'version' => '',
                'name' => PlanFeatures::linksInBioFolder->value,
                'displayName' => 'Links-in-bio\'s Folders',
                'maxLimit' => 8,
                'timeLine' => 'monthly',
                'description' => 'Total number of folder you can create on a monthly basis'
            ],
            #endregion
            #endregion

            #region --- Members ---
            #region Free Plan
            [
                'type' => PlansLimitFeaturesEnum::membersManagement->value,
                'planId' => $freePlan->id,
                'version' => '',
                'name' => PlanFeatures::members->value,
                'displayName' => 'Members',
                'maxLimit' => 8,
                'timeLine' => 'monthly',
                'description' => 'Total number of members you can add in a workspace on a monthly basis'
            ],
            #endregion

            #region Core Plan
            [
                'type' => PlansLimitFeaturesEnum::membersManagement->value,
                'planId' => $corePlan->id,
                'version' => '',
                'name' => PlanFeatures::members->value,
                'displayName' => 'Members',
                'maxLimit' => 9,
                'timeLine' => 'monthly',
                'description' => 'Total number of members you can add in a workspace on a monthly basis'
            ],
            #endregion

            #region Growth Plan
            [
                'type' => PlansLimitFeaturesEnum::membersManagement->value,
                'planId' => $growthPlan->id,
                'version' => '',
                'name' => PlanFeatures::members->value,
                'displayName' => 'Members',
                'maxLimit' => 10,
                'timeLine' => 'monthly',
                'description' => 'Total number of members you can add in a workspace on a monthly basis'
            ],
            #endregion

            #region Premium Plan
            [
                'type' => PlansLimitFeaturesEnum::membersManagement->value,
                'planId' => $premiumPlan->id,
                'version' => '',
                'name' => PlanFeatures::members->value,
                'displayName' => 'Members',
                'maxLimit' => 11,
                'timeLine' => 'monthly',
                'description' => 'Total number of members you can add in a workspace on a monthly basis'
            ],
            #endregion
            #endregion
        ];

        foreach ($planLimits as $planLimit) {
            PlanLimit::create($planLimit);
        }
    }
}
