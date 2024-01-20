<?php

namespace App\Models\ZLink\ShortLinks;

use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class ShortLink extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
        'target' => 'array',
        'utmTagInfo' => 'array',
        'abTestingRotatorLinks' => 'array',
        'geoLocationRotatorLinks' => 'array',
        'linkExpirationInfo' => 'array',
        'password' => 'array',
        'featureImg'=> 'array',
        'tags'=> 'array',
        'favicon'=> 'array',

    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createdBy', 'id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(WorkSpace::class, 'workspaceId', 'id');
    }

    public function customDomain(): HasMany
    {
        return $this->hasMany(CustomDomain::class, 'shortLinkId', 'id');
    }

    public function embeddedWidget(): HasMany
    {
        return $this->hasMany(EmbededWidget::class, 'shortLinkId', 'id');
    }
}
