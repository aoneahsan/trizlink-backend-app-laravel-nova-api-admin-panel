<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Default\AttachmentSeeder;
use Database\Seeders\Default\CommentSeeder;
use Database\Seeders\Default\HistorySeeder;
use Database\Seeders\Default\RolePermissionsSeeder;
use Database\Seeders\Default\TaskSeeder;
use Database\Seeders\Default\UserSeeder;
use Database\Seeders\ZLink\Analytics\PixelSeeder;
use Database\Seeders\ZLink\Analytics\UtmTagSeeder;
use Database\Seeders\ZLink\LinkInBios\LibBlockSeeder;
use Database\Seeders\ZLink\LinkInBios\LibPredefinedDataSeeder;
use Database\Seeders\ZLink\LinkInBios\LinkInBioSeeder;
use Database\Seeders\ZLink\ShortLinks\ShortLinkSeeder;
use Database\Seeders\ZLink\SocialMedia\PostSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Default DB Seeders
            RolePermissionsSeeder::class,
            UserSeeder::class,
            TaskSeeder::class,
            HistorySeeder::class,
            CommentSeeder::class,
            AttachmentSeeder::class,

            // ----------------- ZLink Project DB Seeders -----------------
            // ShortLinks DB Seeders
            ShortLinkSeeder::class,

            // LinkInBios DB Seeders
            LinkInBioSeeder::class,
            LibBlockSeeder::class,
            LibPredefinedDataSeeder::class,

            // Social Media DB Seeders
            PostSeeder::class,

            // Analytics DB Seeders
            PixelSeeder::class,
            UtmTagSeeder::class,
        ]);
    }
}
