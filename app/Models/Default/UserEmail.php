<?php

namespace App\Models\Default;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class UserEmail extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
        'verifiedAt' => 'datetime'
    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
