<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\PartitionMetrics\TasksPerStatusPartitionMetrics;
use App\Nova\Metrics\TrendMetrics\TasksPerWeekTrendMetrics;
use App\Nova\Metrics\ValueMetrics\TaskCountValueMetrics;
use App\Nova\Metrics\ValueMetrics\UserCountValueMetrics;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            // new \Richardkeep\NovaTimenow\NovaTimenow,
            // (new \Richardkeep\NovaTimenow\NovaTimenow)->timezones([
            //     'Africa/Nairobi',
            //     'America/Mexico_City',
            //     'Australia/Sydney',
            //     'Europe/Paris',
            //     'Asia/Tokyo',
            // ])->defaultTimezone('Africa/Nairobi'),

            UserCountValueMetrics::make(),
            TaskCountValueMetrics::make(),
            TasksPerWeekTrendMetrics::make(),
            TasksPerStatusPartitionMetrics::make()
        ];
    }
}
