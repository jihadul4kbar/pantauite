<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine if user can view any departments.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage-departments')
            || $user->hasRole('it_manager');
    }

    /**
     * Determine if user can view a department.
     */
    public function view(User $user, Department $department): bool
    {
        return $user->hasPermission('manage-departments')
            || $user->hasRole('it_manager');
    }

    /**
     * Determine if user can create departments.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('manage-departments')
            || $user->hasRole('it_manager');
    }

    /**
     * Determine if user can update a department.
     */
    public function update(User $user, Department $department): bool
    {
        return $user->hasPermission('manage-departments')
            || $user->hasRole('it_manager');
    }

    /**
     * Determine if user can delete a department.
     */
    public function delete(User $user, Department $department): bool
    {
        return $user->hasPermission('manage-departments')
            || $user->hasRole('it_manager');
    }
}
