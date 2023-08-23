<?php

namespace App\Policies;

use App\Models\Default\User;
use App\Zaions\Enums\PermissionsEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::viewAny_comment->name);
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::view_comment->name);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_comment->name);
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_comment->name);
    }

    public function replicate(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::replicate_comment->name);
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::delete_comment->name);
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::restore_comment->name);
    }

    public function forceDelete(User $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::forceDelete_comment->name);
    }

    public function runAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::create_comment->name) && $user->hasPermissionTo(PermissionsEnum::update_comment->name);
    }

    public function runDestructiveAction(User  $user)
    {
        return $user->hasPermissionTo(PermissionsEnum::update_comment->name) && $user->hasPermissionTo(PermissionsEnum::delete_comment->name);
    }
}
