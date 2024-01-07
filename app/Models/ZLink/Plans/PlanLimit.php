<?php

namespace App\Models\ZLink\Plans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class PlanLimit extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
    ];

    // Relationship methods
    public function plan(): BelongsTo  {
        return $this->belongsTo(Plan::class, 'planId', 'id');
    }
}
