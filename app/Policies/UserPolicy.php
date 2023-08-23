<?php

namespace App\Policies;

use App\Models\Default\User;
use App\Zaions\Enums\PermissionsEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::viewAny_user->name);
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::view_user->name);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_user->name);
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_user->name);
    }

    public function replicate(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::replicate_user->name);
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::delete_user->name);
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::restore_user->name);
    }

    public function forceDelete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::forceDelete_user->name);
    }

    public function runAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_user->name) && $user->hasPermissionTo(PermissionsEnum::update_user->name);
    }

    public function runDestructiveAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_user->name) && $user->hasPermissionTo(PermissionsEnum::delete_user->name);
    }
}
