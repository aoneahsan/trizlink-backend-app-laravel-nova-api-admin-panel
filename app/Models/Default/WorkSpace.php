<?php

namespace App\Models\Default;

use App\Models\ZLink\Analytics\Pixel;
use App\Models\ZLink\Analytics\UtmTag;
use App\Models\ZLink\Common\Folder;
use App\Models\ZLink\LinkInBios\LinkInBio;
use App\Models\ZLink\ShortLinks\ShortLink;
use App\Models\ZLink\TimeSlot\TimeSlot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class WorkSpace extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'workspaceData' => 'array',
        'extraAttributes' => 'array',
    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function shortLinks(): HasMany
    {
        return $this->hasMany(ShortLink::class, 'workspaceId', 'id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(WorkspaceTeam::class, 'workspaceId', 'id');
    }

    public function linkInBio(): HasMany
    {
        return $this->hasMany(LinkInBio::class, 'workspaceId', 'id');
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class, 'workspaceId', 'id');
    }

    public function folder(): HasMany
    {
        return $this->hasMany(Folder::class, 'workspaceId', 'id');
    }

    public function pixel(): MorphToMany
    {
        return $this->morphedByMany(Pixel::class, 'modal', 'workspace_modal_connections');
    }

    public function UTMTag(): MorphToMany
    {
        return $this->morphedByMany(UtmTag::class, 'modal', 'workspace_modal_connections');
    }
}
