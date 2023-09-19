<?php

namespace App\Nova\ZLink\LinkInBios;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\ZLink\LinkInBios\LinkInBio as DefaultLinkInBio;
use App\Zaions\Helpers\ZHelpers;
use Laravel\Nova\Fields\Boolean;

class LibPredefinedData extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ZLink\LinkInBios\LibPredefinedData>
     */
    public static $model = \App\Models\ZLink\LinkInBios\LibPredefinedData::class;

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
        'id',
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

            // 
            Gravatar::make()->maxWidth(50),

            // Relationship Fields
            BelongsTo::make('Link-in-bio', 'linkInBio', DefaultLinkInBio::class),

            // Hidden fields
            // Hidden::make('sortOrderNo', 'sortOrderNo')->default(function () {
            //     $lastItem = LinkInBio::latest()->first();
            //     return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            // }),
            Hidden::make('userId', 'userId')
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                }),

            // Normal fields
            Text::make('Unique Id', 'uniqueId')
                ->onlyOnDetail()
                ->default(function () {
                    return uniqid();
                }),

            Text::make('title', 'title')
                ->sortable()
                ->rules('required', 'max:255')
                ->showWhenPeeking(),

            Text::make('Icon', 'icon')
                ->sortable()
                ->rules('required', 'max:255')
                ->showWhenPeeking(),

            Text::make('Type', 'type')
                ->sortable()
                ->rules('required', 'max:255')
                ->showWhenPeeking(),

            Text::make('Pre Defined Data Type', 'preDefinedDataType')
                ->sortable()
                ->rules('max:255')
                ->showWhenPeeking(),

            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Background', 'background')
                ->rules('nullable'),

            KeyValue::make('Extra attributes', 'extraAttributes')
                ->rules('nullable'),
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
