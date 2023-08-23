<?php

namespace App\Models\Default;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Task extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'namazOfferedAt' => 'datetime',
        'courseStartDate' => 'datetime',
        'extraAttributes' => 'array',
        'courseEstimateDate' => 'datetime'
    ];


    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function history(): HasMany
    {
        return $this->hasMany(History::class, 'taskId', 'id');
    }

    // simple/admin user who verified this task
    public function verifier(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'verifiedBy');
    }

    // (admin) user who approved this task
    public function approver(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'approvedBy');
    }
}
