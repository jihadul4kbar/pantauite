<?php

namespace App\Services;

use App\Models\KbArticle;
use App\Models\KbArticleVote;
use App\Models\KbCategory;
use App\Models\User;
use App\Repositories\KbRepository;
use Illuminate\Support\Facades\DB;

class KbService
{
    public function __construct(
        private KbRepository $kbRepo
    ) {}

    /**
     * Create new KB article
     */
    public function createArticle(array $data, User $author): KbArticle
    {
        $articleNumber = $this->kbRepo->generateArticleNumber();

        return $this->kbRepo->create([
            'article_number' => $articleNumber,
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'slug' => $this->generateSlug($data['title']),
            'content' => $data['content'],
            'summary' => $data['summary'] ?? null,
            'tags' => $data['tags'] ?? [],
            'is_featured' => $data['is_featured'] ?? false,
            'is_internal' => $data['is_internal'] ?? false,
            'status' => $data['status'] ?? 'draft',
            'author_id' => $author->id,
            'published_at' => ($data['status'] ?? 'draft') === 'published' ? now() : null,
        ]);
    }

    /**
     * Update KB article
     */
    public function updateArticle(KbArticle $article, array $data, User $user): KbArticle
    {
        $updateData = [];

        // Only increment version if content changed
        if (isset($data['content']) && $data['content'] !== $article->content) {
            $updateData['version'] = $article->version + 1;
            $updateData['changelog'] = $data['changelog'] ?? 'Content updated';
        }

        $updateData = array_merge($updateData, [
            'title' => $data['title'] ?? $article->title,
            'slug' => isset($data['title']) ? $this->generateSlug($data['title']) : $article->slug,
            'content' => $data['content'] ?? $article->content,
            'summary' => $data['summary'] ?? $article->summary,
            'tags' => $data['tags'] ?? $article->tags,
            'is_featured' => $data['is_featured'] ?? $article->is_featured,
            'is_internal' => $data['is_internal'] ?? $article->is_internal,
            'category_id' => $data['category_id'] ?? $article->category_id,
        ]);

        // Handle status change to published
        if ($data['status'] === 'published' && $article->status !== 'published') {
            $updateData['published_at'] = now();
            $updateData['reviewed_by'] = $user->id;
            $updateData['reviewed_at'] = now();
        }

        $this->kbRepo->update($article, $updateData);

        return $article->fresh();
    }

    /**
     * Publish article
     */
    public function publishArticle(KbArticle $article, User $user): KbArticle
    {
        return $this->updateArticle($article, [
            'status' => 'published',
            'changelog' => 'Article published',
        ], $user);
    }

    /**
     * Archive article
     */
    public function archiveArticle(KbArticle $article, User $user): KbArticle
    {
        return $this->updateArticle($article, [
            'status' => 'archived',
            'changelog' => 'Article archived',
        ], $user);
    }

    /**
     * Vote on article
     */
    public function vote(KbArticle $article, User $user, string $voteType, ?string $feedback = null): KbArticleVote
    {
        return DB::transaction(function () use ($article, $user, $voteType, $feedback) {
            // Check if user already voted
            $existingVote = KbArticleVote::where('article_id', $article->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existingVote) {
                // Update existing vote
                $oldVote = $existingVote->vote_type;
                $existingVote->update([
                    'vote_type' => $voteType,
                    'feedback' => $feedback,
                ]);

                // Update article vote counts
                $this->updateVoteCounts($article, $oldVote, $voteType);
            } else {
                // Create new vote
                KbArticleVote::create([
                    'article_id' => $article->id,
                    'user_id' => $user->id,
                    'vote_type' => $voteType,
                    'feedback' => $feedback,
                ]);

                // Update article vote counts
                if ($voteType === 'helpful') {
                    $article->increment('helpful_votes');
                } else {
                    $article->increment('not_helpful_votes');
                }
            }

            return KbArticleVote::where('article_id', $article->id)
                ->where('user_id', $user->id)
                ->first();
        });
    }

    /**
     * Update vote counts on article
     */
    protected function updateVoteCounts(KbArticle $article, string $oldVote, string $newVote): void
    {
        if ($oldVote === $newVote) {
            return; // No change
        }

        if ($oldVote === 'helpful') {
            $article->decrement('helpful_votes');
        } else {
            $article->decrement('not_helpful_votes');
        }

        if ($newVote === 'helpful') {
            $article->increment('helpful_votes');
        } else {
            $article->increment('not_helpful_votes');
        }
    }

    /**
     * Get paginated articles with filters
     */
    public function getPaginatedArticles(array $filters = [], int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->kbRepo->getPaginatedArticles($filters, $perPage);
    }

    /**
     * Search articles
     */
    public function search(string $query, array $filters = [], int $limit = 20)
    {
        $searchQuery = KbArticle::withoutTrashed()
            ->where('status', 'published')
            ->search($query);

        if (!auth()->user()->hasPermission('manage-kb')) {
            $searchQuery->public();
        }

        if (isset($filters['category_id'])) {
            $searchQuery->where('category_id', $filters['category_id']);
        }

        return $searchQuery->latest('published_at')->paginate($limit);
    }

    /**
     * Increment article views
     */
    public function incrementViews(KbArticle $article): void
    {
        $article->incrementViews();
    }

    /**
     * Delete KB article (soft delete)
     */
    public function deleteArticle(KbArticle $article): void
    {
        $this->kbRepo->delete($article);
    }

    /**
     * Get dashboard data
     */
    public function getDashboardData(): array
    {
        return [
            'stats' => $this->kbRepo->getStats(),
            'featured' => $this->kbRepo->getFeatured(5),
            'recent' => $this->kbRepo->getRecent(5),
            'categories' => $this->kbRepo->getCategoriesWithCounts(),
        ];
    }

    /**
     * Generate URL-friendly slug
     */
    protected function generateSlug(string $title): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        
        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        
        while (KbArticle::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
