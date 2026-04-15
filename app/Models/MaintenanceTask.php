<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * MaintenanceTask model untuk work order perawatan individual
 */
class MaintenanceTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_number',
        'schedule_id',
        'asset_id',
        'title',
        'description',
        'maintenance_type',
        'priority',
        'status',
        'assigned_to_user_id',
        'assigned_to_user_ids',
        'vendor_id',
        'scheduled_date',
        'started_at',
        'completed_at',
        'actual_duration_minutes',
        'estimated_cost',
        'actual_cost',
        'approval_status',
        'approved_by_user_id',
        'approved_at',
        'approval_comments',
        'notes',
        'resolution_notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'actual_duration_minutes' => 'integer',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'approved_at' => 'datetime',
        'assigned_to_user_ids' => 'array',
    ];

    // ==================== RELATIONSHIPS ====================

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Get all assigned users (including primary and additional)
     */
    public function getAssignedUsersAttribute()
    {
        $ids = $this->assigned_to_user_ids ?? [];
        if (empty($ids)) {
            return collect();
        }
        return User::whereIn('id', $ids)->get();
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function checklistResults(): HasMany
    {
        return $this->hasMany(MaintenanceChecklistResult::class, 'task_id');
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(MaintenanceRequirement::class, 'task_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(MaintenancePhoto::class, 'task_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(MaintenanceEvaluation::class, 'task_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(MaintenanceApproval::class);
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeAwaitingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        return $this->scheduled_date->isPast() && !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Check if task requires approval
     */
    public function requiresApproval(): bool
    {
        if ($this->schedule && $this->schedule->approval_threshold) {
            return $this->estimated_cost > $this->schedule->approval_threshold;
        }
        return $this->estimated_cost > 1000000; // Default threshold: 1 juta
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'gray',
            'scheduled' => 'blue',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'cancelled' => 'red',
            'overdue' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray',
        };
    }

    /**
     * Calculate duration in hours
     */
    public function getDurationHoursAttribute(): ?float
    {
        if (!$this->actual_duration_minutes) {
            return null;
        }
        return round($this->actual_duration_minutes / 60, 2);
    }
}
