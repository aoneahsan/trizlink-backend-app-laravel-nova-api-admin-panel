<?php

namespace App\Models\ZLink\LinkInBios;

use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class LinkInBio extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'pixelIds' => 'array',
        'utmTagInfo' => 'array',
        'shortUrl' => 'array',
        'abTestingRotatorLinks' => 'array',
        'geoLocationRotatorLinks' => 'array',
        'linkExpirationInfo' => 'array',
        'password' => 'array',
        'theme' => 'array',
        'settings' => 'array',
        'poweredBy' => 'array',
        'extraAttributes' => 'array'
    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(WorkSpace::class, 'workspaceId', 'id');
    }

    public function libBlock(): HasMany
    {
        return $this->hasMany(LibBlock::class, 'linkInBioId', 'id');
    }

    public function libPreDefinedData(): HasMany
    {
        return $this->hasMany(LibPredefinedData::class, 'linkInBioId', 'id');
    }
}
