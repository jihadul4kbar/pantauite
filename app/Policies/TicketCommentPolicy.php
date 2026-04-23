<?php

namespace App\Policies;

use App\Models\TicketComment;
use App\Models\User;

class TicketCommentPolicy
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
     * Determine if user can update a comment.
     * Only the comment author can edit within the time limit.
     */
    public function update(User $user, TicketComment $comment): bool
    {
        if ($user->hasPermission('manage-tickets')) {
            return true;
        }

        return $comment->user_id === $user->id && $comment->isEditableBy($user);
    }

    /**
     * Determine if user can delete a comment.
     * Only the comment author can delete (within time limit) or users with delete permission.
     */
    public function delete(User $user, TicketComment $comment): bool
    {
        if ($user->hasPermission('tickets.delete')) {
            return true;
        }

        return $comment->user_id === $user->id && $comment->isEditableBy($user);
    }
}
