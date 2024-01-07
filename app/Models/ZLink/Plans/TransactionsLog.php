<?php

namespace App\Models\ZLink\Plans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class TransactionsLog extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
        'transactionDate' => 'datetime'
    ];

    // Relationship methods
    public function transactions(): BelongsTo
    {
        return $this->belongsTo(Transactions::class, 'transactionId', 'id');
    }
}
