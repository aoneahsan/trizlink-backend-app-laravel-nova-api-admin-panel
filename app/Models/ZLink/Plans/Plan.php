<?php

namespace App\Models\ZLink\Plans;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Plan extends Pivot
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
        'features' => 'array'
    ];

    protected function annualPrice(): Attribute
    {
        // $currency = $this->attributes['currency'] ? $this->attributes['currency'] : '';
        
        return Attribute::make(
            get: fn ($value) => $value ? 'annual charge of '. $value : '',
        );
    }

    // Relationship methods
    public function subscription(): BelongsToMany  {
        return $this->belongsToMany(UserSubscription::class, 'planId', 'id');
    }

    public function planLimits(): HasMany  {
        return $this->hasMany(PlanLimit::class, 'planId', 'id');
    }
}
