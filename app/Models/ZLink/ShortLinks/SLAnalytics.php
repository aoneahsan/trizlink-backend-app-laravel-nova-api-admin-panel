<?php

namespace App\Models\ZLink\ShortLinks;

use App\Models\Default\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class SLAnalytics extends Model
{
    use
        HasFactory,
        SoftDeletes,
        Actionable;

    protected $guarded = [];

    // TODO: set according to DB
    protected $casts = [
        'extraAttributes' => 'array',
        'userLocationCoords' => 'array',
        'userDeviceInfo' => 'array',
    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function shortLink(): BelongsTo
    {
        return $this->belongsTo(ShortLink::class, 'shortLinkId', 'id');
    }

    // TODO: complete visiterUserId DB
    // public function visiterUser()
    // {
    //     if ($this->)
    // }
}
