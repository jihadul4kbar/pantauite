<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketAuditLog;
use App\Models\User;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        private TicketRepository $tickets,
        private SlaService $sla
    ) {}

    /**
     * Create new ticket
     */
    public function createTicket(array $data, User $user): Ticket
    {
        return DB::transaction(function () use ($data, $user) {
            // Generate ticket number
            $ticketNumber = $this->tickets->generateTicketNumber();

            // Create ticket
            $ticket = $this->tickets->create([
                'ticket_number' => $ticketNumber,
                'subject' => $data['subject'],
                'description' => $data['description'],
                'priority' => $data['priority'],
                'category_id' => $data['category_id'] ?? null,
                'department_id' => $data['department_id'] ?? $user->department_id,
                'user_id' => $user->id,
                'status' => 'open',
                'source' => $data['source'] ?? 'web',
            ]);

            // Initialize SLA
            $this->sla->initialize($ticket);

            // Log creation
            $this->logAudit($ticket, 'created', [
                'ticket_number' => $ticketNumber,
                'source' => $ticket->source,
            ]);

            return $ticket;
        });
    }

    /**
     * Update ticket
     */
    public function updateTicket(Ticket $ticket, array $data, User $user): Ticket
    {
        $oldValues = $ticket->getChanges();

        $this->tickets->update($ticket, $data);

        // Log changes
        $newValues = $ticket->getChanges();
        if ($oldValues !== $newValues) {
            $this->logAudit($ticket, 'updated', [
                'old' => $oldValues,
                'new' => $newValues,
            ]);
        }

        return $ticket->fresh();
    }

    /**
     * Change ticket status
     */
    public function changeStatus(Ticket $ticket, string $newStatus, User $user): Ticket
    {
        $oldStatus = $ticket->status;

        $updateData = ['status' => $newStatus];

        // Set timestamps based on status
        if ($newStatus === 'resolved') {
            $updateData['resolved_at'] = now();
        } elseif ($newStatus === 'closed') {
            $updateData['closed_at'] = now();
        }

        $this->tickets->update($ticket, $updateData);

        // Log status change
        $this->logAudit($ticket, 'status_changed', [
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);

        return $ticket->fresh();
    }

    /**
     * Assign ticket to user(s)
     */
    public function assignTicket(Ticket $ticket, ?int $assigneeId, User $assignedBy, array $assigneeIds = []): Ticket
    {
        $oldAssignee = $ticket->assignee_id;
        $oldAssignees = $ticket->assignees->pluck('user_id')->toArray();

        // Set primary assignee (for backward compatibility)
        if ($assigneeId) {
            $this->tickets->update($ticket, [
                'assignee_id' => $assigneeId,
            ]);
        }

        // Handle multi-assignees
        if (!empty($assigneeIds)) {
            // Use attach with pivot data instead of sync
            $ticket->assignees()->detach();
            
            foreach ($assigneeIds as $index => $userId) {
                $ticket->assignees()->attach($userId, [
                    'assigned_at' => now(),
                    'assigned_by' => $assignedBy->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Set first assignee as primary if not set
                if ($index === 0 && !$assigneeId) {
                    $this->tickets->update($ticket, [
                        'assignee_id' => $userId,
                    ]);
                }
            }
        }

        // Log assignment
        $newAssigneeIds = !empty($assigneeIds) ? $assigneeIds : ($assigneeId ? [$assigneeId] : []);
        if (!empty($newAssigneeIds)) {
            $assignees = User::whereIn('id', $newAssigneeIds)->get();
            $assigneeNames = $assignees->pluck('name')->join(', ');
            
            $this->logAudit($ticket, 'assigned', [
                'old_assignee_id' => $oldAssignee,
                'old_assignees' => $oldAssignees,
                'new_assignee_id' => $assigneeId,
                'new_assignees' => $newAssigneeIds,
                'assignee_names' => $assigneeNames,
                'assigned_by' => $assignedBy->name,
            ]);

            // Auto change status to in_progress if was open
            if ($ticket->status === 'open') {
                $this->changeStatus($ticket, 'in_progress', $assignedBy);
            }
        }

        return $ticket->fresh();
    }

    /**
     * Add comment to ticket
     */
    public function addComment(Ticket $ticket, array $data, User $user)
    {
        $workflowStage = $data['workflow_stage'] ?? $ticket->current_workflow_stage;
        
        $comment = $ticket->comments()->create([
            'user_id' => $user->id,
            'comment' => $data['comment'],
            'is_internal' => $data['is_internal'] ?? false,
            'is_solution' => $data['is_solution'] ?? false,
            'workflow_stage' => $workflowStage,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        if (!$ticket->first_response_at && $workflowStage === 'respon') {
            $this->markFirstResponse($ticket);
        }

        return $comment;
    }

    /**
     * Update comment
     */
    public function updateComment($comment, array $data, User $user)
    {
        if (!$comment->isEditableBy($user)) {
            throw new \Exception('Comment cannot be edited');
        }

        $comment->update([
            'comment' => $data['comment'],
        ]);

        return $comment->fresh();
    }

    /**
     * Delete comment
     */
    public function deleteComment($comment, User $user)
    {
        if ($comment->user_id !== $user->id && !$user->hasPermissionTo('tickets.delete')) {
            throw new \Exception('Unauthorized to delete this comment');
        }

        return $comment->delete();
    }

    /**
     * Update documentation milestone
     */
    public function updateDocumentationMilestone(Ticket $ticket, string $milestone, User $user, ?string $resolutionNotes = null): void
    {
        $allowedMilestones = ['before_photos', 'after_photos', 'completion_report'];
        
        if (!in_array($milestone, $allowedMilestones)) {
            throw new \Exception('Invalid milestone');
        }

        if ($milestone === 'completion_report' && empty($resolutionNotes)) {
            throw new \Exception('Resolution notes are required for completion report');
        }

        $updateData = [];
        
        if ($milestone === 'completion_report' && $resolutionNotes) {
            $updateData['resolution_notes'] = $resolutionNotes;
            $updateData['resolved_at'] = now();
        }

        $ticket->markDocumentationMilestone($milestone);
        
        if (!empty($updateData)) {
            $ticket->update($updateData);
        }

        $this->logAudit($ticket, 'documentation_milestone', [
            'milestone' => $milestone,
            'updated_by' => $user->name,
            'has_resolution_notes' => (bool) $resolutionNotes,
        ]);
    }

    /**
     * Mark first response time
     */
    public function markFirstResponse(Ticket $ticket): void
    {
        if (!$ticket->first_response_at) {
            $ticket->update(['first_response_at' => now()]);
        }
    }

    /**
     * Pause SLA (waiting for customer)
     */
    public function pauseSla(Ticket $ticket): void
    {
        if (!$ticket->paused_at) {
            $ticket->update(['paused_at' => now()]);

            $this->logAudit($ticket, 'sla_paused', [
                'reason' => 'Waiting for customer response',
            ]);
        }
    }

    /**
     * Resume SLA
     */
    public function resumeSla(Ticket $ticket): void
    {
        if ($ticket->paused_at) {
            // Calculate pause duration and add to deadline
            $pauseDuration = now()->diffInMinutes($ticket->paused_at);
            $newDeadline = $ticket->sla_deadline?->addMinutes($pauseDuration);

            $ticket->update([
                'paused_at' => null,
                'sla_deadline' => $newDeadline,
            ]);

            $this->logAudit($ticket, 'sla_resumed', [
                'pause_duration_minutes' => $pauseDuration,
            ]);
        }
    }

    /**
     * Delete ticket
     */
    public function deleteTicket(Ticket $ticket, User $user): bool
    {
        $this->logAudit($ticket, 'deleted', [
            'deleted_by' => $user->name,
        ]);

        return $ticket->delete();
    }

    /**
     * Get paginated tickets with filters
     */
    public function getPaginated(array $filters = [], int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->tickets->getPaginated($filters, $perPage);
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'total' => Ticket::count(),
            'open' => Ticket::open()->count(),
            'in_progress' => Ticket::inProgress()->count(),
            'resolved' => Ticket::resolved()->count(),
            'closed' => Ticket::closed()->count(),
            'overdue' => Ticket::overdue()->count(),
            'sla_compliance' => $this->sla->getCompliancePercentage(),
            'by_priority' => $this->tickets->getCountByPriority(),
            'by_status' => $this->tickets->getCountByStatus(),
        ];
    }

    /**
     * Log audit trail
     */
    protected function logAudit(Ticket $ticket, string $action, array $data = []): void
    {
        TicketAuditLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'old_values' => $data['old'] ?? null,
            'new_values' => $data['new'] ?? null,
            'notes' => $data['reason'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
