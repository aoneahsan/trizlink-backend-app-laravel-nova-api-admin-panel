<?php

namespace App\Models\ZLink\Analytics;

use App\Models\Default\WorkSpace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Pixel extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
    ];

    // Relationship methods
    public function workspaces(): BelongsTo
    {
        return $this->belongsTo(WorkSpace::class, 'workspaceId', 'id');
    }

    // public function workspaces()
    // {
    //     return $this->morphToMany(WorkSpace::class, 'modal', 'workspace_modal_connections');
    // }
}
