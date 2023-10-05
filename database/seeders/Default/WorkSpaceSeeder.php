<?php

namespace Database\Seeders\Default;

use App\Models\Default\User;
use App\Models\Default\WorkSpace;
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
        $ahsanUser = User::where('email', 'ahsan@zaions.com')->first();
        $simpleUser = User::where('email', 'user@zaions.com')->first();

        WorkSpace::create([
            'uniqueId' => uniqid(),
            'userId' => $ahsanUser->id,
            'title' => 'Ahsan share workspace'
        ]);

        WorkSpace::create([
            'uniqueId' => uniqid(),
            'userId' => $simpleUser->id,
            'title' => 'User share workspace'
        ]);
    }
}
