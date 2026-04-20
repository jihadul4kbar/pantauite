<?php

namespace App\Policies;

use App\Models\RepairRequest;
use App\Models\User;

class RepairRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-repair-requests') 
            || $user->isItManager() 
            || $user->isItStaff()
            || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RepairRequest $repairRequest): bool
    {
        return $user->isSuperAdmin()
            || $user->isItManager()
            || $user->isItStaff();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Public access
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RepairRequest $repairRequest): bool
    {
        return $user->isSuperAdmin()
            || $user->isItManager()
            || $user->isItStaff();
    }

    /**
     * Determine whether the user can delete the model.
     * ONLY SUPER ADMIN CAN DELETE
     */
    public function delete(User $user, RepairRequest $repairRequest): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RepairRequest $repairRequest): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RepairRequest $repairRequest): bool
    {
        return $user->isSuperAdmin();
    }
}