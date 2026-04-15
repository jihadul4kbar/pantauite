<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * KbArticleVote model untuk user feedback pada KB articles
 */
class KbArticleVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'user_id',
        'vote_type',
        'feedback',
    ];

    // ==================== RELATIONSHIPS ====================

    public function article(): BelongsTo
    {
        return $this->belongsTo(KbArticle::class, 'article_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ==================== HELPERS ====================

    public function isHelpful(): bool
    {
        return $this->vote_type === 'helpful';
    }

    public function isNotHelpful(): bool
    {
        return $this->vote_type === 'not_helpful';
    }
}
