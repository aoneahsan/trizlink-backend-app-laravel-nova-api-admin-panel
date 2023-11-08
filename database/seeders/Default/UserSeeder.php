<?php

namespace Database\Seeders\Default;

use App\Models\Default\User;
use App\Models\Default\UserEmail;
use App\Zaions\Enums\EmailStatusEnum;
use App\Zaions\Enums\RolesEnum;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\Default\User::factory(10)->create();

        // \App\Models\Default\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $superAdminRole = Role::where('name', RolesEnum::superAdmin->name)->get();
        $adminRole = Role::where('name', RolesEnum::admin->name)->get();
        $userRole = Role::where('name', RolesEnum::user->name)->get();

        // create superAdmin user
        $ahsanUser = User::create([
            'uniqueId' => uniqid(),
            'username' => 'ahsan',
            'slug' => 'ahsan',
            // 'email' => env('ADMIN_EMAIL'),
            'email' => env('ADMIN_EMAIL'),
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'lastSeenAt' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);
        // adding a default email entry from ahsanUser in userEmail.
        UserEmail::create([
            'uniqueId' => uniqid(),
            'userId' => $ahsanUser->id,
            'email' => $ahsanUser->email,
            'status' => EmailStatusEnum::Verified->value,
            'isDefault' => true,
            'isPrimary' => true,
        ]);


        $superAdminUser = User::create([
            'uniqueId' => uniqid(),
            'username' => 'superAdmin',
            'slug' => 'superAdmin',
            'email' => 'superAdmin@zaions.com',
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'lastSeenAt' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);
        // adding a default email entry from superAdminUser in userEmail.
        UserEmail::create([
            'uniqueId' => uniqid(),
            'userId' => $superAdminUser->id,
            'email' => $superAdminUser->email,
            'status' => EmailStatusEnum::Verified->value,
            'isDefault' => true,
            'isPrimary' => true,
        ]);

        // create admin user
        $adminUser = User::create([
            'uniqueId' => uniqid(),
            'username' => 'admin',
            'slug' => 'admin',
            'email' => 'admin@zaions.com',
            'password' => Hash::make("asd123!@#"),
            'lastSeenAt' => Carbon::now(),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);
        // adding a default email entry from adminUser in userEmail.
        UserEmail::create([
            'uniqueId' => uniqid(),
            'userId' => $adminUser->id,
            'email' => $adminUser->email,
            'status' => EmailStatusEnum::Verified->value,
            'isDefault' => true,
            'isPrimary' => true,
        ]);

        // create user user
        $simpleUser = User::create([
            'uniqueId' => uniqid(),
            'username' => 'user',
            'slug' => 'user',
            'email' => 'user@zaions.com',
            'password' => Hash::make("asd123!@#"),
            'lastSeenAt' => Carbon::now(),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);
        // adding a default email entry from simpleUser in userEmail.
        UserEmail::create([
            'uniqueId' => uniqid(),
            'userId' => $simpleUser->id,
            'status' => EmailStatusEnum::Verified->value,
            'email' => $simpleUser->email,
            'isDefault' => true,
            'isPrimary' => true,
        ]);

        // Assign Roles
        $ahsanUser->assignRole($superAdminRole);
        $superAdminUser->assignRole($superAdminRole);
        $adminUser->assignRole($adminRole);
        $simpleUser->assignRole($userRole);

        // users account to test workspace member flow
        // create sws test 1 user
        $wsUser1 = User::create([
            'uniqueId' => uniqid(),
            'username' => 'test-user-1',
            'slug' => 'sws-user-1',
            'email' => 'test1@zaions.com',
            'lastSeenAt' => Carbon::now(),
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);
        // adding a default email entry from wsUser1 in userEmail.
        UserEmail::create([
            'uniqueId' => uniqid(),
            'userId' => $wsUser1->id,
            'status' => EmailStatusEnum::Verified->value,
            'email' => $wsUser1->email,
            'isDefault' => true,
            'isPrimary' => true,
        ]);
        $wsUser1->assignRole($userRole);

        // create sws test 2 user
        $wsUser2 = User::create([
            'uniqueId' => uniqid(),
            'username' => 'test-user-2',
            'slug' => 'sws-user-2',
            'email' => 'test2@zaions.com',
            'lastSeenAt' => Carbon::now(),
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);
        // adding a default email entry from wsUser1 in userEmail.
        UserEmail::create([
            'uniqueId' => uniqid(),
            'userId' => $wsUser2->id,
            'status' => EmailStatusEnum::Verified->value,
            'email' => $wsUser2->email,
            'isDefault' => true,
            'isPrimary' => true,
        ]);
        $wsUser2->assignRole($userRole);

        // create sws test 3 user
        $wsUser3 = User::create([
            'uniqueId' => uniqid(),
            'username' => 'test-user-3',
            'slug' => 'sws-user-3',
            'email' => 'test3@zaions.com',
            'lastSeenAt' => Carbon::now(),
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);
        // adding a default email entry from wsUser1 in userEmail.
        UserEmail::create([
            'uniqueId' => uniqid(),
            'userId' => $wsUser3->id,
            'status' => EmailStatusEnum::Verified->value,
            'email' => $wsUser3->email,
            'isDefault' => true,
            'isPrimary' => true,
        ]);
        $wsUser3->assignRole($userRole);

        // create sws test 4 user
        $wsUser4 = User::create([
            'uniqueId' => uniqid(),
            'username' => 'test-user-4',
            'slug' => 'sws-user-4',
            'email' => 'test4@zaions.com',
            'lastSeenAt' => Carbon::now(),
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);
        // adding a default email entry from wsUser1 in userEmail.
        UserEmail::create([
            'uniqueId' => uniqid(),
            'userId' => $wsUser4->id,
            'status' => EmailStatusEnum::Verified->value,
            'email' => $wsUser4->email,
            'isDefault' => true,
            'isPrimary' => true,
        ]);
        $wsUser4->assignRole($userRole);

        // create sws test 5 user
        $wsUser5 = User::create([
            'uniqueId' => uniqid(),
            'username' => 'test-user-5',
            'slug' => 'sws-user-5',
            'email' => 'test5@zaions.com',
            'lastSeenAt' => Carbon::now(),
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);
        // adding a default email entry from wsUser1 in userEmail.
        UserEmail::create([
            'uniqueId' => uniqid(),
            'userId' => $wsUser5->id,
            'status' => EmailStatusEnum::Verified->value,
            'email' => $wsUser5->email,
            'isDefault' => true,
            'isPrimary' => true,
        ]);
        $wsUser5->assignRole($userRole);
    }
}
