<?php

namespace App\Models\Default;

use App\Models\Default\Notification\UserNotificationSetting;
use App\Models\Default\UserSetting;
use App\Models\ZLink\Plans\Transactions;
use App\Models\ZLink\Plans\TransactionsLog;
use App\Models\ZLink\Plans\UserSubscription;
use App\Models\ZLink\Plans\UserSubscriptionLog;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Actions\Actionable;
use Laravel\Nova\Auth\Impersonatable;
use Laravel\Sanctum\HasApiTokens;
use Outl1ne\NovaNotesField\Traits\HasNotes;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Tags\HasTags;
use Visanduma\NovaTwoFactor\ProtectWith2FA;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasTags, SoftDeletes, Impersonatable, Actionable, SortableTrait, ProtectWith2FA, HasNotes;

    protected $guarded = [];


    // https://novapackages.com/packages/outl1ne/nova-sortable
    public $sortable = [
        'order_column_name' => 'sortOrderNo',
        'sort_when_creating' => true,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'extraAttributes' => 'array',
        'profileImage' => 'array',
        'email_verified_at' => 'datetime',
        'OPTCodeValidTill' => 'datetime',
        'lastSeenAt' => 'datetime',
    ];

    public function wantsBreadcrumbs()
    {
        return true;
    }

    public function wantsRTL()
    {
        return false;
    }

    public function canImpersonate()
    {
        return $this->hasPermissionTo(PermissionsEnum::can_impersonate->name);
    }

    public function canBeImpersonated()
    {
        return $this->hasPermissionTo(PermissionsEnum::canBe_impersonate->name) && !$this->hasRole(RolesEnum::superAdmin->name);
    }

    // User Modal Attributes getter functions
    public function getUserTimezoneAttribute()
    {
        // $this->timezone will get set by user or admin on profile setting page, otherwise we will use "Asia/Karachi"
        if ($this->timezone) {
            return $this->timezone;
        } else {
            return config('app.timezone', 'Asia/Karachi');
        }
    }

    // Relationship data methods
    public function workSpace(): HasMany
    {
        return $this->hasMany(workSpace::class, 'userId', 'id');
    }


    public function userSettings(): HasMany
    {
        return $this->HasMany(UserSetting::class, 'userId', 'id');
    }

    public function USNotificationSettings(): HasOne
    {
        return $this->HasOne(UserNotificationSetting::class, 'userId', 'id');
    }

    // User can belong to many workspace as member (added by other user)
    public function asMember(): BelongsToMany
    {
        return $this->belongsToMany(WorkSpace::class, 'workspace_members');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'userId', 'id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    // Subscription and Plan Relationships
    public function subscription(): HasOne  {
        return $this->hasOne(UserSubscription::class, 'userId', 'id');
    }

    public function transactions(): HasMany  {
        return $this->hasMany(Transactions::class, 'userId', 'id');
    }

    public function userTransactionsLogs(): HasManyThrough
    {
        return $this->hasManyThrough(
            TransactionsLog::class,
            Transactions::class,
            'userId', // Foreign key on the transactions table...
            'transactionId', // Foreign key on the transactions_logs table...
            'id', // Local key on the transactions table...
            'id' // Local key on the transactions_logs table...
        );
    }

    public function userSubscriptionLogs(): HasManyThrough
    {
        return $this->hasManyThrough(
            UserSubscriptionLog::class,
            UserSubscription::class,
            'userId', // Foreign key on the user_subscription table...
            'subscriptionId', // Foreign key on the user_subscription_log table...
            'id', // Local key on the user_subscription table...
            'id' // Local key on the user_subscription_log table...
        );
    }

}
