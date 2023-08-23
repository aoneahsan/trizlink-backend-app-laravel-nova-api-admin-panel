<?php

namespace App\Nova\Lenses\TaskLens;

use App\Zaions\Enums\TaskStatusEnum;
use App\Zaions\Enums\VerificationStatusEnum;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;

class PendingTaskLens extends Lens
{
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->whereNotIn('taskStatus', [TaskStatusEnum::done->name, TaskStatusEnum::closed])->where('verificationStatus', VerificationStatusEnum::pending->name)
        ));
    }

    /**
     * Get the fields available to the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Select::make('Type')->displayUsingLabels(),

            Select::make('Task Status', 'taskStatus')->displayUsingLabels(),

            Select::make('Verification Status', 'verificationStatus')->displayUsingLabels(),

            Select::make('Namaz Offered', 'namazOffered')->displayUsingLabels(),

            Text::make('Remarks By Verifier', 'verifierRemarks'),

            Text::make('Remarks By Approver', 'approverRemarks'),

            Date::make('Course Start Date', 'courseStartDate'),

            Date::make('Course Estimate Date', 'courseEstimateDate'),

            Number::make('Course Total Time In Hours', 'courseTotalTimeInHours'),

            Number::make('Per Day Course Content Time In Hours', 'perDayCourseContentTimeInHours'),

            Number::make('Number of days allowed for Course', 'numberOfDaysAllowedForCourse'),

            Number::make('Time Spend On Exercise In Minutes', 'timeSpendOnExerciseInMinutes'),

            Number::make('Time Spend While Reading Quran In Minutes', 'timeSpendWhileReadingQuranInMinutes'),

            Number::make('work Time Recorded On Traqq', 'workTimeRecordedOnTraqq'),

            Number::make('Traqq Activity For Recorded Time', 'traqqActivityForRecordedTime'),

            Textarea::make('Task Info', 'officeWorkTaskInfo'),

            URL::make('Office Work Task Trello Ticket Link', 'officeWorkTaskTrelloTicketLink'),

            Boolean::make('isActive', 'isActive')->default(true),
        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return parent::actions($request);
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'task-lens-pending-task-lens';
    }
}
