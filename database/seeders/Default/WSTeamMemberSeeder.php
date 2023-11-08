<?php


namespace Database\Seeders\Default;

use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use App\Models\Default\WSTeamMember;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Enums\WSMemberAccountStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class WSTeamMemberSeeder extends Seeder
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

        // Roles
        $wsAdministrator = Role::where('name', RolesEnum::ws_administrator->value)->first();
        $wsManager = Role::where('name', RolesEnum::ws_manager->value)->first();
        $wsContributor = Role::where('name', RolesEnum::ws_contributor->value)->first();
        $wsWriter = Role::where('name', RolesEnum::ws_writer->value)->first();
        $wsApprover = Role::where('name', RolesEnum::ws_approver->value)->first();
        $wsCommenter = Role::where('name', RolesEnum::ws_commenter->value)->first();
        $wsGuest = Role::where('name', RolesEnum::ws_guest->value)->first();

        $ahsanSWS = WorkSpace::where('userId', $ahsanUser->id)->first();

        WSTeamMember::create([
            'uniqueId' => uniqid(),
            'inviterId' => $ahsanUser->id,
            'workspaceId' => $ahsanSWS->id,
            'memberRoleId' => $wsAdministrator->id,
            'memberId' => $superAdminUser->id,
            'email' => $superAdminUser->email,
            'accountStatus' =>  WSMemberAccountStatusEnum::accepted->value,
            'inviteAcceptedAt' => Carbon::now(),
            'invitedAt' => Carbon::now(),
        ]);

        WSTeamMember::create([
            'uniqueId' => uniqid(),
            'inviterId' => $ahsanUser->id,
            'workspaceId' => $ahsanSWS->id,
            'memberRoleId' => $wsManager->id,
            'memberId' => $adminUser->id,
            'email' => $adminUser->email,
            'accountStatus' =>  WSMemberAccountStatusEnum::accepted->value,
            'inviteAcceptedAt' => Carbon::now(),
            'invitedAt' => Carbon::now(),
        ]);

        WSTeamMember::create([
            'uniqueId' => uniqid(),
            'inviterId' => $ahsanUser->id,
            'workspaceId' => $ahsanSWS->id,
            'memberRoleId' => $wsContributor->id,
            'memberId' => $simpleUser->id,
            'email' => $simpleUser->email,
            'accountStatus' =>  WSMemberAccountStatusEnum::accepted->value,
            'inviteAcceptedAt' => Carbon::now(),
            'invitedAt' => Carbon::now(),
        ]);

        WSTeamMember::create([
            'uniqueId' => uniqid(),
            'inviterId' => $ahsanUser->id,
            'workspaceId' => $ahsanSWS->id,
            'memberRoleId' => $wsWriter->id,
            'memberId' => $wsUser1->id,
            'email' => $wsUser1->email,
            'accountStatus' =>  WSMemberAccountStatusEnum::accepted->value,
            'inviteAcceptedAt' => Carbon::now(),
            'invitedAt' => Carbon::now(),
        ]);

        WSTeamMember::create([
            'uniqueId' => uniqid(),
            'inviterId' => $ahsanUser->id,
            'workspaceId' => $ahsanSWS->id,
            'memberRoleId' => $wsApprover->id,
            'memberId' => $wsUser2->id,
            'email' => $wsUser2->email,
            'accountStatus' =>  WSMemberAccountStatusEnum::accepted->value,
            'inviteAcceptedAt' => Carbon::now(),
            'invitedAt' => Carbon::now(),
        ]);

        WSTeamMember::create([
            'uniqueId' => uniqid(),
            'inviterId' => $ahsanUser->id,
            'workspaceId' => $ahsanSWS->id,
            'memberRoleId' => $wsCommenter->id,
            'memberId' => $wsUser3->id,
            'email' => $wsUser3->email,
            'accountStatus' =>  WSMemberAccountStatusEnum::accepted->value,
            'inviteAcceptedAt' => Carbon::now(),
            'invitedAt' => Carbon::now(),
        ]);

        WSTeamMember::create([
            'uniqueId' => uniqid(),
            'inviterId' => $ahsanUser->id,
            'workspaceId' => $ahsanSWS->id,
            'memberRoleId' => $wsGuest->id,
            'memberId' => $wsUser4->id,
            'email' => $wsUser4->email,
            'accountStatus' =>  WSMemberAccountStatusEnum::accepted->value,
            'inviteAcceptedAt' => Carbon::now(),
            'invitedAt' => Carbon::now(),
        ]);
    }
}
