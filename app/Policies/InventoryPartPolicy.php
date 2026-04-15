<?php

namespace App\Policies;

use App\Models\InventoryPart;
use App\Models\User;

class InventoryPartPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager') || $user->hasRole('it_staff');
    }

    public function view(User $user, InventoryPart $part): bool
    {
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager') || $user->hasRole('it_staff');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager');
    }

    public function update(User $user, InventoryPart $part): bool
    {
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager');
    }

    public function delete(User $user, InventoryPart $part): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('it_manager');
    }
}
