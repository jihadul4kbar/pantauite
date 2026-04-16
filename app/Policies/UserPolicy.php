<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole("super_admin")) {
            return true;
        }

        return null;
    }

    /**
     * Determine if user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission("manage-users") ||
            $user->hasRole("it_manager");
    }

    /**
     * Determine if user can view a user.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasPermission("manage-users") ||
            $user->hasRole("it_manager") ||
            $user->id === $model->id;
    }

    /**
     * Determine if user can create users.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission("manage-users") ||
            $user->hasRole("it_manager");
    }

    /**
     * Determine if user can update a user.
     */
    public function update(User $user, User $model): bool
    {
        // Super admin can update anyone
        if ($user->hasRole("super_admin")) {
            return true;
        }

        // IT Manager can update users except super admins
        if ($user->hasRole("it_manager")) {
            return !$model->hasRole("super_admin");
        }

        // Users can update themselves
        if ($user->id === $model->id) {
            return true;
        }

        return $user->hasPermission("manage-users");
    }

    /**
     * Determine if user can delete a user.
     */
    public function delete(User $user, User $model): bool
    {
        // Cannot delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        // Cannot delete super admins unless you're also super admin
        if ($model->hasRole("super_admin") && !$user->hasRole("super_admin")) {
            return false;
        }

        return $user->hasPermission("manage-users") ||
            $user->hasRole("it_manager");
    }
}
