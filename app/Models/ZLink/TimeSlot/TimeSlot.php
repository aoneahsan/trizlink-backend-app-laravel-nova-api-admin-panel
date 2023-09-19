<?php

namespace App\Models\ZLink\TimeSlot;

use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class TimeSlot extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array'
    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(WorkSpace::class, 'userId', 'id');
    }
}
