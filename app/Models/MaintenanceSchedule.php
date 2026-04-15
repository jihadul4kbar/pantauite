<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * MaintenanceSchedule model untuk jadwal perawatan berkala asset
 */
class MaintenanceSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_id',
        'name',
        'description',
        'maintenance_type',
        'frequency_type',
        'frequency_value',
        'next_due_date',
        'last_completed_date',
        'estimated_duration_minutes',
        'estimated_cost',
        'approval_threshold',
        'assigned_to_user_id',
        'vendor_id',
        'is_active',
    ];

    protected $casts = [
        'next_due_date' => 'date',
        'last_completed_date' => 'date',
        'estimated_duration_minutes' => 'integer',
        'estimated_cost' => 'decimal:2',
        'approval_threshold' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(MaintenanceTask::class);
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(MaintenanceChecklistItem::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(MaintenanceRequirement::class);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDueSoon($query, int $days = 7)
    {
        return $query->where('next_due_date', '<=', now()->addDays($days))
            ->where('next_due_date', '>=', now())
            ->where('is_active', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('next_due_date', '<', now())
            ->where('is_active', true);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get frequency display text
     */
    public function getFrequencyDisplayAttribute(): string
    {
        $types = [
            'daily' => 'Every day',
            'weekly' => "Every {$this->frequency_value} week(s)",
            'monthly' => "Every {$this->frequency_value} month(s)",
            'yearly' => "Every {$this->frequency_value} year(s)",
            'custom' => "Custom ({$this->frequency_value} days)",
        ];

        return $types[$this->frequency_type] ?? $this->frequency_type;
    }

    /**
     * Calculate next due date based on frequency
     */
    public function calculateNextDueDate(): \Carbon\Carbon
    {
        $date = $this->last_completed_date ? \Carbon\Carbon::parse($this->last_completed_date) : now();

        return match ($this->frequency_type) {
            'daily' => $date->addDays($this->frequency_value),
            'weekly' => $date->addWeeks($this->frequency_value),
            'monthly' => $date->addMonths($this->frequency_value),
            'yearly' => $date->addYears($this->frequency_value),
            'custom' => $date->addDays($this->frequency_value),
            default => $date->addMonths(1),
        };
    }

    /**
     * Check if schedule is overdue
     */
    public function isOverdue(): bool
    {
        return $this->next_due_date->isPast() && $this->is_active;
    }

    /**
     * Check if maintenance is due soon
     */
    public function isDueSoon(int $days = 7): bool
    {
        return $this->next_due_date->between(now(), now()->addDays($days));
    }
}
