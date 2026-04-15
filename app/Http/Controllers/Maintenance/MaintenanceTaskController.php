<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceTask;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceChecklistItem;
use App\Models\MaintenancePhoto;
use App\Models\Asset;
use App\Models\User;
use App\Models\Vendor;
use App\Models\InventoryPart;
use App\Services\MaintenanceService;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class MaintenanceTaskController extends Controller
{
    public function __construct(
        private MaintenanceService $maintenanceService,
        private InventoryService $inventoryService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', MaintenanceTask::class);

        $query = MaintenanceTask::with(['asset', 'assignedUser', 'vendor', 'schedule'])
            ->orderBy('scheduled_date', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by asset
        if ($request->filled('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        // IT Staff only sees their assigned tasks
        if (auth()->user()->hasRole('it_staff') && !auth()->user()->hasPermission('view-all-tickets')) {
            $query->where('assigned_to_user_id', auth()->id());
        }

        $tasks = $query->paginate(15);
        $assets = Asset::orderBy('name')->get();

        return view('maintenance.tasks.index', compact('tasks', 'assets'));
    }

    public function create()
    {
        $this->authorize('create', MaintenanceTask::class);

        $assets = Asset::orderBy('name')->get();
        // Only IT Manager and IT Staff can be assigned to tasks
        $users = User::whereHas('role', function($q) {
            $q->whereIn('name', ['it_manager', 'it_staff']);
        })->orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();
        $schedules = MaintenanceSchedule::where('is_active', true)->get();

        return view('maintenance.tasks.create', compact('assets', 'users', 'vendors', 'schedules'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', MaintenanceTask::class);

        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'schedule_id' => ['nullable', 'exists:maintenance_schedules,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'maintenance_type' => ['required', 'string'],
            'priority' => ['required', 'string'],
            'assigned_to_user_id' => ['nullable', 'exists:users,id'],
            'assigned_to_user_ids' => ['nullable', 'array'],
            'assigned_to_user_ids.*' => ['exists:users,id'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'scheduled_date' => ['required', 'date'],
            'estimated_cost' => ['nullable', 'numeric'],
        ]);

        $validated['task_number'] = 'MNT-' . str_pad(MaintenanceTask::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);
        
        // Store the array of assigned user IDs
        if (!empty($validated['assigned_to_user_ids'])) {
            $validated['assigned_to_user_ids'] = array_map('intval', $validated['assigned_to_user_ids']);
            // Set the first user as primary assignee for backward compatibility
            $validated['assigned_to_user_id'] = $validated['assigned_to_user_ids'][0] ?? null;
        }

        $task = MaintenanceTask::create($validated);

        return redirect()
            ->route('maintenance.tasks.show', $task)
            ->with('success', 'Maintenance task created successfully.');
    }

    public function show(MaintenanceTask $task)
    {
        $this->authorize('view', $task);

        $task->load(['asset', 'assignedUser', 'vendor', 'schedule', 'checklistResults', 'photos', 'evaluations', 'requirements']);

        return view('maintenance.tasks.show', compact('task'));
    }

    /**
     * Execute a maintenance task (checklist, photos, parts)
     */
    public function execute(MaintenanceTask $task)
    {
        $this->authorize('execute', $task);

        if ($task->status === 'pending' || $task->status === 'scheduled') {
            $this->maintenanceService->startTask($task);
        }

        // Get checklist items from schedule or empty array
        $checklistItems = $task->schedule
            ? MaintenanceChecklistItem::where('schedule_id', $task->schedule_id)->orderBy('order_index')->get()
            : collect();

        $parts = InventoryPart::where('is_active', true)->orderBy('name')->get();

        return view('maintenance.tasks.execute', compact('task', 'checklistItems', 'parts'));
    }

    /**
     * Save execution data
     */
    public function saveExecution(Request $request, MaintenanceTask $task)
    {
        $this->authorize('execute', $task);

        // Save checklist results
        if ($request->filled('checklist')) {
            $this->maintenanceService->saveChecklistResults(
                $task,
                $request->input('checklist'),
                auth()->user()
            );
        }

        // Save requirements
        if ($request->filled('requirements')) {
            $this->maintenanceService->saveRequirements(
                $task,
                $request->input('requirements')
            );
        }

        // Complete task
        $this->maintenanceService->completeTask(
            $task,
            $request->input('resolution_notes')
        );

        return redirect()
            ->route('maintenance.tasks.show', $task)
            ->with('success', 'Maintenance task completed successfully.');
    }

    /**
     * Upload photo documentation
     */
    public function uploadPhoto(Request $request, MaintenanceTask $task)
    {
        $this->authorize('execute', $task);

        $validated = $request->validate([
            'photos.*' => ['required', 'image', 'max:5120'],
            'photo_type' => ['required', 'string'],
            'captions.*' => ['nullable', 'string'],
        ]);

        $uploaded = [];
        foreach ($request->file('photos') as $index => $file) {
            $photo = $this->maintenanceService->uploadPhoto(
                $task,
                $file,
                $request->input('photo_type'),
                auth()->user()
            );
            $uploaded[] = $photo->original_filename;
        }

        return redirect()
            ->route('maintenance.tasks.execute', $task)
            ->with('success', count($uploaded) . ' photos uploaded successfully.');
    }

    /**
     * Show approval form
     */
    public function requestApproval(MaintenanceTask $task)
    {
        $this->authorize('approve', $task);

        return view('maintenance.tasks.approval', compact('task'));
    }

    /**
     * Approve task
     */
    public function approve(Request $request, MaintenanceTask $task)
    {
        $this->authorize('approve', $task);

        $validated = $request->validate([
            'comments' => ['nullable', 'string'],
        ]);

        $this->maintenanceService->approveTask($task, auth()->user(), $validated['comments'] ?? null);

        return redirect()
            ->route('maintenance.tasks.show', $task)
            ->with('success', 'Maintenance task approved.');
    }

    /**
     * Reject task approval
     */
    public function reject(Request $request, MaintenanceTask $task)
    {
        $this->authorize('approve', $task);

        $validated = $request->validate([
            'comments' => ['required', 'string'],
        ]);

        $this->maintenanceService->rejectTask($task, auth()->user(), $validated['comments']);

        return redirect()
            ->route('maintenance.tasks.show', $task)
            ->with('error', 'Maintenance task approval rejected.');
    }

    public function destroy(MaintenanceTask $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('maintenance.tasks.index')
            ->with('success', 'Maintenance task deleted successfully.');
    }
}
