<?php

namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TicketRepository
{
    /**
     * Get paginated tickets with filters
     */
    public function getPaginated(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Ticket::with(['user', 'assignee', 'department', 'category', 'slaPolicy']);

        // Apply filters
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority']) && $filters['priority']) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['category_id']) && $filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['department_id']) && $filters['department_id']) {
            $query->where('department_id', $filters['department_id']);
        }

        if (isset($filters['assignee_id']) && $filters['assignee_id']) {
            $query->where('assignee_id', $filters['assignee_id']);
        }

        if (isset($filters['user_id']) && $filters['user_id']) {
            $query->where('user_id', $filters['user_id']);
        }

        // IT Staff can see tickets assigned to them OR tickets they created
        if (isset($filters['created_by_user']) && isset($filters['assignee_id'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('assignee_id', $filters['assignee_id'])
                  ->orWhere('user_id', $filters['created_by_user']);
            });
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ticket_number', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find ticket by ID
     */
    public function findById(int $id): ?Ticket
    {
        return Ticket::with([
            'user',
            'assignee',
            'department',
            'category',
            'slaPolicy',
            'comments.user',
            'attachments.uploadedBy',
            'auditLogs.user',
        ])->find($id);
    }

    /**
     * Find ticket by ticket number
     */
    public function findByNumber(string $number): ?Ticket
    {
        return Ticket::where('ticket_number', $number)->first();
    }

    /**
     * Create new ticket
     */
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    /**
     * Update ticket
     */
    public function update(Ticket $ticket, array $data): bool
    {
        return $ticket->update($data);
    }

    /**
     * Delete ticket (soft delete)
     */
    public function delete(Ticket $ticket): bool
    {
        return $ticket->delete();
    }

    /**
     * Get tickets by status
     */
    public function getByStatus(string $status, int $limit = 10): Collection
    {
        return Ticket::with(['user', 'assignee'])
            ->where('status', $status)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get overdue tickets
     */
    public function getOverdue(): Collection
    {
        return Ticket::with(['user', 'assignee'])
            ->where('sla_deadline', '<', now())
            ->whereNotIn('status', ['closed', 'resolved'])
            ->latest()
            ->get();
    }

    /**
     * Get tickets assigned to user
     */
    public function getAssignedTo(int $userId, int $limit = 10): Collection
    {
        return Ticket::with(['user', 'category'])
            ->where('assignee_id', $userId)
            ->whereNotIn('status', ['closed'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get tickets created by user
     */
    public function getCreatedBy(int $userId, int $limit = 10): Collection
    {
        return Ticket::with(['assignee', 'category'])
            ->where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get ticket count by status
     */
    public function getCountByStatus(): array
    {
        return Ticket::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get ticket count by priority
     */
    public function getCountByPriority(): array
    {
        return Ticket::selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
    }

    /**
     * Generate next ticket number
     */
    public function generateTicketNumber(): string
    {
        $year = now()->year;
        $lastTicket = Ticket::withTrashed()
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastTicket ? (int) substr($lastTicket->ticket_number, -4) + 1 : 1;

        return 'TKT-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
