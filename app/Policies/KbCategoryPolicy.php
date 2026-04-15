<?php

namespace App\Policies;

use App\Models\KbCategory;
use App\Models\User;

class KbCategoryPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('it_manager')) {
            return true;
        }

        return null;
    }

    /**
     * Determine if user can view any categories.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-kb') || $user->hasPermission('manage-kb');
    }

    /**
     * Determine if user can create categories.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('manage-kb');
    }

    /**
     * Determine if user can update categories.
     */
    public function update(User $user, KbCategory $category): bool
    {
        return $user->hasPermission('manage-kb');
    }

    /**
     * Determine if user can delete categories.
     */
    public function delete(User $user, KbCategory $category): bool
    {
        return $user->hasPermission('manage-kb');
    }
}
