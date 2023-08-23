<?php

namespace App\Nova\ZLink\ShortLinks;

use App\Nova\Default\WorkSpace as DefaultWorkSpace;
use App\Nova\Resource;
use App\Zaions\Helpers\ZHelpers;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;

class ShortLink extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ZLink\ShortLinks\ShortLink>
     */
    public static $model = \App\Models\ZLink\ShortLinks\ShortLink::class;

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
        'id',
        'title'
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

            Gravatar::make()->maxWidth(50),

            // Relationship Fields
            BelongsTo::make('Work Space', 'workspace', DefaultWorkSpace::class),

            // Hidden fields
            Hidden::make('sortOrderNo', 'sortOrderNo')->default(function () {
                $lastItem = ShortLink::latest()->first();
                return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            }),
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

            Text::make('title')
                ->sortable()
                ->rules('required', 'max:255')
                ->showWhenPeeking(),

            Number::make('Folder Id', 'folderId')->rules('required'),

            Text::make('type')
                ->sortable()
                ->rules('required', 'max:255')
                ->showWhenPeeking(),

            KeyValue::make('target', 'target')
                ->rules('nullable'),


            Image::make('Feature Image', 'featureImg')
                ->rules('nullable', 'image')
                ->disk(ZHelpers::getActiveFileDriver())
                ->maxWidth(300),

            Text::make('description')
                ->sortable()
                ->rules('max:255')
                ->showWhenPeeking(),

            KeyValue::make('UTM Tag Info', 'utmTagInfo')
                ->rules('nullable'),

            KeyValue::make('short Url', 'shortUrl')
                ->rules('nullable'),

            Text::make('notes')
                ->sortable()
                ->rules('max:255')
                ->showWhenPeeking(),

            Text::make('tags')
                ->sortable()
                ->rules('max:255')
                ->showWhenPeeking(),

            KeyValue::make('AB Testing Rotator Links', 'abTestingRotatorLinks')
                ->rules('nullable'),

            KeyValue::make('GEO Location Rotator Links', 'geoLocationRotatorLinks')
                ->rules('nullable'),

            KeyValue::make('Link Expiration Info', 'linkExpirationInfo')
                ->rules('nullable'),

            KeyValue::make('Password', 'password')
                ->rules('nullable'),

            Text::make('favicon')
                ->sortable()
                ->rules('max:255')
                ->showWhenPeeking(),

            Boolean::make('Is Favorite', 'isFavorite')->default(true)->rules('nullable'),

            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Extra Attributes', 'extraAttributes')
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
