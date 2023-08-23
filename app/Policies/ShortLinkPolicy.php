<?php

namespace App\Policies;

use App\Models\Default\User;
use App\Zaions\Enums\PermissionsEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShortLinkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::viewAny_shortLink->name);
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::view_shortLink->name);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_shortLink->name);
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_shortLink->name);
    }

    public function replicate(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::replicate_shortLink->name);
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::delete_shortLink->name);
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::restore_shortLink->name);
    }

    public function forceDelete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::forceDelete_shortLink->name);
    }

    public function runAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_shortLink->name) && $user->hasPermissionTo(PermissionsEnum::update_shortLink->name);
    }

    public function runDestructiveAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_shortLink->name) && $user->hasPermissionTo(PermissionsEnum::delete_shortLink->name);
    }
}
