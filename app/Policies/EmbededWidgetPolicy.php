<?php

namespace App\Policies;

use App\Models\Default\User;
use App\Zaions\Enums\PermissionsEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmbededWidgetPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::viewAny_embededWidget->name);
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::view_embededWidget->name);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_embededWidget->name);
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_embededWidget->name);
    }

    public function replicate(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::replicate_embededWidget->name);
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::delete_embededWidget->name);
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::restore_embededWidget->name);
    }

    public function forceDelete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::forceDelete_embededWidget->name);
    }

    public function runAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_embededWidget->name) && $user->hasPermissionTo(PermissionsEnum::update_embededWidget->name);
    }

    public function runDestructiveAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_embededWidget->name) && $user->hasPermissionTo(PermissionsEnum::delete_embededWidget->name);
    }
}
