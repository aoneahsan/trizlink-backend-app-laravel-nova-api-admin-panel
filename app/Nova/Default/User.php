<?php

namespace App\Nova\Default;

use App\Nova\Resource;
use App\Zaions\Helpers\ZHelpers;
use Dniccum\PhoneNumber\PhoneNumber;

use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Timezone;
use Laravel\Nova\Fields\Image;
use Outl1ne\NovaInputFilter\InputFilter;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class User extends Resource
{
    use HasSortableRows;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Default\User>
     */
    public static $model = \App\Models\Default\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'email',
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

            Gravatar::make()->maxWidth(50),

            Text::make('Unique Id', 'uniqueId')
                ->onlyOnDetail()
                ->default(function () {
                    return uniqid();
                }),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255')
                ->showWhenPeeking(),

            Text::make('Slug')
                ->sortable()
                ->hideFromIndex()
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}')
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->filterable(function ($request, $query, $value, $attribute) {
                    return $query->where($attribute, 'LIKE', "%{$value}%");
                }),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults())
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            Image::make('Profile Pitcher', 'profilePitcher')
                ->rules('nullable', 'image')
                ->disk(ZHelpers::getActiveFileDriver())
                ->maxWidth(300),

            // https://novapackages.com/packages/dniccum/phone-number
            PhoneNumber::make('Phone Number', 'phoneNumber')
                ->format('+## ### ### ####')
                ->country('PK'),

            Timezone::make('Timezone', 'timezone')->searchable()->default(ZHelpers::getTimezone()),

            Number::make('dailyMinOfficeTime', 'dailyMinOfficeTime')
                ->default(function () {
                    return 8;
                })
                ->min(3)
                ->max(12)
                ->step('any')
                ->rules('required', 'numeric', 'min:3', 'max:12')
                ->showOnIndex(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnCreating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            Number::make('dailyMinOfficeTimeActivity', 'dailyMinOfficeTimeActivity')
                ->default(function ($request) {
                    return 85;
                })
                ->min(70)
                ->max(100)
                ->step('any')
                ->rules('required', 'numeric', 'min:70', 'max:100')
                ->showOnIndex(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnCreating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnUpdating(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                })
                ->showOnDetail(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),


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
        return $this->myFilters();
    }

    protected function myFilters()
    {
        return [
            InputFilter::make()->forColumns(['email'])->withName('Email')
        ];
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
