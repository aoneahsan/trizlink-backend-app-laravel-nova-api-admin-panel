<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // To find migration files in sub folders - Code Start
        $mainPath = database_path('migrations');
        $zlinkPath = database_path('migrations/zlink');
        $paths = array_merge([$mainPath], glob($mainPath . '/*', GLOB_ONLYDIR), glob($zlinkPath . '/*', GLOB_ONLYDIR));

        $this->loadMigrationsFrom($paths);
        // To find migration files in sub folders - Code End
    }
}
