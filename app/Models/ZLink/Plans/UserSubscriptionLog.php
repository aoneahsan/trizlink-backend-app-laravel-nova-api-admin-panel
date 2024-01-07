<?php

namespace App\Models\ZLink\Plans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class UserSubscriptionLog extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
        'actionDate' => 'datetime'
    ];

    // Relationship methods
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'subscriptionId', 'id');
    }
}
