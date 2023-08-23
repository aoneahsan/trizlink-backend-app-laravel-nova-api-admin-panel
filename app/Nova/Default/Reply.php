<?php

namespace App\Nova\Default;

use App\Models\Default\Reply as ModelsReply;
use App\Nova\Resource;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Reply extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Default\Reply>
     */
    public static $model = \App\Models\Default\Reply::class;

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
        'id', 'content'
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

            BelongsTo::make('comment')
                ->hideFromIndex()
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->hideWhenCreating()
                ->hideWhenUpdating(),


            Hidden::make('userId', 'userId')
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                }),

            Textarea::make('Reply Text', 'content')
                ->rules('nullable', 'string')
                ->maxlength(1500)
                ->enforceMaxlength()
                ->alwaysShow(),


            Hidden::make('sortOrderNo', 'sortOrderNo')->default(function () {
                $lastItem = ModelsReply::latest()->first();
                return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            }),


            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Extra Attributes', 'extraAttributes')
                ->rules('nullable', 'json'),

            MorphMany::make('Attachments'),
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
