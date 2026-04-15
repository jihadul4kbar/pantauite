<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * Ticket model untuk core ticket records
 * 
 * @property int $id
 * @property string $ticket_number
 * @property string $subject
 * @property string $status
 * @property string $priority
 * @property Carbon|null $sla_deadline
 * @property bool $sla_breached
 */
class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'subject',
        'description',
        'status',
        'priority',
        'user_id',
        'assignee_id',
        'department_id',
        'category_id',
        'asset_id',
        'related_kb_article_id',
        'sla_policy_id',
        'sla_deadline',
        'sla_breached',
        'sla_breached_at',
        'paused_at',
        'resolved_at',
        'closed_at',
        'first_response_at',
        'source',
        'resolution_notes',
        'satisfaction_rating',
        'satisfaction_feedback',
    ];

    protected $casts = [
        'status' => 'string',
        'priority' => 'string',
        'sla_breached' => 'boolean',
        'sla_deadline' => 'datetime',
        'sla_breached_at' => 'datetime',
        'paused_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'first_response_at' => 'datetime',
        'satisfaction_rating' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function slaPolicy(): BelongsTo
    {
        return $this->belongsTo(SlaPolicy::class, 'sla_policy_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function relatedKbArticle(): BelongsTo
    {
        return $this->belongsTo(KbArticle::class, 'related_kb_article_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(TicketAuditLog::class);
    }

    // ==================== STATUS HELPERS ====================

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function isReopened(): bool
    {
        return $this->status === 'reopened';
    }

    // ==================== SLA HELPERS ====================

    public function isOverdue(): bool
    {
        return $this->sla_deadline && now()->gt($this->sla_deadline);
    }

    public function isAtRisk(): bool
    {
        if (!$this->sla_deadline || $this->sla_breached) {
            return false;
        }

        $threshold = 30; // minutes
        return now()->addMinutes($threshold)->gte($this->sla_deadline);
    }

    public function markAsBreached(): void
    {
        $this->update([
            'sla_breached' => true,
            'sla_breached_at' => now(),
        ]);
    }

    // ==================== SCOPES ====================

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeNotClosed($query)
    {
        return $query->whereNotIn('status', ['closed']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('sla_deadline', '<', now())
            ->whereNotIn('status', ['closed', 'resolved']);
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assignee_id', $userId);
    }

    public function scopeCreatedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
