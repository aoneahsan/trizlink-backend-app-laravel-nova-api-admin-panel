<?php

namespace App\Nova\Default;

use AlexAzartsev\Heroicon\Heroicon;
use App\Models\Default\ZTaskSubType as ModelsZTaskSubType;
use App\Nova\Resource;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Color;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class ZTaskSubType extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Default\ZTaskSubType>
     */
    public static $model = \App\Models\Default\ZTaskSubType::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'description'
    ];

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

            BelongsTo::make('user')
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                })
                ->hideFromIndex()
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            BelongsTo::make('Parent', 'parentTaskType', ZTaskType::class)
                ->hideFromIndex()
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->hideWhenCreating(function (NovaRequest $request) {
                    return !ZHelpers::isNRUserSuperAdmin($request);
                })
                ->hideWhenUpdating(function (NovaRequest $request) {
                    return !ZHelpers::isNRUserSuperAdmin($request);
                }),

            Hidden::make('userId', 'userId')
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                }),



            Text::make('Title', 'title')
                ->rules('nullable', 'string')
                ->maxlength(100)
                ->enforceMaxlength(),


            Trix::make('Description', 'description')
                ->rules('nullable', 'string')
                ->showOnIndex(true),

            Color::make('Color', 'color')
                ->rules('nullable'),

            Heroicon::make('Icon', 'icon')
                ->rules('nullable'),



            Hidden::make('sortOrderNo', 'sortOrderNo')->default(function () {
                $lastItem = ModelsZTaskSubType::latest()->first();
                return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
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
