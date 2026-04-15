<?php

namespace App\Policies;

use App\Models\MaintenanceSchedule;
use App\Models\User;

class MaintenanceSchedulePolicy
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

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager');
    }

    public function view(User $user, MaintenanceSchedule $schedule): bool
    {
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager');
    }

    public function update(User $user, MaintenanceSchedule $schedule): bool
    {
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager');
    }

    public function delete(User $user, MaintenanceSchedule $schedule): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('it_manager');
    }
}
