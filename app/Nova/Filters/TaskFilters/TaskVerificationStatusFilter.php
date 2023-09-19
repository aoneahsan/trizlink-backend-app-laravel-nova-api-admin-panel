<?php

namespace App\Nova\Filters\TaskFilters;

use App\Zaions\Enums\VerificationStatusEnum;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class TaskVerificationStatusFilter extends Filter
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
        return $query->where('verificationStatus', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return  [
            VerificationStatusEnum::pending->name => VerificationStatusEnum::pending->name,
            VerificationStatusEnum::verified->name => VerificationStatusEnum::verified->name,
            VerificationStatusEnum::approved->name => VerificationStatusEnum::approved->name
        ];
    }
}
