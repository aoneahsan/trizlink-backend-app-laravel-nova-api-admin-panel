<?php

namespace App\Models\Default;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Actions\Actionable;
use Laravel\Nova\Auth\Impersonatable;
use Laravel\Sanctum\HasApiTokens;
use MultiPolygon;
use Outl1ne\NovaNotesField\Traits\HasNotes;
use Point;
use Polygon;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Tags\HasTags;
use Visanduma\NovaTwoFactor\ProtectWith2FA;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class ZTestingDemo extends Model
{
    // use HasApiTokens, HasFactory, Notifiable, HasRoles, HasSlug, HasTags, SoftDeletes, Impersonatable, Actionable, SortableTrait, ProtectWith2FA, HasNotes, HasFlexible, HasSpatialColumns;
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasTags, SoftDeletes, Impersonatable, Actionable, SortableTrait, ProtectWith2FA, HasNotes;

    protected $guarded = [];

    // bolechen/nova-activitylog
    protected static $logAttributes = ['name', 'email'];

    // https://novapackages.com/packages/outl1ne/nova-sortable
    public $sortable = [
        'order_column_name' => 'sortOrderNo',
        'sort_when_creating' => true,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'extraAttributes' => 'array',
        'openingHoursData' => 'array',
        'jsonFieldContent' => 'array',
        'unlayerEmailMakerField' => 'array',
        'flexableContent' => FlexibleCast::class,
        'map-field-location' => Point::class,
        'map-field-area'     => Polygon::class,
        'map-field-areas'    => MultiPolygon::class
    ];

    // public function getSlugOptions(): SlugOptions
    // {
    //     return SlugOptions::create()
    //         ->generateSlugsFrom('name')
    //         ->saveSlugsTo('slug');
    // }

    // https://novapackages.com/packages/ebess/advanced-nova-media-library package setting - starts
    // public function registerMediaConversions(Media $media = null): void
    // {
    //     $this->addMediaConversion('thumb')
    //         ->width(130)
    //         ->height(130);
    // }

    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('main')->singleFile();
    //     $this->addMediaCollection('my_multi_collection');
    // }
    // https://novapackages.com/packages/ebess/advanced-nova-media-library package setting - ends

    // https://novapackages.com/packages/bolechen/nova-activitylog package setting - starts
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults();
    // }
    // https://novapackages.com/packages/bolechen/nova-activitylog package setting - ends
}
