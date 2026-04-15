<?php

namespace App\Policies;

use App\Models\KbArticle;
use App\Models\User;

class KbArticlePolicy
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
     * Determine if user can view articles list.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-kb');
    }

    /**
     * Determine if user can view a specific article.
     */
    public function view(User $user, KbArticle $article): bool
    {
        // Published articles visible to all dengan view-kb permission
        if ($article->status === 'published' 
            && $user->hasPermission('view-kb')) {
            // Internal articles only visible to IT staff/manager
            if ($article->is_internal) {
                return $user->hasPermission('manage-kb');
            }
            return true;
        }

        // Draft/archived articles only visible to author atau KB managers
        if ($article->author_id === $user->id) {
            return true;
        }

        return $user->hasPermission('manage-kb');
    }

    /**
     * Determine if user can create articles.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('manage-kb');
    }

    /**
     * Determine if user can update articles.
     */
    public function update(User $user, KbArticle $article): bool
    {
        // Super admin and IT manager can update any article (handled by before())
        
        // Author can update their own articles (if they have manage-kb permission)
        if ($article->author_id === $user->id) {
            return $user->hasPermission('manage-kb');
        }

        // KB managers can update any article
        return $user->hasPermission('manage-kb');
    }

    /**
     * Determine if user can delete articles.
     */
    public function delete(User $user, KbArticle $article): bool
    {
        // Only IT Manager atau Super Admin
        return $user->hasRole('it_manager')
            || $user->hasRole('super_admin');
    }

    /**
     * Determine if user can publish articles.
     */
    public function publish(User $user, KbArticle $article): bool
    {
        return $user->hasRole('it_manager')
            || $user->hasRole('super_admin');
    }

    /**
     * Determine if user can vote on articles.
     */
    public function vote(User $user, KbArticle $article): bool
    {
        return $user->hasPermission('view-kb');
    }
}
