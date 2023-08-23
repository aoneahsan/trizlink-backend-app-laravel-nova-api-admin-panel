<?php

namespace App\Nova\Filters\TaskFilters;

use App\Zaions\Enums\TaskStatusEnum;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class TaskStatusFilter extends Filter
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
        return $query->where('taskStatus', $value);
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
            TaskStatusEnum::todo->name => TaskStatusEnum::todo->name,
            TaskStatusEnum::inProgress->name => TaskStatusEnum::inProgress->name,
            TaskStatusEnum::requireInfo->name => TaskStatusEnum::requireInfo->name,
            TaskStatusEnum::availableForReview->name => TaskStatusEnum::availableForReview->name,
            TaskStatusEnum::done->name => TaskStatusEnum::done->name,
            TaskStatusEnum::closed->name => TaskStatusEnum::closed->name,
            TaskStatusEnum::other->name => TaskStatusEnum::other->name
        ];
    }
}
