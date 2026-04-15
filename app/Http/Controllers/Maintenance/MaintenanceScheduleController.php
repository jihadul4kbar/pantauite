<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\Asset;
use App\Models\User;
use App\Models\Vendor;
use App\Services\MaintenanceService;
use Illuminate\Http\Request;

class MaintenanceScheduleController extends Controller
{
    public function __construct(
        private MaintenanceService $maintenanceService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', MaintenanceSchedule::class);

        $query = MaintenanceSchedule::with(['asset', 'assignedUser', 'vendor'])
            ->orderBy('next_due_date');

        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $schedules = $query->paginate(15);
        $assets = Asset::orderBy('name')->get();

        return view('maintenance.schedules.index', compact('schedules', 'assets'));
    }

    public function create()
    {
        $this->authorize('create', MaintenanceSchedule::class);

        $assets = Asset::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();

        return view('maintenance.schedules.create', compact('assets', 'users', 'vendors'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', MaintenanceSchedule::class);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'maintenance_type' => ['required', 'string'],
            'frequency_type' => ['required', 'string'],
            'frequency_value' => ['required', 'integer', 'min:1'],
            'next_due_date' => ['required', 'date'],
            'estimated_duration_minutes' => ['nullable', 'integer'],
            'estimated_cost' => ['nullable', 'numeric'],
            'approval_threshold' => ['nullable', 'numeric'],
            'assigned_to_user_id' => ['nullable', 'exists:users,id'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        MaintenanceSchedule::create($validated);

        return redirect()
            ->route('maintenance.schedules.index')
            ->with('success', 'Maintenance schedule created successfully.');
    }

    public function show(MaintenanceSchedule $schedule)
    {
        $this->authorize('view', $schedule);

        $schedule->load(['asset', 'assignedUser', 'vendor', 'tasks' => function($q) {
            $q->latest()->limit(10);
        }]);

        return view('maintenance.schedules.show', compact('schedule'));
    }

    public function edit(MaintenanceSchedule $schedule)
    {
        $this->authorize('update', $schedule);

        $assets = Asset::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();

        return view('maintenance.schedules.edit', compact('schedule', 'assets', 'users', 'vendors'));
    }

    public function update(Request $request, MaintenanceSchedule $schedule)
    {
        $this->authorize('update', $schedule);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'maintenance_type' => ['required', 'string'],
            'frequency_type' => ['required', 'string'],
            'frequency_value' => ['required', 'integer', 'min:1'],
            'next_due_date' => ['required', 'date'],
            'estimated_duration_minutes' => ['nullable', 'integer'],
            'estimated_cost' => ['nullable', 'numeric'],
            'approval_threshold' => ['nullable', 'numeric'],
            'assigned_to_user_id' => ['nullable', 'exists:users,id'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $schedule->update($validated);

        return redirect()
            ->route('maintenance.schedules.index')
            ->with('success', 'Maintenance schedule updated successfully.');
    }

    public function destroy(MaintenanceSchedule $schedule)
    {
        $this->authorize('delete', $schedule);

        $schedule->delete();

        return redirect()
            ->route('maintenance.schedules.index')
            ->with('success', 'Maintenance schedule deleted successfully.');
    }

    /**
     * Manually trigger task generation from schedules
     */
    public function generateTasks()
    {
        $this->authorize('create', MaintenanceSchedule::class);

        $count = $this->maintenanceService->generateTasksFromSchedules();

        return redirect()
            ->route('maintenance.schedules.index')
            ->with('success', "{$count} maintenance tasks generated from schedules.");
    }
}
