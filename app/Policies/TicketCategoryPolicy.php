<?php

namespace App\Policies;

use App\Models\TicketCategory;
use App\Models\User;

class TicketCategoryPolicy
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
     * Determine if user can view any ticket categories.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage-categories')
            || $user->hasRole('it_manager')
            || $user->hasRole('it_staff');
    }

    /**
     * Determine if user can view a ticket category.
     */
    public function view(User $user, TicketCategory $category): bool
    {
        return $user->hasPermission('manage-categories')
            || $user->hasRole('it_manager')
            || $user->hasRole('it_staff');
    }

    /**
     * Determine if user can create ticket categories.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('manage-categories')
            || $user->hasRole('it_manager')
            || $user->hasRole('it_staff');
    }

    /**
     * Determine if user can update a ticket category.
     */
    public function update(User $user, TicketCategory $category): bool
    {
        return $user->hasPermission('manage-categories')
            || $user->hasRole('it_manager')
            || $user->hasRole('it_staff');
    }

    /**
     * Determine if user can delete a ticket category.
     */
    public function delete(User $user, TicketCategory $category): bool
    {
        return $user->hasPermission('manage-categories')
            || $user->hasRole('it_manager')
            || $user->hasRole('it_staff');
    }
}
