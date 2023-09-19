<?php

namespace App\Nova\ZLink\ShortLinks;

use App\Models\ZLink\ShortLinks\ShortLink;
use App\Nova\Resource;
use App\Zaions\Helpers\ZHelpers;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\ZLink\ShortLinks\ShortLink as DefaultShortLink;
use Laravel\Nova\Fields\Select;

class EmbededWidget extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\ZLink\ShortLinks\EmbededWidget>
     */
    public static $model = \App\Models\ZLink\ShortLinks\EmbededWidget::class;

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

            Gravatar::make()->maxWidth(50),

            // Relationship Fields
            // BelongsTo::make('Short-links', 'shortLink', DefaultShortLink::class),

            // Hidden fields
            // Hidden::make('sortOrderNo', 'sortOrderNo')->default(function () {
            //     $lastItem = LinkInBio::latest()->first();
            //     return $lastItem ? $lastItem->sortOrderNo + 1 : 1;
            // }),
            Hidden::make('userId', 'userId')
                ->default(function (NovaRequest $request) {
                    return $request->user()->getKey();
                }),

            Select::make('Short Link', 'shortLinkId')->options(function () {
                $items = ShortLink::take(20)->pluck('id', 'title');
                $Array = [];
                foreach ($items as $id => $title) {
                    $Array[$title] = $id;
                }
                return $Array;
            }),

            // Normal fields
            Text::make('Unique Id', 'uniqueId')
                ->onlyOnDetail()
                ->default(function () {
                    return uniqid();
                }),

            Text::make('Name', 'name')
                ->sortable()
                ->rules('required', 'max:255')
                ->showWhenPeeking(),

            Boolean::make('Write js code', 'canCodeJs')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),


            Boolean::make('Write html code', 'canCodeHtml')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            Text::make('Js Code', 'jsCode')
                ->sortable()
                ->rules('max:255')
                ->showWhenPeeking(),

            Text::make('Html Code', 'HTMLCode')
                ->sortable()
                ->rules('max:255')
                ->showWhenPeeking(),

            Text::make('Display at', 'displayAt')
                ->sortable()
                ->rules('max:255')
                ->showWhenPeeking(),

            Text::make('Delay', 'delay')
                ->sortable()
                ->rules('max:255')
                ->showWhenPeeking(),

            Text::make('Position', 'position')
                ->sortable()
                ->rules('max:255')
                ->showWhenPeeking(),

            Boolean::make('Animation', 'animation')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            Boolean::make('Closing Option', 'closingOption')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

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
