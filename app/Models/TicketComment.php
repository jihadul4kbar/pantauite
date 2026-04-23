<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * TicketComment model untuk comments dan updates pada tickets
 */
class TicketComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment',
        'is_internal',
        'is_solution',
        'workflow_stage',
        'attachments',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'is_solution' => 'boolean',
        'attachments' => 'array',
        'workflow_stage' => 'string',
    ];

    // ==================== RELATIONSHIPS ====================

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ==================== SCOPES ====================

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    public function scopeSolution($query)
    {
        return $query->where('is_solution', true);
    }

    public function scopeByWorkflowStage($query, string $stage)
    {
        return $query->where('workflow_stage', $stage);
    }

    public function getWorkflowStageLabelAttribute(): string
    {
        return Ticket::WORKFLOW_STAGE_LABELS[$this->workflow_stage] ?? $this->workflow_stage;
    }

    public function isEditableBy(User $user): bool
    {
        if ($this->user_id !== $user->id) {
            return false;
        }
        
        $editWindow = config('ticket.comment_edit_window_minutes', 15);
        
        return now()->diffInMinutes($this->created_at) <= $editWindow;
    }
}
