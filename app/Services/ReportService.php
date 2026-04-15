<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\KbArticle;
use App\Models\ReportRun;
use App\Models\User;
use App\Models\Department;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

/**
 * ReportService untuk generate berbagai jenis laporan
 */
class ReportService
{
    /**
     * Generate ticket report dengan berbagai filter
     */
    public function generateTicketReport(array $filters = []): Collection
    {
        $query = Ticket::with(['user', 'assignee', 'department', 'category', 'slaPolicy'])
            ->whereNull('deleted_at');

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }
        if (!empty($filters['assignee_id'])) {
            $query->where('assignee_id', $filters['assignee_id']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        if (!empty($filters['sla_breached'])) {
            $query->where('sla_breached', true);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Generate ticket summary statistics
     */
    public function getTicketSummary(array $filters = []): array
    {
        $query = Ticket::whereNull('deleted_at');

        // Apply date filters
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $tickets = $query->get();

        return [
            'total' => $tickets->count(),
            'by_status' => $tickets->groupBy('status')->map->count(),
            'by_priority' => $tickets->groupBy('priority')->map->count(),
            'by_category' => $tickets->groupBy('category.name')->map->count(),
            'by_department' => $tickets->groupBy('department.name')->map->count(),
            'sla_compliance' => $this->calculateSlaCompliance($tickets),
            'avg_resolution_time' => $this->calculateAvgResolutionTime($tickets),
        ];
    }

    /**
     * Generate staff performance report
     */
    public function generateStaffPerformanceReport(array $filters = []): Collection
    {
        $query = Ticket::with(['assignee'])
            ->whereNotNull('assignee_id')
            ->whereNull('deleted_at');

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $tickets = $query->get()->groupBy('assignee_id');

        return $tickets->map(function ($staffTickets, $assigneeId) {
            $assignee = $staffTickets->first()->assignee;
            $resolved = $staffTickets->whereIn('status', ['resolved', 'closed'])->count();
            $total = $staffTickets->count();
            $breached = $staffTickets->where('sla_breached', true)->count();

            $resolutionTimes = $staffTickets
                ->whereNotNull('resolved_at')
                ->map(fn($t) => $t->created_at->diffInHours($t->resolved_at));

            return [
                'assignee' => $assignee,
                'total_tickets' => $total,
                'resolved_tickets' => $resolved,
                'resolution_rate' => $total > 0 ? round(($resolved / $total) * 100, 2) : 0,
                'sla_breached' => $breached,
                'sla_compliance' => $total > 0 ? round((($total - $breached) / $total) * 100, 2) : 100,
                'avg_resolution_hours' => $resolutionTimes->count() > 0 ? round($resolutionTimes->avg(), 2) : null,
            ];
        })->sortByDesc('total_tickets')->values();
    }

    /**
     * Generate SLA compliance report
     */
    public function generateSlaComplianceReport(array $filters = []): array
    {
        $query = Ticket::whereNull('deleted_at');

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $tickets = $query->with('slaPolicy')->get();

        $byPriority = $tickets->groupBy('priority')->map(function ($priorityTickets) {
            $total = $priorityTickets->count();
            $breached = $priorityTickets->where('sla_breached', true)->count();
            return [
                'total' => $total,
                'breached' => $breached,
                'compliant' => $total - $breached,
                'compliance_rate' => $total > 0 ? round((($total - $breached) / $total) * 100, 2) : 100,
            ];
        });

        return [
            'summary' => [
                'total' => $tickets->count(),
                'breached' => $tickets->where('sla_breached', true)->count(),
                'compliant' => $tickets->where('sla_breached', false)->count(),
                'overall_compliance' => $this->calculateSlaCompliance($tickets),
            ],
            'by_priority' => $byPriority,
            'breached_tickets' => $tickets->where('sla_breached', true)->take(50),
        ];
    }

    /**
     * Generate asset inventory report
     */
    public function generateAssetReport(array $filters = []): Collection
    {
        $query = Asset::with(['assignedUser', 'assignedDepartment', 'vendor'])
            ->whereNull('deleted_at');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['asset_type'])) {
            $query->where('asset_type', $filters['asset_type']);
        }
        if (!empty($filters['department_id'])) {
            $query->where('assigned_to_department_id', $filters['department_id']);
        }
        if (!empty($filters['vendor_id'])) {
            $query->where('vendor_id', $filters['vendor_id']);
        }
        if (!empty($filters['warranty_expiring'])) {
            $days = $filters['warranty_expiring_days'] ?? 30;
            $query->whereBetween('warranty_end', [now(), now()->addDays($days)]);
        }

        return $query->orderBy('asset_code')->get();
    }

    /**
     * Generate asset summary statistics
     */
    public function getAssetSummary(array $filters = []): array
    {
        $query = Asset::whereNull('deleted_at');

        $assets = $query->get();

        return [
            'total' => $assets->count(),
            'by_status' => $assets->groupBy('status')->map->count(),
            'by_type' => $assets->groupBy('asset_type')->map->count(),
            'by_condition' => $assets->groupBy('condition')->map->count(),
            'deployed' => $assets->where('status', 'deployed')->count(),
            'in_maintenance' => $assets->where('status', 'maintenance')->count(),
            'warranty_expiring_30' => $assets->filter(fn($a) => $a->warranty_end && $a->warranty_end->between(now(), now()->addDays(30)))->count(),
            'warranty_expiring_60' => $assets->filter(fn($a) => $a->warranty_end && $a->warranty_end->between(now(), now()->addDays(60)))->count(),
            'warranty_expiring_90' => $assets->filter(fn($a) => $a->warranty_end && $a->warranty_end->between(now(), now()->addDays(90)))->count(),
            'total_value' => $assets->sum('price'),
            'depreciated_value' => $assets->sum('depreciated_value'),
        ];
    }

    /**
     * Generate knowledge base report
     */
    public function generateKbReport(array $filters = []): Collection
    {
        $query = KbArticle::with(['category', 'author'])
            ->whereNull('deleted_at');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['is_internal'])) {
            $query->where('is_internal', $filters['is_internal']);
        }
        if (!empty($filters['is_featured'])) {
            $query->where('is_featured', true);
        }

        return $query->orderBy('views', 'desc')->get();
    }

    /**
     * Generate KB summary statistics
     */
    public function getKbSummary(array $filters = []): array
    {
        $query = KbArticle::whereNull('deleted_at');

        $articles = $query->get();

        $negativeFeedback = $articles->filter(function ($article) {
            $total = $article->helpful_votes + $article->not_helpful_votes;
            return $total > 0 && ($article->helpful_votes / $total) < 0.5;
        });

        return [
            'total' => $articles->count(),
            'by_status' => $articles->groupBy('status')->map->count(),
            'by_category' => $articles->groupBy('category.name')->map->count(),
            'published' => $articles->where('status', 'published')->count(),
            'draft' => $articles->where('status', 'draft')->count(),
            'archived' => $articles->where('status', 'archived')->count(),
            'total_views' => $articles->sum('views'),
            'total_helpful_votes' => $articles->sum('helpful_votes'),
            'total_not_helpful_votes' => $articles->sum('not_helpful_votes'),
            'most_viewed' => $articles->sortByDesc('views')->take(10),
            'negative_feedback' => $negativeFeedback->take(20),
        ];
    }

    /**
     * Log report generation
     */
    public function logReportRun(string $reportType, array $filters, string $format, ?int $generationTimeMs = null): ReportRun
    {
        return ReportRun::create([
            'report_type' => $reportType,
            'filters' => $filters,
            'format' => $format,
            'generated_by' => auth()->id(),
            'generation_time_ms' => $generationTimeMs,
        ]);
    }

    /**
     * Calculate SLA compliance rate
     */
    protected function calculateSlaCompliance(Collection $tickets): float
    {
        $total = $tickets->count();
        if ($total === 0) return 100.0;

        $breached = $tickets->where('sla_breached', true)->count();
        return round((($total - $breached) / $total) * 100, 2);
    }

    /**
     * Calculate average resolution time in hours
     */
    protected function calculateAvgResolutionTime(Collection $tickets): ?float
    {
        $resolvedTickets = $tickets->whereNotNull('resolved_at');

        if ($resolvedTickets->count() === 0) return null;

        $resolutionTimes = $resolvedTickets->map(function ($ticket) {
            return $ticket->created_at->diffInHours($ticket->resolved_at);
        });

        return round($resolutionTimes->avg(), 2);
    }

    /**
     * Get report types dengan metadata
     */
    public function getAvailableReports(): array
    {
        return [
            'tickets' => [
                'name' => 'Ticket Reports',
                'icon' => '🎫',
                'description' => 'Comprehensive ticket analytics dan statistics',
                'reports' => [
                    'ticket_summary' => 'Ticket Summary',
                    'ticket_by_status' => 'Tickets by Status',
                    'ticket_by_priority' => 'Tickets by Priority',
                    'sla_compliance' => 'SLA Compliance Report',
                    'staff_performance' => 'Staff Performance Report',
                ],
            ],
            'assets' => [
                'name' => 'Asset Reports',
                'icon' => '🖥️',
                'description' => 'Asset inventory, warranty, dan depreciation reports',
                'reports' => [
                    'asset_inventory' => 'Asset Inventory List',
                    'asset_by_status' => 'Assets by Status',
                    'asset_by_type' => 'Assets by Type',
                    'warranty_expiry' => 'Warranty Expiry Report',
                    'depreciation' => 'Depreciation Report',
                ],
            ],
            'kb' => [
                'name' => 'Knowledge Base Reports',
                'icon' => '📚',
                'description' => 'KB article analytics dan feedback reports',
                'reports' => [
                    'kb_summary' => 'KB Summary',
                    'kb_by_status' => 'Articles by Status',
                    'most_viewed' => 'Most Viewed Articles',
                    'negative_feedback' => 'Articles with Negative Feedback',
                ],
            ],
        ];
    }

    /**
     * Get filter options untuk reports
     */
    public function getReportFilterOptions(): array
    {
        return [
            'departments' => Department::where('is_active', true)->orderBy('name')->get(),
            'categories' => TicketCategory::where('is_active', true)->orderBy('name')->get(),
            'users' => User::with('role')->where('status', 'active')->orderBy('name')->get(),
        ];
    }
}
