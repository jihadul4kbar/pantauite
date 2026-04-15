<?php

namespace App\Services;

use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceTask;
use App\Models\MaintenanceChecklistItem;
use App\Models\MaintenanceChecklistResult;
use App\Models\MaintenanceRequirement;
use App\Models\MaintenancePhoto;
use App\Models\MaintenanceEvaluation;
use App\Models\MaintenanceApproval;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaintenanceService
{
    /**
     * Generate tasks from schedules due within the next X days
     */
    public function generateTasksFromSchedules(int $daysAhead = 7): int
    {
        $schedules = MaintenanceSchedule::where('is_active', true)
            ->where('next_due_date', '<=', now()->addDays($daysAhead))
            ->where(function($q) {
                $q->whereNull('last_completed_date')
                  ->orWhere('last_completed_date', '<', now()->subDays(1));
            })
            ->get();

        $createdCount = 0;

        foreach ($schedules as $schedule) {
            // Check if a task already exists for this schedule and date
            $exists = MaintenanceTask::where('schedule_id', $schedule->id)
                ->whereDate('scheduled_date', $schedule->next_due_date)
                ->exists();

            if (!$exists) {
                MaintenanceTask::create([
                    'task_number' => $this->generateTaskNumber(),
                    'schedule_id' => $schedule->id,
                    'asset_id' => $schedule->asset_id,
                    'title' => "Scheduled: {$schedule->name}",
                    'description' => $schedule->description,
                    'maintenance_type' => $schedule->maintenance_type,
                    'priority' => 'medium',
                    'status' => 'scheduled',
                    'assigned_to_user_id' => $schedule->assigned_to_user_id,
                    'vendor_id' => $schedule->vendor_id,
                    'scheduled_date' => $schedule->next_due_date,
                    'estimated_cost' => $schedule->estimated_cost,
                ]);

                $createdCount++;
                Log::info("Maintenance task created from schedule: {$schedule->name}");
            }
        }

        return $createdCount;
    }

    /**
     * Start a maintenance task
     */
    public function startTask(MaintenanceTask $task): MaintenanceTask
    {
        return tap($task)->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    /**
     * Complete a maintenance task
     */
    public function completeTask(MaintenanceTask $task, string $resolutionNotes = null): MaintenanceTask
    {
        return tap($task)->update([
            'status' => 'completed',
            'completed_at' => now(),
            'actual_duration_minutes' => $task->started_at ? now()->diffInMinutes($task->started_at) : null,
            'resolution_notes' => $resolutionNotes,
        ]);
    }

    /**
     * Save checklist results for a task
     */
    public function saveChecklistResults(MaintenanceTask $task, array $items, User $user): void
    {
        foreach ($items as $itemName => $data) {
            MaintenanceChecklistResult::updateOrCreate(
                [
                    'task_id' => $task->id,
                    'item_name' => $itemName,
                ],
                [
                    'description' => $data['description'] ?? null,
                    'status' => $data['status'], // pass/fail/na
                    'notes' => $data['notes'] ?? null,
                    'photo_path' => $data['photo_path'] ?? null,
                    'checked_at' => now(),
                    'checked_by_user_id' => $user->id,
                ]
            );
        }
    }

    /**
     * Save requirements (parts/materials) for a task
     */
    public function saveRequirements(MaintenanceTask $task, array $requirements): void
    {
        foreach ($requirements as $req) {
            // Skip empty entries
            if (empty($req['part_name'])) {
                continue;
            }

            $requirement = MaintenanceRequirement::create([
                'task_id' => $task->id,
                'part_name' => $req['part_name'],
                'quantity' => $req['quantity'] ?? 1,
                'unit' => $req['unit'] ?? 'pcs',
                'unit_cost' => $req['unit_cost'] ?? 0,
                'total_cost' => ($req['quantity'] ?? 1) * ($req['unit_cost'] ?? 0),
                'part_id' => $req['part_id'] ?? null,
                'is_consumable' => $req['is_consumable'] ?? false,
                'stock_used' => $req['stock_used'] ?? null,
            ]);

            // If part_id exists, update inventory
            if ($requirement->part_id && $requirement->stock_used) {
                app(InventoryService::class)->stockOut(
                    $requirement->part_id,
                    $requirement->stock_used,
                    $task->id,
                    MaintenanceTask::class,
                    $task->assignedUser
                );
            }
        }

        // Recalculate actual cost
        $totalCost = $task->requirements()->sum('total_cost');
        $task->update(['actual_cost' => $totalCost]);
    }

    /**
     * Upload photo documentation for a task
     */
    public function uploadPhoto(MaintenanceTask $task, $file, string $type, User $user): MaintenancePhoto
    {
        $path = $file->store('maintenance/' . $task->id, 'public');

        return MaintenancePhoto::create([
            'task_id' => $task->id,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'photo_type' => $type, // before/after/during/evidence
            'uploaded_by_user_id' => $user->id,
        ]);
    }

    /**
     * Create evaluation for a completed task
     */
    public function evaluateTask(MaintenanceTask $task, array $data, User $user): MaintenanceEvaluation
    {
        return MaintenanceEvaluation::create([
            'task_id' => $task->id,
            'evaluated_by_user_id' => $user->id,
            'evaluation_date' => now(),
            'overall_rating' => $data['overall_rating'],
            'asset_condition_before' => $data['asset_condition_before'] ?? null,
            'asset_condition_after' => $data['asset_condition_after'] ?? null,
            'issues_found' => $data['issues_found'] ?? null,
            'recommendations' => $data['recommendations'] ?? null,
            'follow_up_required' => $data['follow_up_required'] ?? false,
            'follow_up_notes' => $data['follow_up_notes'] ?? null,
            'next_maintenance_recommendation' => $data['next_maintenance_recommendation'] ?? null,
            'asset_health_score' => $data['asset_health_score'] ?? null,
        ]);
    }

    /**
     * Request approval for a high-cost task
     */
    public function requestApproval(MaintenanceTask $task, User $user, string $justification): MaintenanceApproval
    {
        $task->update(['approval_status' => 'pending']);

        return MaintenanceApproval::create([
            'task_id' => $task->id,
            'requested_by_user_id' => $user->id,
            'status' => 'pending',
            'estimated_cost' => $task->estimated_cost,
            'justification' => $justification,
        ]);
    }

    /**
     * Approve a task
     */
    public function approveTask(MaintenanceTask $task, User $approver, string $comments = null): void
    {
        $task->update([
            'approval_status' => 'approved',
            'approved_by_user_id' => $approver->id,
            'approved_at' => now(),
            'approval_comments' => $comments,
        ]);
    }

    /**
     * Reject a task approval
     */
    public function rejectTask(MaintenanceTask $task, User $approver, string $comments): void
    {
        $task->update([
            'approval_status' => 'rejected',
            'approved_by_user_id' => $approver->id,
            'approved_at' => now(),
            'approval_comments' => $comments,
        ]);
    }

    /**
     * Generate unique task number
     */
    protected function generateTaskNumber(): string
    {
        $lastTask = MaintenanceTask::withTrashed()->orderBy('id', 'desc')->first();
        $nextNumber = $lastTask ? (int) substr($lastTask->task_number, 4) + 1 : 1;
        return 'MNT-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
