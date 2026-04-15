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
     * Assign ticket to user
     */
    public function assignTicket(Ticket $ticket, ?int $assigneeId, User $assignedBy): Ticket
    {
        $oldAssignee = $ticket->assignee_id;

        $this->tickets->update($ticket, [
            'assignee_id' => $assigneeId,
        ]);

        // Log assignment
        if ($assigneeId) {
            $assignee = User::find($assigneeId);
            $this->logAudit($ticket, 'assigned', [
                'old_assignee_id' => $oldAssignee,
                'new_assignee_id' => $assigneeId,
                'assignee_name' => $assignee?->name,
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
        return $ticket->comments()->create([
            'user_id' => $user->id,
            'comment' => $data['comment'],
            'is_internal' => $data['is_internal'] ?? false,
            'is_solution' => $data['is_solution'] ?? false,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
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
