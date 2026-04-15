<?php

namespace App\Repositories;

use App\Models\KbArticle;
use App\Models\KbCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class KbRepository
{
    /**
     * Get paginated articles with filters
     */
    public function getPaginatedArticles(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = KbArticle::withoutTrashed()
            ->with(['category', 'author', 'reviewer'])
            ->where('status', 'published');

        // Only show non-internal articles to regular users
        if (!auth()->user()->hasPermission('manage-kb')) {
            $query->public();
        }

        // Apply filters
        if (isset($filters['category_id']) && $filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $query->search($filters['search']);
        }

        if (isset($filters['tag']) && $filters['tag']) {
            $query->whereJsonContains('tags', $filters['tag']);
        }

        return $query->latest('published_at')->paginate($perPage);
    }

    /**
     * Find article by ID
     */
    public function findArticleById(int $id): ?KbArticle
    {
        return KbArticle::with(['category', 'author', 'reviewer'])->find($id);
    }

    /**
     * Find article by article number
     */
    public function findByNumber(string $number): ?KbArticle
    {
        return KbArticle::where('article_number', $number)->first();
    }

    /**
     * Create new article
     */
    public function create(array $data): KbArticle
    {
        return KbArticle::create($data);
    }

    /**
     * Update article
     */
    public function update(KbArticle $article, array $data): bool
    {
        return $article->update($data);
    }

    /**
     * Delete article (soft delete)
     */
    public function delete(KbArticle $article): bool
    {
        // Explicitly set deleted_at and save
        $article->deleted_at = now();
        return $article->save();
    }

    /**
     * Get categories with article counts
     */
    public function getCategoriesWithCounts(): Collection
    {
        return KbCategory::withCount(['articles' => function ($query) {
            $query->published();
            if (!auth()->user()->hasPermission('manage-kb')) {
                $query->public();
            }
        }])
        ->active()
        ->root()
        ->ordered()
        ->get();
    }

    /**
     * Get most viewed articles
     */
    public function getMostViewed(int $limit = 10): Collection
    {
        $query = KbArticle::withoutTrashed()
            ->with(['category', 'author'])
            ->where('status', 'published')
            ->mostViewed($limit);

        if (!auth()->user()->hasPermission('manage-kb')) {
            $query->public();
        }

        return $query->get();
    }

    /**
     * Get recently published articles
     */
    public function getRecent(int $limit = 10): Collection
    {
        $query = KbArticle::withoutTrashed()
            ->with(['category', 'author'])
            ->where('status', 'published')
            ->latest('published_at')
            ->limit($limit);

        if (!auth()->user()->hasPermission('manage-kb')) {
            $query->public();
        }

        return $query->get();
    }

    /**
     * Get featured articles
     */
    public function getFeatured(int $limit = 5): Collection
    {
        $query = KbArticle::withoutTrashed()
            ->with(['category', 'author'])
            ->where('status', 'published')
            ->featured()
            ->limit($limit);

        if (!auth()->user()->hasPermission('manage-kb')) {
            $query->public();
        }

        return $query->get();
    }

    /**
     * Generate next article number
     */
    public function generateArticleNumber(): string
    {
        // Include soft-deleted articles to avoid duplicate article numbers
        $lastArticle = KbArticle::withTrashed()->orderBy('id', 'desc')->first();
        $nextNumber = $lastArticle ? (int) substr($lastArticle->article_number, 3) + 1 : 1;

        return 'KB-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get article statistics
     */
    public function getStats(): array
    {
        $query = KbArticle::withoutTrashed();

        // For non-manage users, only count public articles
        if (!auth()->user()->hasPermission('manage-kb')) {
            $query->public();
        }

        return [
            'total' => (clone $query)->count(),
            'published' => (clone $query)->published()->count(),
            'draft' => (clone $query)->draft()->count(),
            'archived' => (clone $query)->archived()->count(),
            'featured' => (clone $query)->featured()->count(),
            'total_views' => (clone $query)->sum('views'),
        ];
    }
}
