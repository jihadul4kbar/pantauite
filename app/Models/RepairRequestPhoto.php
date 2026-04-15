<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * RepairRequestPhoto model for photos uploaded with repair requests
 */
class RepairRequestPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_request_id',
        'filename',
        'path',
        'mime_type',
        'file_size',
        'original_size',
        'width',
        'height',
        'photo_taken_at',
        'exif_data',
    ];

    protected $casts = [
        'photo_taken_at' => 'datetime',
        'exif_data' => 'array',
        'file_size' => 'integer',
        'original_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function repairRequest(): BelongsTo
    {
        return $this->belongsTo(RepairRequest::class, 'repair_request_id');
    }

    // ==================== HELPERS ====================

    /**
     * Get the full URL of the photo
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        return number_format($bytes / 1024, 2) . ' KB';
    }

    /**
     * Get formatted original file size
     */
    public function getFormattedOriginalSizeAttribute(): string
    {
        $bytes = $this->original_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        return number_format($bytes / 1024, 2) . ' KB';
    }

    /**
     * Get compression ratio percentage
     */
    public function getCompressionRatioAttribute(): float
    {
        if ($this->original_size == 0) {
            return 0;
        }
        return round((1 - ($this->file_size / $this->original_size)) * 100, 1);
    }

    /**
     * Get formatted photo taken time
     */
    public function getFormattedPhotoTakenAtAttribute(): string
    {
        if (!$this->photo_taken_at) {
            return 'Unknown';
        }
        return $this->photo_taken_at->format('d M Y, H:i:s');
    }

    // ==================== SCOPES ====================

    public function scopeForRepairRequest($query, int $repairRequestId)
    {
        return $query->where('repair_request_id', $repairRequestId);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeWithPhotoTimestamp($query)
    {
        return $query->whereNotNull('photo_taken_at');
    }
}
