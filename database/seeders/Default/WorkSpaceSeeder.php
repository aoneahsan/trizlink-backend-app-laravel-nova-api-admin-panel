<?php

namespace Database\Seeders\Default;

use App\Models\Default\Notification\WSNotificationSetting;
use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\Plans\Plan;
use App\Models\ZLink\Plans\WSSubscription;
use App\Zaions\Enums\PlansEnum;
use App\Zaions\Enums\WSEnum;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $ahsanUser = User::where('email', env('ADMIN_EMAIL'))->first();
        $freePlan = Plan::where('name', PlansEnum::free->value)->first();
        $simpleUser = User::where('email', 'user@zaions.com')->first();

        $ahsanWS = WorkSpace::create([
            'uniqueId' => uniqid(),
            'userId' => $ahsanUser->id,
            'title' => 'Ahsan share workspace',
            'timezone' => 'Asia/Karachi',
        ]);
        WSNotificationSetting::create([
            'uniqueId' => uniqid(),
            'userId' => $ahsanUser->id,
            'workspaceId' => $ahsanWS->id,
            'type' => WSEnum::personalWorkspace->value,
        ]);
        WSSubscription::create([
            'uniqueId' => uniqid(),
            'userId' => $ahsanUser->id,
            'planId' => $freePlan->id,
            'startedAt' => Carbon::now(),
            'endedAt' => Carbon::now()->addDays(30),
            'workspaceId' => $ahsanWS->id,
            'type' => WSEnum::personalWorkspace->value,
        ]);

        $simpleUserWs = WorkSpace::create([
            'uniqueId' => uniqid(),
            'userId' => $simpleUser->id,
            'title' => 'User share workspace',
            'timezone' => 'Asia/Karachi',
        ]);

        WSNotificationSetting::create([
            'uniqueId' => uniqid(),
            'userId' => $simpleUser->id,
            'workspaceId' => $simpleUserWs->id,
            'type' => WSEnum::personalWorkspace->value,
        ]);

        WSSubscription::create([
            'uniqueId' => uniqid(),
            'userId' => $simpleUser->id,
            'planId' => $freePlan->id,
            'startedAt' => Carbon::now(),
            'endedAt' => Carbon::now()->addDays(30),
            'workspaceId' => $simpleUserWs->id,
            'type' => WSEnum::personalWorkspace->value,
        ]);
    }
}
