<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\KbArticle;
use App\Models\Asset;
use App\Models\RepairRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Cache stats for 5 minutes to improve performance
        $stats = Cache::remember('dashboard_stats', 300, function () use ($user) {
            $ticketStats = $this->getTicketStats();
            $kbStats = $this->getKbStats();
            $assetStats = $this->getAssetStats();
            $slaStats = $this->getSlaStats();
            $repairStats = $this->getRepairRequestStats();

            return [
                'tickets' => $ticketStats,
                'kb' => $kbStats,
                'assets' => $assetStats,
                'sla' => $slaStats,
                'repair_requests' => $repairStats,
            ];
        });

        // Chart data (cache for 5 minutes)
        $chartData = Cache::remember('dashboard_charts', 300, function () use ($user) {
            return $this->getChartData();
        });

        return view('dashboard', [
            'stats' => $stats,
            'chartData' => $chartData,
        ]);
    }

    private function getTicketStats()
    {
        $user = auth()->user();

        // Base query depends on user role
        $query = Ticket::query();

        // End users only see their own tickets
        if ($user->hasRole('end_user')) {
            $query->where('user_id', $user->id);
        }

        // IT Staff see tickets assigned to them OR tickets they created
        if ($user->hasRole('it_staff')) {
            $query->where(function ($q) use ($user) {
                $q->where('assignee_id', $user->id)
                  ->orWhere('user_id', $user->id);
            });
        }

        $total = $query->count();
        $open = (clone $query)->where('status', 'open')->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $resolved = (clone $query)->where('status', 'resolved')->count();
        $overdue = (clone $query)
            ->whereNotNull('sla_deadline')
            ->where('sla_deadline', '<', now())
            ->whereNotIn('status', ['resolved', 'closed'])
            ->count();

        return [
            'total' => $total,
            'open' => $open,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'overdue' => $overdue,
        ];
    }

    private function getKbStats()
    {
        $total = KbArticle::count();
        $published = KbArticle::published()->count();
        $draft = KbArticle::draft()->count();
        $recent = KbArticle::published()
            ->where('published_at', '>=', now()->subDays(7))
            ->count();

        return [
            'total' => $total,
            'published' => $published,
            'draft' => $draft,
            'recent' => $recent,
        ];
    }

    private function getAssetStats()
    {
        // Only count assets user can view
        if (!auth()->user()->can('view-assets')) {
            return ['total' => 0, 'deployed' => 0, 'maintenance' => 0];
        }

        $total = Asset::count();
        $deployed = Asset::where('status', 'deployed')->count();
        $maintenance = Asset::where('status', 'maintenance')->count();

        return [
            'total' => $total,
            'deployed' => $deployed,
            'maintenance' => $maintenance,
        ];
    }

    private function getSlaStats()
    {
        $user = auth()->user();

        $query = Ticket::query()
            ->whereNotNull('sla_deadline')
            ->whereNotIn('status', ['closed']);

        // End users only see their own tickets
        if ($user->hasRole('end_user')) {
            $query->where('user_id', $user->id);
        }

        // IT Staff see tickets assigned to them OR tickets they created
        if ($user->hasRole('it_staff')) {
            $query->where(function ($q) use ($user) {
                $q->where('assignee_id', $user->id)
                  ->orWhere('user_id', $user->id);
            });
        }

        $total = $query->count();
        
        if ($total === 0) {
            return ['compliance' => 100, 'breached' => 0, 'on_track' => 0];
        }

        $breached = (clone $query)
            ->where('sla_deadline', '<', now())
            ->count();

        $onTrack = $total - $breached;
        $compliance = round(($onTrack / $total) * 100, 1);

        return [
            'compliance' => $compliance,
            'breached' => $breached,
            'on_track' => $onTrack,
        ];
    }

    private function getRepairRequestStats()
    {
        // Only IT Manager and Super Admin can see repair request stats
        if (!auth()->user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets'])) {
            return ['pending' => 0, 'approved' => 0, 'rejected' => 0, 'converted' => 0];
        }

        $pending = RepairRequest::submitted()->count();
        $approved = RepairRequest::approved()->count();
        $rejected = RepairRequest::rejected()->count();
        $converted = RepairRequest::converted()->count();

        return [
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'converted' => $converted,
        ];
    }

    private function getChartData()
    {
        $user = auth()->user();

        // Base query depends on user role
        $query = Ticket::query();

        if ($user->hasRole('end_user')) {
            $query->where('user_id', $user->id);
        }

        if ($user->hasRole('it_staff')) {
            $query->where(function ($q) use ($user) {
                $q->where('assignee_id', $user->id)
                  ->orWhere('user_id', $user->id);
            });
        }

        try {
            // Priority Chart Data
            $priorityData = [
                'critical' => (clone $query)->where('priority', 'critical')->count(),
                'high' => (clone $query)->where('priority', 'high')->count(),
                'medium' => (clone $query)->where('priority', 'medium')->count(),
                'low' => (clone $query)->where('priority', 'low')->count(),
            ];

            // Category Chart Data - Simplified query
            $categoryData = \App\Models\TicketCategory::select('ticket_categories.name', \DB::raw('COUNT(tickets.id) as count'))
                ->leftJoin('tickets', 'ticket_categories.id', '=', 'tickets.category_id')
                ->whereNull('ticket_categories.deleted_at')
                ->groupBy('ticket_categories.id', 'ticket_categories.name')
                ->orderByDesc('count')
                ->limit(5)
                ->get()
                ->pluck('count', 'name');

            // SLA Timeline Data (last 7 days)
            $slaTimeline = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->copy()->subDays($i);
                $dateStr = $date->format('d M');
                
                $slaQuery = Ticket::whereNotNull('sla_deadline')
                    ->whereDate('created_at', $date);
                    
                if ($user->hasRole('end_user')) {
                    $slaQuery->where('user_id', $user->id);
                }
                
                if ($user->hasRole('it_staff')) {
                    $slaQuery->where(function ($q) use ($user) {
                        $q->where('assignee_id', $user->id)
                          ->orWhere('user_id', $user->id);
                    });
                }
                
                $total = $slaQuery->count();
                $breached = (clone $slaQuery)->where('sla_deadline', '<', now())->count();
                $met = max(0, $total - $breached);
                
                $slaTimeline[$dateStr] = [
                    'met' => $met,
                    'breached' => $breached,
                ];
            }

            return [
                'priority' => $priorityData,
                'category' => $categoryData,
                'sla_timeline' => $slaTimeline,
            ];
        } catch (\Exception $e) {
            // Return empty data on error to prevent breaking the view
            \Log::error('Chart data error: ' . $e->getMessage());
            return [
                'priority' => ['critical' => 0, 'high' => 0, 'medium' => 0, 'low' => 0],
                'category' => [],
                'sla_timeline' => [],
            ];
        }
    }
}
