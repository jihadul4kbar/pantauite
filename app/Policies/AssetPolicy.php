<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    /**
     * Determine if user can view assets.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-assets')
            || $user->hasPermission('manage-assets');
    }

    /**
     * Determine if user can view a specific asset.
     */
    public function view(User $user, Asset $asset): bool
    {
        return $user->hasPermission('view-assets')
            || $user->hasPermission('manage-assets');
    }

    /**
     * Determine if user can create assets.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('manage-assets');
    }

    /**
     * Determine if user can update assets.
     */
    public function update(User $user, Asset $asset): bool
    {
        return $user->hasPermission('manage-assets');
    }

    /**
     * Determine if user can delete assets.
     */
    public function delete(User $user, Asset $asset): bool
    {
        // Only IT Manager atau Super Admin
        return $user->hasRole('it_manager')
            || $user->hasRole('super_admin');
    }

    /**
     * Determine if user can assign asset to user.
     */
    public function assign(User $user, Asset $asset): bool
    {
        return $user->hasPermission('manage-assets');
    }

    /**
     * Determine if user can log maintenance.
     */
    public function logMaintenance(User $user, Asset $asset): bool
    {
        return $user->hasPermission('manage-assets');
    }
}
