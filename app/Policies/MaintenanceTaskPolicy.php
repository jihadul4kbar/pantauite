<?php

namespace App\Policies;

use App\Models\MaintenanceTask;
use App\Models\User;

class MaintenanceTaskPolicy
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

    public function view(User $user, MaintenanceTask $task): bool
    {
        // Assigned user can view their own tasks
        if ($task->assigned_to_user_id === $user->id) {
            return true;
        }
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager');
    }

    public function update(User $user, MaintenanceTask $task): bool
    {
        // Assigned user can update (execute) their tasks
        if ($task->assigned_to_user_id === $user->id) {
            return true;
        }
        return $user->hasPermission('manage-assets') || $user->hasRole('it_manager');
    }

    public function execute(User $user, MaintenanceTask $task): bool
    {
        return $task->assigned_to_user_id === $user->id || $user->hasPermission('manage-assets');
    }

    public function approve(User $user, MaintenanceTask $task): bool
    {
        return $user->hasRole('it_manager') || $user->hasRole('super_admin');
    }

    public function delete(User $user, MaintenanceTask $task): bool
    {
        return $user->hasRole('super_admin') || $user->hasRole('it_manager');
    }
}
