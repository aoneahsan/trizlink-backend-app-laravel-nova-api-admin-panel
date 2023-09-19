<?php

namespace App\Nova\Metrics\TrendMetrics;

use App\Models\Default\Task;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class TasksPerWeekTrendMetrics extends Trend
{
    // https://novapackages.com/packages/saintsystems/nova-linkable-metrics
    // use LinkableTrend;
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByWeeks($request, Task::class)->showLatestValue();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            1 => __('This Week'),
            2 => __('Last Week'),
            3 => __('2 Weeks Ago'),
            6 => __('5 Weeks Ago'),
            11 => __('10 Weeks Ago'),
            21 => __('20 Weeks Ago'),
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'trend-metrics-tasks-per-week-trend-metrics';
    }
}
