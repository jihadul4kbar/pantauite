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
<<<<<<< HEAD
        if ($user->hasRole("super_admin")) {
=======
        if ($user->hasRole('super_admin')) {
>>>>>>> 7470507c696abb1293b9dc1f4dbd7b95b9394097
            return true;
        }

        return null;
    }

    /**
     * Determine if user can view any users.
     */
    public function viewAny(User $user): bool
    {
<<<<<<< HEAD
        return $user->hasPermission("manage-users") ||
            $user->hasRole("it_manager");
=======
        return $user->hasPermission('manage-users')
            || $user->hasRole('it_manager');
>>>>>>> 7470507c696abb1293b9dc1f4dbd7b95b9394097
    }

    /**
     * Determine if user can view a user.
     */
    public function view(User $user, User $model): bool
    {
<<<<<<< HEAD
        return $user->hasPermission("manage-users") ||
            $user->hasRole("it_manager") ||
            $user->id === $model->id;
=======
        return $user->hasPermission('manage-users')
            || $user->hasRole('it_manager')
            || $user->id === $model->id;
>>>>>>> 7470507c696abb1293b9dc1f4dbd7b95b9394097
    }

    /**
     * Determine if user can create users.
     */
    public function create(User $user): bool
    {
<<<<<<< HEAD
        return $user->hasPermission("manage-users") ||
            $user->hasRole("it_manager");
=======
        return $user->hasPermission('manage-users')
            || $user->hasRole('it_manager');
>>>>>>> 7470507c696abb1293b9dc1f4dbd7b95b9394097
    }

    /**
     * Determine if user can update a user.
     */
    public function update(User $user, User $model): bool
    {
        // Super admin can update anyone
<<<<<<< HEAD
        if ($user->hasRole("super_admin")) {
=======
        if ($user->hasRole('super_admin')) {
>>>>>>> 7470507c696abb1293b9dc1f4dbd7b95b9394097
            return true;
        }

        // IT Manager can update users except super admins
<<<<<<< HEAD
        if ($user->hasRole("it_manager")) {
            return !$model->hasRole("super_admin");
=======
        if ($user->hasRole('it_manager')) {
            return !$model->hasRole('super_admin');
>>>>>>> 7470507c696abb1293b9dc1f4dbd7b95b9394097
        }

        // Users can update themselves
        if ($user->id === $model->id) {
            return true;
        }

<<<<<<< HEAD
        return $user->hasPermission("manage-users");
=======
        return $user->hasPermission('manage-users');
>>>>>>> 7470507c696abb1293b9dc1f4dbd7b95b9394097
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
<<<<<<< HEAD
        if ($model->hasRole("super_admin") && !$user->hasRole("super_admin")) {
            return false;
        }

        return $user->hasPermission("manage-users") ||
            $user->hasRole("it_manager");
=======
        if ($model->hasRole('super_admin') && !$user->hasRole('super_admin')) {
            return false;
        }

        return $user->hasPermission('manage-users')
            || $user->hasRole('it_manager');
>>>>>>> 7470507c696abb1293b9dc1f4dbd7b95b9394097
    }
}
