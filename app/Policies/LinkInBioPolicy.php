<?php

namespace App\Policies;

use App\Models\Default\User;
use App\Zaions\Enums\PermissionsEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkInBioPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::viewAny_linkInBio->name);
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::view_linkInBio->name);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_linkInBio->name);
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_linkInBio->name);
    }

    public function replicate(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::replicate_linkInBio->name);
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::delete_linkInBio->name);
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::restore_linkInBio->name);
    }

    public function forceDelete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::forceDelete_linkInBio->name);
    }

    public function runAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_linkInBio->name) && $user->hasPermissionTo(PermissionsEnum::update_linkInBio->name);
    }

    public function runDestructiveAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_linkInBio->name) && $user->hasPermissionTo(PermissionsEnum::delete_linkInBio->name);
    }
}
