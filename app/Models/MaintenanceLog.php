<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * MaintenanceLog model untuk record maintenance activities
 */
class MaintenanceLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_id',
        'maintenance_type',
        'title',
        'description',
        'performed_by',
        'vendor_name',
        'cost',
        'currency',
        'start_date',
        'end_date',
        'status',
        'attachments',
        'outcome',
        'recommendations',
        'next_maintenance_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'cost' => 'decimal:2',
        'attachments' => 'array',
        'next_maintenance_date' => 'date',
    ];

    // ==================== RELATIONSHIPS ====================

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    // ==================== STATUS HELPERS ====================

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    // ==================== SCOPES ====================

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeUpcoming($query, int $days = 30)
    {
        return $query->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<=', now()->addDays($days))
            ->where('next_maintenance_date', '>', now());
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('maintenance_type', $type);
    }
}
