<?php

namespace App\Models\ZLink\ShortLinks;

use App\Models\Default\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class EmbededWidget extends Model
{
    use
        HasFactory,
        SoftDeletes,
        Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',

    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function shortLink(): BelongsTo
    {
        return $this->belongsTo(ShortLink::class, 'userId', 'id');
    }
}
