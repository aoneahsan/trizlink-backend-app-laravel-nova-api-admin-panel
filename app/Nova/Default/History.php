<?php

namespace App\Nova\Default;

use App\Models\Default\History as ModelsHistory;
use App\Nova\Resource;
use App\Zaions\Enums\HistoryTypeEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class History extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Default\History>
     */
    public static $model = \App\Models\Default\History::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'detail'
    ];

    /**
     * The number of results to display when searching for relatable resources without Scout.
     *
     * @var int|null
     */
    public static $relatableSearchResults = 10;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            // Relationship Fields
            BelongsTo::make('Task (main task)', 'task', Task::class)
                ->hideFromIndex()
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->hideWhenCreating(true)
                ->hideWhenUpdating(true)
                ->peekable(true)
                ->searchable(),

            BelongsTo::make('Created By', 'user', User::class)
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                })
                ->hideFromIndex()
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            MorphMany::make('History Comments', 'comments', Comment::class),
            MorphMany::make('History Attachments', 'attachments', Attachment::class),

            // Hidden Fields
            Hidden::make('userId', 'userId')
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                }),
            Hidden::make('sortOrderNo', 'sortOrderNo')->default(function () {
                $lastItem = ModelsHistory::latest()->first();
                return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            }),

            // Normal Form Fields
            Select::make('Type')
                ->searchable()
                ->rules('required', 'string', new Enum(HistoryTypeEnum::class))
                ->options(function () {
                    return [
                        HistoryTypeEnum::courseUpdate->name => 'Course Update',
                        HistoryTypeEnum::officeTaskUpdate->name => 'Office Task Update',
                        HistoryTypeEnum::other->name => 'Other',
                    ];
                })
                ->displayUsingLabels(),
            Text::make('Detail', 'detail')->rules('required', 'string')->maxlength(1500)->enforceMaxlength(),
            Number::make('Time Spend On Course', 'timeSpendOnCourse')
                ->readonly(true)
                ->rules('nullable')
                ->hide()
                ->showOnDetail(function () {
                    return $this->type === HistoryTypeEnum::courseUpdate->name;
                })
                ->dependsOn('type', function (Number $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === HistoryTypeEnum::courseUpdate->name) {
                        $field->readonly(false)
                            ->rules('required')
                            ->show()
                            ->min(1)
                            ->max(10);
                    }
                }),
            Number::make('Time Spend On Office Task', 'timeSpendOnOfficeTask')
                ->readonly(true)
                ->rules('nullable')
                ->hide()
                ->showOnDetail(function () {
                    return $this->type === HistoryTypeEnum::officeTaskUpdate->name;
                })
                ->dependsOn('type', function (Number $field, NovaRequest $request, FormData $formData) {
                    if ($formData->type === HistoryTypeEnum::officeTaskUpdate->name) {
                        $field->readonly(false)
                            ->rules('required')
                            ->show()
                            ->min(1)
                            ->max(10);
                    }
                }),

            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Extra Attributes', 'extraAttributes')
                ->rules('nullable', 'json'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
