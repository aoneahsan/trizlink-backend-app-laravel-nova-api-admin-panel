<?php

namespace App\Models\Default\Notification;

use App\Models\Default\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class UserNotificationSetting extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'invitationNotification' => 'array',
        'newDeviceLogin' => 'array',
        'passwordReset' => 'array',
        'otherNotification' => 'array',
        'browser' => 'array',
        'email' => 'array',
        'sms' => 'array',
        'extraAttributes' => 'array',
    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

}
