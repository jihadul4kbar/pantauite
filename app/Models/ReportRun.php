<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ReportRun model untuk tracking report generation
 */
class ReportRun extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'report_type',
        'filters',
        'format',
        'file_path',
        'file_size',
        'generated_by',
        'generation_time_ms',
    ];

    protected $casts = [
        'filters' => 'array',
        'file_size' => 'integer',
        'generation_time_ms' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    // ==================== SCOPES ====================

    public function scopeByType($query, string $type)
    {
        return $query->where('report_type', $type);
    }

    public function scopeByFormat($query, string $format)
    {
        return $query->where('format', $format);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('generated_by', $userId);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
