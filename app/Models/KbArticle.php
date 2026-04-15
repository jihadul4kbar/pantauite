<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * KbArticle model untuk knowledge base articles
 */
class KbArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'article_number',
        'category_id',
        'title',
        'slug',
        'content',
        'summary',
        'tags',
        'is_featured',
        'is_internal',
        'version',
        'changelog',
        'status',
        'published_at',
        'author_id',
        'reviewed_by',
        'reviewed_at',
        'views',
        'helpful_votes',
        'not_helpful_votes',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_internal' => 'boolean',
        'version' => 'integer',
        'views' => 'integer',
        'helpful_votes' => 'integer',
        'not_helpful_votes' => 'integer',
        'published_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'meta_keywords' => 'array',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category(): BelongsTo
    {
        return $this->belongsTo(KbCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ==================== STATUS HELPERS ====================

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    /**
     * Calculate helpful percentage
     */
    public function getHelpfulPercentageAttribute(): float
    {
        $total = $this->helpful_votes + $this->not_helpful_votes;
        
        if ($total === 0) {
            return 0.0;
        }
        
        return round(($this->helpful_votes / $total) * 100, 2);
    }

    // ==================== SCOPES ====================

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    public function scopeMostViewed($query, int $limit = 10)
    {
        return $query->orderBy('views', 'desc')->limit($limit);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('content', 'like', "%{$term}%")
                ->orWhere('summary', 'like', "%{$term}%");
        });
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
