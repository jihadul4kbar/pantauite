<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
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
     * Determine if user can view tickets list.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-all-tickets') 
            || $user->hasPermission('view-own-tickets');
    }

    /**
     * Determine if user can view a specific ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // Super admin dan IT Manager can view all
        if ($user->hasPermission('view-all-tickets')) {
            return true;
        }

        // IT Staff can view tickets assigned to them
        if ($ticket->assignee_id === $user->id
            && $user->hasRole('it_staff')) {
            return true;
        }

        // Users can view their own tickets
        return $ticket->user_id === $user->id
            && $user->hasPermission('view-own-tickets');
    }

    /**
     * Determine if user can create tickets.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-tickets');
    }

    /**
     * Determine if user can update a ticket.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Full ticket management permission
        if ($user->hasPermission('manage-tickets')) {
            return true;
        }

        // Users can update their own tickets (limited fields)
        if ($ticket->user_id === $user->id 
            && $user->hasPermission('update-own-tickets')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can assign tickets.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->hasPermission('assign-tickets');
    }

    /**
     * Determine if user can change ticket status.
     */
    public function changeStatus(User $user, Ticket $ticket): bool
    {
        // IT Manager dan IT Staff (if assigned)
        if ($user->hasPermission('manage-tickets')) {
            return true;
        }

        return $ticket->assignee_id === $user->id;
    }

    /**
     * Determine if user can close tickets.
     */
    public function close(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('it_manager')
            || $ticket->assignee_id === $user->id;
    }

    /**
     * Determine if user can delete tickets.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        // Only Super Admin atau IT Manager
        return $user->hasRole('super_admin') 
            || $user->hasRole('it_manager');
    }

    /**
     * Determine if user can add comments.
     */
    public function comment(User $user, Ticket $ticket): bool
    {
        // All users can comment on their own tickets
        if ($ticket->user_id === $user->id) {
            return $user->hasPermission('comment-tickets');
        }

        // IT staff can comment on any ticket if they have permission
        return $user->hasPermission('comment-tickets');
    }

    /**
     * Determine if user can add internal notes.
     */
    public function addInternalNote(User $user, Ticket $ticket): bool
    {
        // Only IT staff dan manager
        return $user->hasPermission('manage-tickets')
            || $user->hasRole('it_manager');
    }

    /**
     * Determine if user can view reports.
     */
    public function viewReports(User $user): bool
    {
        return $user->hasPermission('view-reports');
    }
}
