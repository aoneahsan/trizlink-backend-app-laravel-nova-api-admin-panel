<?php

namespace App\Nova\Filters\TaskFilters;

use App\Zaions\Enums\TaskTypeEnum;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class TaskTypeFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('type', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return [
            TaskTypeEnum::namaz->name => TaskTypeEnum::namaz->name,
            TaskTypeEnum::exercise->name => TaskTypeEnum::exercise->name,
            TaskTypeEnum::quran->name => TaskTypeEnum::quran->name,
            TaskTypeEnum::dailyOfficeTime->name => TaskTypeEnum::dailyOfficeTime->name,
            TaskTypeEnum::course->name => TaskTypeEnum::course->name,
            TaskTypeEnum::officeWorkTask->name => TaskTypeEnum::officeWorkTask->name,
            TaskTypeEnum::other->name => TaskTypeEnum::other->name
        ];
    }
}
