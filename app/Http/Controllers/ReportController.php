<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\KbArticle;
use App\Models\Department;
use App\Models\TicketCategory;
use App\Models\User;
use App\Models\ReportRun;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    /**
     * Display report selection page
     */
    public function index()
    {
        $this->authorize('view-reports', Ticket::class);

        $availableReports = $this->reportService->getAvailableReports();
        $filterOptions = $this->reportService->getReportFilterOptions();
        $recentReports = ReportRun::with('generatedBy')
            ->latest()
            ->take(10)
            ->get();

        return view('reports.index', compact('availableReports', 'filterOptions', 'recentReports'));
    }

    /**
     * Generate and display ticket report
     */
    public function generateTicketReport(Request $request)
    {
        $this->authorize('view-reports', Ticket::class);

        $filters = $request->validate([
            'status' => ['nullable', 'string', 'in:open,in_progress,resolved,closed,reopened'],
            'priority' => ['nullable', 'string', 'in:critical,high,medium,low'],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'sla_breached' => ['nullable', 'boolean'],
            'report_type' => ['required', 'string', 'in:summary,by_status,by_priority,sla_compliance,staff_performance'],
        ]);

        $startTime = microtime(true);

        $reportData = match ($filters['report_type']) {
            'summary' => $this->reportService->getTicketSummary($filters),
            'sla_compliance' => $this->reportService->generateSlaComplianceReport($filters),
            'staff_performance' => $this->reportService->generateStaffPerformanceReport($filters),
            default => [
                'tickets' => $this->reportService->generateTicketReport($filters),
                'summary' => $this->reportService->getTicketSummary($filters),
            ],
        };

        $generationTime = round((microtime(true) - $startTime) * 1000);

        // Log report run
        $this->reportService->logReportRun(
            'ticket_' . $filters['report_type'],
            $filters,
            'view',
            $generationTime
        );

        $filterOptions = $this->reportService->getReportFilterOptions();

        return view('reports.tickets', compact('reportData', 'filters', 'filterOptions', 'generationTime'));
    }

    /**
     * Generate and display asset report
     */
    public function generateAssetReport(Request $request)
    {
        $this->authorize('view-reports', Asset::class);

        $filters = $request->validate([
            'status' => ['nullable', 'string', 'in:procurement,inventory,deployed,maintenance,retired,disposed'],
            'asset_type' => ['nullable', 'string', 'in:hardware,software,network'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'warranty_expiring' => ['nullable', 'boolean'],
            'warranty_expiring_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'report_type' => ['required', 'string', 'in:summary,inventory,warranty_expiry'],
        ]);

        $startTime = microtime(true);

        $reportData = match ($filters['report_type']) {
            'summary' => $this->reportService->getAssetSummary($filters),
            'inventory' => $this->reportService->generateAssetReport($filters),
            'warranty_expiry' => [
                'assets' => $this->reportService->generateAssetReport(array_merge($filters, ['warranty_expiring' => true])),
                'summary' => $this->reportService->getAssetSummary($filters),
            ],
            default => [
                'assets' => $this->reportService->generateAssetReport($filters),
                'summary' => $this->reportService->getAssetSummary($filters),
            ],
        };

        $generationTime = round((microtime(true) - $startTime) * 1000);

        $this->reportService->logReportRun(
            'asset_' . $filters['report_type'],
            $filters,
            'view',
            $generationTime
        );

        $filterOptions = $this->reportService->getReportFilterOptions();

        return view('reports.assets', compact('reportData', 'filters', 'filterOptions', 'generationTime'));
    }

    /**
     * Generate and display KB report
     */
    public function generateKbReport(Request $request)
    {
        $this->authorize('view-reports', KbArticle::class);

        $filters = $request->validate([
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'category_id' => ['nullable', 'exists:kb_categories,id'],
            'is_internal' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'report_type' => ['required', 'string', 'in:summary,most_viewed,negative_feedback'],
        ]);

        $startTime = microtime(true);

        $reportData = match ($filters['report_type']) {
            'summary' => $this->reportService->getKbSummary($filters),
            'most_viewed' => $this->reportService->generateKbReport($filters),
            'negative_feedback' => $this->reportService->getKbSummary($filters),
            default => [
                'articles' => $this->reportService->generateKbReport($filters),
                'summary' => $this->reportService->getKbSummary($filters),
            ],
        };

        $generationTime = round((microtime(true) - $startTime) * 1000);

        $this->reportService->logReportRun(
            'kb_' . $filters['report_type'],
            $filters,
            'view',
            $generationTime
        );

        $filterOptions = $this->reportService->getReportFilterOptions();

        return view('reports.kb', compact('reportData', 'filters', 'filterOptions', 'generationTime'));
    }
}
