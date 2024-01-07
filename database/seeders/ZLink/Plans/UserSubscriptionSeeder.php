<?php

namespace Database\Seeders\ZLink\Plans;

use App\Models\Default\User;
use App\Models\ZLink\Plans\Plan;
use App\Models\ZLink\Plans\UserSubscription;
use App\Zaions\Enums\PlansEnum;
use App\Zaions\Enums\SubscriptionTimeLine;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $ahsanUser = User::where('email', env('ADMIN_EMAIL'))->first();
        $superAdminUser = User::where('email', 'superAdmin@zaions.com')->first();
        $adminUser = User::where('email', 'admin@zaions.com')->first();
        $simpleUser = User::where('email', 'user@zaions.com')->first();
        $wsUser1 = User::where('email', 'test1@zaions.com')->first();
        $wsUser2 = User::where('email', 'test2@zaions.com')->first();
        $wsUser3 = User::where('email', 'test3@zaions.com')->first();
        $wsUser4 = User::where('email', 'test4@zaions.com')->first();
        $wsUser5 = User::where('email', 'test5@zaions.com')->first();

        $freePlan = Plan::where('name', PlansEnum::free->value)->first();

        $usersSubscriptionTest  = [
            [
                'uniqueId' => uniqid(),
                'userId' => $ahsanUser->id,
                'planId' => $freePlan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => SubscriptionTimeLine::monthly->value,
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $superAdminUser->id,
                'planId' => $freePlan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => SubscriptionTimeLine::monthly->value,
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $adminUser->id,
                'planId' => $freePlan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => SubscriptionTimeLine::monthly->value,
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $simpleUser->id,
                'planId' => $freePlan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => SubscriptionTimeLine::monthly->value,
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $wsUser1->id,
                'planId' => $freePlan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => SubscriptionTimeLine::monthly->value,
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $wsUser2->id,
                'planId' => $freePlan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => SubscriptionTimeLine::monthly->value,
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $wsUser3->id,
                'planId' => $freePlan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => SubscriptionTimeLine::monthly->value,
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $wsUser4->id,
                'planId' => $freePlan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => SubscriptionTimeLine::monthly->value,
            ],
            [
                'uniqueId' => uniqid(),
                'userId' => $wsUser5->id,
                'planId' => $freePlan->id,
                'startedAt' => Carbon::now(),
                'endedAt' => Carbon::now(),
                'amount' => 0,
                'duration' => SubscriptionTimeLine::monthly->value,
            ],
        ];

        foreach ($usersSubscriptionTest as $subscription) {
            UserSubscription::create($subscription);
        }
    }
}
