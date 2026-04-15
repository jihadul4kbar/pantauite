<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SlaPolicy model untuk SLA definitions per priority
 */
class SlaPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'priority',
        'response_time_minutes',
        'resolution_time_minutes',
        'use_business_hours',
        'business_hours_start',
        'business_hours_end',
        'business_days',
        'escalation_enabled',
        'escalation_threshold_minutes',
        'escalation_user_id',
        'is_active',
        'description',
    ];

    protected $casts = [
        'use_business_hours' => 'boolean',
        'escalation_enabled' => 'boolean',
        'business_days' => 'array',
        'business_hours_start' => 'datetime:H:i:s',
        'business_hours_end' => 'datetime:H:i:s',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function escalationUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalation_user_id');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get response time in hours
     */
    public function getResponseTimeHoursAttribute(): float
    {
        return $this->response_time_minutes / 60;
    }

    /**
     * Get resolution time in hours
     */
    public function getResolutionTimeHoursAttribute(): float
    {
        return $this->resolution_time_minutes / 60;
    }

    /**
     * Check if SLA is 24/7
     */
    public function is247(): bool
    {
        return !$this->use_business_hours;
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }
}
