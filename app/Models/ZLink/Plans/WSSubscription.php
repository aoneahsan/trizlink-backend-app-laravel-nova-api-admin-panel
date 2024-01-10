<?php

namespace App\Models\ZLink\Plans;

use App\Models\Default\User;
use App\Models\Default\WorkSpace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class WSSubscription extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
        'startedAt' => 'datetime',
        'endedAt' => 'datetime',
        'renewedAt' => 'datetime',
        'canceledAt' => 'datetime'
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

    public function plan(): BelongsTo  {
        return $this->belongsTo(Plan::class, 'planId', 'id');
    }

    public function subscriptionLogs(): HasMany  {
        return $this->hasMany(UserSubscriptionLog::class, 'subscriptionId', 'id');
    }

    public function transactions(): HasMany  {
        return $this->hasMany(Transactions::class, 'subscriptionId', 'id');
    }
    
    public function planLimits(): HasManyThrough
    {
        return $this->hasManyThrough(
            PlanLimit::class,
            Plan::class,
            'id', // Foreign key on the plan table...
            'planId', // Foreign key on the plan_limit table...
            'planId', // Local key on the plan table...
            'id' // Local key on the plan_limit table...
        );
    }   
}
