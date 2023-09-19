<?php

namespace App\Nova\Default;

use AlexAzartsev\Heroicon\Heroicon;
use App\Models\Default\ZTestingDemo as ModelsZTestingDemo;
use App\Nova\Actions\ZTestingDemoActions\ZTestActionZaionsLink;
use App\Nova\Filters\ZTestingDemoFilters\CustomInputFilter;
use App\Nova\Resource;
use App\Zaions\Helpers\ZHelpers;
use Degecko\NovaFiltersSummary\FiltersSummary;
use Devtical\Qrcode\Qrcode;
use Dniccum\PhoneNumber\PhoneNumber;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;

use Illuminate\Validation\Rules;
use Laravel\Nova\Actions\ExportAsCsv;
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
use Mostafaznv\NovaVideo\Video;
use Oneduo\NovaFileManager\FileManager;
use Outl1ne\NovaDetachedFilters\NovaDetachedFilters;
use Outl1ne\NovaInputFilter\InputFilter;
use Outl1ne\NovaNotesField\NotesField;
use SLASH2NL\NovaBackButton\NovaBackButton;
use Stepanenko3\NovaCards\Cards\CacheCard;
use Stepanenko3\NovaCards\Cards\CountdownCard;
use Stepanenko3\NovaCards\Cards\LinkableCard;
use Stepanenko3\NovaCards\Cards\PercentageCard;
use Stepanenko3\NovaCards\Cards\VersionsCard;
use Stepanenko3\NovaCards\Cards\WorldClockCard;
use Stepanenko3\NovaCards\Cards\CalendarCard;
use Stepanenko3\NovaCards\Cards\GreeterCard;

class ZTestingDemo extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Default\ZTestingDemo>
     */
    public static $model = \App\Models\Default\ZTestingDemo::class;

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

            Tabs::make('User Info', [
                Tab::make('General Info', [

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
                        ->rules('nullable', 'image', 'size:3000')
                        ->disk(ZHelpers::getActiveFileDriver())
                        ->maxWidth(300),
                    // Images::make('Profile Pitcher', 'profilePitcher') // second parameter is the media collection name
                    //     ->conversionOnIndexView('thumb') // conversion used to display the image
                    // ->disk(ZHelpers::getActiveFileDriver())
                    //     ->rules('nullable', 'image', 'size:3000'), // validation rules

                    // https://novapackages.com/packages/dniccum/phone-number
                    PhoneNumber::make('Phone Number', 'phoneNumber')
                        ->format('+## ### ### ####')
                        ->country('PK')
                ]),
                Tab::make('Other Info', [
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
                ]),
            ]),



            Boolean::make('isActive', 'isActive')->default(true)
                ->show(function (NovaRequest $request) {
                    return ZHelpers::isNRUserSuperAdmin($request);
                }),

            KeyValue::make('Extra Attributes', 'extraAttributes')
                ->rules('nullable', 'json'),

            // Relationship fields below
            // HasMany::make('Tasks'),
            // MorphMany::make('Comments'),
            // MorphMany::make('Attachments'),

            // MorphedByMany::make('Roles', 'roles', \Vyuldashev\NovaPermission\Role::class),
            // MorphedByMany::make('Permissions', 'permissions', \Vyuldashev\NovaPermission\Permission::class),

            // Packages Fields below

            // StatusField::make('Published', 'name')
            //     ->icons([
            //         'minus-circle' => false,
            //         // 'clock'        => $this->pending == 1 && $this->published == 0,
            //         'clock'        => false,
            //         'check-circle' => true
            //     ])
            //     ->tooltip([
            //         'minus-circle' => 'Not published',
            //         'clock'        => 'Pending publication',
            //         'check-circle' => 'Published'
            //     ])
            //     ->info([
            //         'minus-circle' => 'This blog is not published yet.',
            //         'clock'        => 'This blog is pending publication.',
            //         // 'check-circle' => 'This blog is published on ' . $this->published_at->format('d-m-Y') . '.'
            //         'check-circle' => 'This blog is published on today.'
            //     ])
            //     ->color([
            //         'minus-circle' => 'red-500',
            //         'clock'        => 'blue-500',
            //         'check-circle' => 'green-500'
            //     ]),

            // https://novapackages.com/packages/whitecube/nova-flexible-content - working great
            // Flexible::make('Content', 'flexableContent')
            //     ->addLayout('Simple content section', 'wysiwyg', [
            //         StatusField::make('Published', 'name')
            //             ->icons([
            //                 'minus-circle' => false,
            //                 // 'clock'        => $this->pending == 1 && $this->published == 0,
            //                 'clock'        => false,
            //                 'check-circle' => true
            //             ])
            //             ->tooltip([
            //                 'minus-circle' => 'Not published',
            //                 'clock'        => 'Pending publication',
            //                 'check-circle' => 'Published'
            //             ])
            //             ->info([
            //                 'minus-circle' => 'This blog is not published yet.',
            //                 'clock'        => 'This blog is pending publication.',
            //                 // 'check-circle' => 'This blog is published on ' . $this->published_at->format('d-m-Y') . '.'
            //                 'check-circle' => 'This blog is published on today.'
            //             ])
            //             ->color([
            //                 'minus-circle' => 'red-500',
            //                 'clock'        => 'blue-500',
            //                 'check-circle' => 'green-500'
            //             ]),
            //         Text::make('Title'),
            //         Markdown::make('Content')
            //     ])
            //     ->addLayout('Video section', 'video', [
            //         Text::make('Title'),
            //         Image::make('Video Thumbnail', 'thumbnail'),
            //         Text::make('Video ID (YouTube)', 'video'),
            //         Text::make('Video Caption', 'caption')
            //     ]),

            // https://novapackages.com/packages/spatie/nova-tags-field  -  search not working, tags adding working
            // Tags::make('Tags'),

            // https://novapackages.com/packages/vyuldashev/nova-money-field
            // Money::make('Display Name', 'EUR', 'columnName'),

            // https://novapackages.com/packages/pdmfc/nova-action-button
            // ActionButton::make('Export Action')
            //     ->action(DemoTestAction::class, $this->id),
            //->action(new ChangeRole(), $this->id) using a new instance

            // https://novapackages.com/packages/jeffbeltran/sanctum-tokens
            // SanctumTokens::make(),


            // https://novapackages.com/packages/limedeck/nova-cashier-overview
            // if you want to display a specific subscription or multiple
            // Subscription::make('a-fancy-subscription-name'),

            // https://novapackages.com/packages/kirschbaum-development/nova-comments
            // new Commenter(),

            // https://novapackages.com/packages/razorcreations/ajax-field
            // AjaxField::make('Foo')->setUrl('/api/ajaxselect/foo')->setValueKey('id')->setLabelKey('name'),

            // https://novapackages.com/packages/yieldstudio/nova-google-autocomplete
            // Now this address field will search and store the address as a string, but also made available the values in the withValues array
            // GoogleAutocomplete::make('Address', 'address')->withValues(['latitude', 'longitude']),
            // // And you can store the values by autocomplete like this
            // AddressMetadata::make('lat')->fromValue('latitude'),
            // AddressMetadata::make('long')->fromValue('longitude'),
            // // You can disable the field so the user can't edit the metadata
            // AddressMetadata::make('long')->fromValue('longitude')->disabled(),
            // // Or you can make the field invisible in the form but collect the data anyways
            // AddressMetadata::make('long')->fromValue('longitude')->invisible(),
            // AddressMetadata::make('coordinates')->fromValue('{{latitude}}, {{longitude}}'),


            // https://novapackages.com/packages/stepanenko3/nova-json
            // JSON::make('Author', 'jsonFieldContent', [
            //     Text::make('Name')->rules(['string', 'required', 'min:3']),
            //     Text::make('Email')->rules(['email', 'required']),
            // ]),

            // https://novapackages.com/packages/webparking/nova-belongs-to-dependency
            // BelongsToDependency::make('User')
            //     ->dependsOn('type', 'type_id'),

            // https://novapackages.com/packages/kongulov/nova-tab-translatable
            // NovaTabTranslatable::make([
            //     Text::make('Title', 'randomDataTesting'),
            // ]),

            // https://novapackages.com/packages/mostafaznv/nova-video
            // Video::make(trans('Video'), 'randomDataTesting', 'media')
            //     ->rules('file', 'max:150000', 'mimes:mp4', 'mimetypes:video/mp4')
            //     ->creationRules('required')
            //     ->updateRules('nullable'),

            // https://novapackages.com/packages/wdelfuego/nova-datetime
            // DateTime::make(__('Localized label'), 'attribute')
            //     ->withDateFormat('d-M-Y, H:i'),

            // https://novapackages.com/packages/oneduo/nova-file-manager
            // FileManager::make(__('Avatar'), 'randomDataTesting'),

            // https://novapackages.com/packages/alexazartsev/heroicon
            Heroicon::make('Icon', 'heroIconField'),


            // https://novapackages.com/packages/outl1ne/nova-notes-field
            NotesField::make('Notes', 'notesFieldData'),
            // ->placeholder('Add note') // Optional
            // ->addingNotesEnabled(false) // Optional
            // ->fullWidth(true), // Optional

            // https://novapackages.com/packages/sadekd/nova-opening-hours-field
            // NovaOpeningHoursField::make(__('Opening Hours'), 'openingHoursData'),
            // ->allowExceptions(FALSE)    // TRUE by default
            // ->allowOverflowMidnight(TRUE)  // FALSE by default
            // ->useTextInputs(TRUE)  // FALSE by default


            // https://novapackages.com/packages/interaction-design-foundation/nova-unlayer-field
            // Unlayer::make('Content', 'design')->config([
            //     'projectId' => config('unlayer.project_id'),

            //     // optional
            //     'templateId' => config('unlayer.default_template_id'), // Used only if bound attribute ('design' in this case) is empty.
            //     'displayMode' => 'web', // "email" or "web". Default value: "email"
            //     'locale' => 'es', // Locale for Unlayer UI. Default value: application’s locale.
            // ]),

            // https://novapackages.com/packages/alexwenzel/nova-dependency-container
            // DependencyContainer::make([
            //     Text::make('First Name', 'name')
            // ])->dependsOn('email', 'ahsan@zaions.com')
            //             ->dependsOn('field1', 'value1')
            // ->dependsOnNotEmpty('field2')
            // ->dependsOn('field3', 'value3'),


            // https://novapackages.com/packages/sietse85/nova-button
            // Button::make('Zaions')->link('https://nova.laravel.com')->confirm('Confirmation', 'Are you sure you want to cancel your account?', 'Cancel'),


            // https://novapackages.com/packages/digital-creative/custom-relationship-field
            // CustomRelationshipField::make('Items with similar name - CustomRelationshipField', 'similarItems', self::class),


            // https://novapackages.com/packages/devtical/nova-qrcode-field
            Qrcode::make('QR Code', 'qrField')
                ->indexSize(100)
                ->detailSize(500),


            // https://novapackages.com/packages/yieldstudio/nova-google-polygon
            // GooglePolygon::make('Delivery area', 'GooglePolygonfield'),


            // https://novapackages.com/packages/mostafaznv/nova-map-field#screenshots
            // MapPointField::make('location'),
            // MapPolygonField::make('area'),
            // MapMultiPolygonField::make('areas'),

            // https://novapackages.com/packages/khalin/nova4-indicator-field
            // Indicator::make('Status', 'Indicatorfield')
            //     ->labels([
            //         'banned' => 'Banned',
            //         'active' => 'Active',
            //         'invited' => 'Invited',
            //         'inactive' => 'Inactive',
            //     ]),


            // https://novapackages.com/packages/outl1ne/nova-tooltip-field
            // Tooltip::make('Content', 'Tooltipfield')
            //     ->text('Text displayed as field value')
            //     ->content('Content displayed in tooltip')
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
        return [
            // https://novapackages.com/packages/coroowicaksono/chart-js-integration
            // https://coroo.github.io/nova-chartjs/#/?id=installation
            // (new StackedChart())
            //     ->title('Revenue')
            //     ->series(array([
            //         'barPercentage' => 0.5,
            //         'label' => 'Product #1',
            //         'backgroundColor' => '#ffcc5c',
            //         'data' => [30, 70, 80],
            //     ], [
            //         'barPercentage' => 0.5,
            //         'label' => 'Product #2',
            //         'backgroundColor' => '#ff6f69',
            //         'data' => [40, 62, 79],
            //     ]))
            //     ->options([
            //         'xaxis' => [
            //             'categories' => ['Jan', 'Feb', 'Mar']
            //         ],
            //     ])
            //     ->width('1/3'),

            // https://novapackages.com/packages/interaction-design-foundation/nova-html-card
            // (new HtmlCard())->width('1/3')->html('<h1>Hello World!</h1>'),

            // https://novapackages.com/packages/degecko/nova-filters-summary
            // FiltersSummary::make(),

            // https://novapackages.com/packages/slash2nl/nova-back-button
            new NovaBackButton(),

            // https://novapackages.com/packages/outl1ne/nova-detached-filters
            new NovaDetachedFilters($this->myFilters()),

            // https://novapackages.com/packages/nemrutco/nova-global-filter
            // new NovaGlobalFilter([
            //     new Date, // Date Filter
            // ])

            // https://novapackages.com/packages/stepanenko3/nova-cards - Cards - Starts
            GreeterCard::make()
                ->user(
                    name: '$user->name',
                    title: 'Admin',
                )
                ->message(
                    text: 'Welcome back,',
                )
                ->button(
                    name: 'Profile',
                    target: '/nova/resources/users/' . '$user->id',
                )
                ->button(
                    name: 'Users',
                    target: '/nova/resources/users',
                )
                ->avatar(
                    url: '$user->avatar'
                        ? storage_url('$user->avatar', 'public')
                        :  'https://ui-avatars.com/api/?size=300&color=7F9CF5&background=EBF4FF&name=' . '$user->name'
                ),

            // (new WeatherCard)
            //     ->pollingTime(60000) // Optional
            //     ->startPolling(), // Optional. Auto start polling

            (new CalendarCard),

            (new LinkableCard)
                ->title('Docs') // Required
                ->subtitle('subtitle') // Optional
                ->url('/') // Required
                ->target('_blank'), // Default: _self

            (new CacheCard),

            // (new SystemResourcesCard),

            (new VersionsCard),

            // (new ScheduledJobsCard)
            //     ->startPolling() // Optional. Auto start polling
            //     ->pollingTime(1000)
            //     ->width('1/2'),

            // (new BlockchainExchangeCard)
            //     ->width('1/2'),

            // (new NovaReleaseCard),

            // (new EnvironmentCard),

            // (new SslCard)
            //     ->domain('test.com'), // Required

            // (new SslCard)
            //     ->domain('laravel.com'), // Required

            (new PercentageCard)
                ->name('Demo percents') // Optional
                ->label('$') // Optional
                ->count(33) // Required
                ->total(1000) // Required
                ->percentagePrecision(2), // Default: 2

            (new CountdownCard)
                ->to(now()->addDays(30)) // Required
                ->title('30 Days Later') // Optional
                ->label('30 Days Later'), // Optional

            (new WorldClockCard())
                ->timezones([ // Required
                    'Europe/Kiev',
                    'Asia/Tehran',
                    'America/new_york',
                    'America/los_angeles',
                ])
                ->title(__('World Clock')), // Optional

            // A most simple embed
            // (new EmbedCard)
            //     ->url('https://www.youtube.com/embed/WhWc3b3KhnY'), // Required

            // A more complex embed of raw <iframe>...</iframe> HTML
            // (new EmbedCard)
            //     ->withoutPadding() // Optional remove padding in card
            //     ->url('https://www.youtube.com/embed/WhWc3b3KhnY'), // Required
            // https://novapackages.com/packages/stepanenko3/nova-cards - Cards - Ends


            // https://novapackages.com/packages/abordage/nova-table-card
            // MyTableCard::make(),

            // https://novapackages.com/packages/abordage/nova-total-card
            /* with cache expiry time */
            // new TotalCard(\App\Models\Default\User::class, 'All users',  now()->addHour()),
        ];
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
            // https://novapackages.com/packages/outl1ne/nova-input-filter
            InputFilter::make()->forColumns(['email'])->withName('Email'),
            CustomInputFilter::make(),

            // https://novapackages.com/packages/khalin/nova4-searchable-belongs-to-filter
            // (new NovaSearchableBelongsToFilter('my-new-name'))
            //     ->fieldAttribute('department')
            //     ->filterBy('department_id')


            // https://novapackages.com/packages/outl1ne/nova-multiselect-filter
            // NovaMultiselectFilterDemo::make()
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
        return [
            ExportAsCsv::make()->nameable()->withFormat(function ($model) {
                return [
                    'ID' => $model->getKey(),
                    'Name' => $model->name,
                    'Email Address' => $model->email,
                ];
            }),

            ZTestActionZaionsLink::make()

        ];
    }


    // https://novapackages.com/packages/digital-creative/custom-relationship-field
    public function similarItemsFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name'),
            Text::make('Age'),
            //...
        ];
    }
    public static function similarItemsQuery(NovaRequest $request, $query, ModelsZTestingDemo $model)
    {
        return $query->where('name', 'SOUNDS LIKE', $model->name)->whereKeyNot($model->getKey());
    }
    public function similarItemsActions(NovaRequest $request)
    {
        return [];
    }
    public function similarItemsFilters(NovaRequest $request)
    {
        return [];
    }
}
