<?php

namespace App\Models\Default;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Crypt;
use Laravel\Nova\Actions\Actionable;
use Spatie\Permission\Models\Role;

class WSTeamMember extends Model
{
    use SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
        'invitedAt' => 'datetime',
        'accountStatusLastUpdatedBy' => 'datetime',
        'inviteAcceptedAt' => 'datetime',
        'inviteRejectedAt' => 'datetime',
        'resendAllowedAfter' => 'datetime'
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

    // public function workspaceTeam(): BelongsTo
    // {
    //     return $this->belongsTo(WorkspaceTeam::class, 'teamId', 'id');
    // }

    public function memberRole(): HasOne
    {
        // Crypt::encryptString('');
        // Crypt::decryptString('');
        return $this->hasOne(Role::class, 'id', 'memberRoleId');
    }
}



// inviteToken


// encrypted token    secret