<?php

namespace App\Nova\Metrics\PartitionMetrics;

use App\Models\Default\Task;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class TasksPerStatusPartitionMetrics extends Partition
{
    // https://novapackages.com/packages/saintsystems/nova-linkable-metrics
    // use LinkablePartition;
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Task::class, 'taskStatus');
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
        return 'partition-metrics-tasks-per-status-partition-metrics';
    }
}
