<?php

namespace App\Models\Default;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class UserSetting extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'settings' => 'array',
        'extraAttributes' => 'array',
    ];

    // Relationship methods
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'userId', 'id');
    // }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(WorkSpace::class, 'workspaceId', 'id');
    }
}
