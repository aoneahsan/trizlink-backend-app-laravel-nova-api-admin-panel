<?php

namespace App\Providers;

use App\Channels\ZDatabaseChannel;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Support\ServiceProvider;

class DatabaseProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->instance(IlluminateDatabaseChannel::class, new ZDatabaseChannel);
    }
}
