<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * AssetDocument model untuk documents terkait assets
 */
class AssetDocument extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'asset_id',
        'document_type',
        'filename',
        'original_filename',
        'file_path',
        'file_size',
        'mime_type',
        'description',
        'uploaded_by',
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'file_size' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ==================== HELPERS ====================

    /**
     * Get human-readable file size
     */
    public function getHumanReadableSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if document is expiring soon
     */
    public function isExpiringInDays(int $days): bool
    {
        if (!$this->expiry_date) {
            return false;
        }

        return now()->diffInDays($this->expiry_date, false) <= $days;
    }

    // ==================== SCOPES ====================

    public function scopeByType($query, string $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeExpiring($query, int $days = 30)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now());
    }
}
