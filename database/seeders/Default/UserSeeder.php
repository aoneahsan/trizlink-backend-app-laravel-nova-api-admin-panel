<?php

namespace Database\Seeders\Default;

use App\Models\Default\User;
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
            'name' => 'ahsan',
            'slug' => 'ahsan',
            'email' => 'ahsan@zaions.com',
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);
        $superAdminUser = User::create([
            'uniqueId' => uniqid(),
            'name' => 'superAdmin',
            'slug' => 'superAdmin',
            'email' => 'superAdmin@zaions.com',
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);

        // create admin user
        $adminUser = User::create([
            'uniqueId' => uniqid(),
            'name' => 'admin',
            'slug' => 'admin',
            'email' => 'admin@zaions.com',
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);

        // create user user
        $simpleUser = User::create([
            'uniqueId' => uniqid(),
            'name' => 'user',
            'slug' => 'user',
            'email' => 'user@zaions.com',
            'password' => Hash::make("asd123!@#"),
            'email_verified_at' => Carbon::now(),
            'dailyMinOfficeTime' => 8,
            'dailyMinOfficeTimeActivity' => 85
        ]);

        // Assign Roles
        $ahsanUser->assignRole($superAdminRole);
        $superAdminUser->assignRole($superAdminRole);
        $adminUser->assignRole($adminRole);
        $simpleUser->assignRole($userRole);
    }
}
