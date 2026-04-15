<?php

namespace App\Console\Commands;

use App\Models\MaintenanceTask;
use App\Models\InventoryPart;
use App\Services\TelegramNotificationService;
use App\Services\MaintenanceService;
use Illuminate\Console\Command;

class SendMaintenanceNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:notify
                            {--reminders : Send task reminders for upcoming tasks}
                            {--overdue : Send overdue alerts}
                            {--lowstock : Send low stock alerts}
                            {--generate : Generate tasks from schedules}
                            {--all : Run all notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send maintenance notifications and alerts via Telegram';

    protected TelegramNotificationService $telegram;
    protected MaintenanceService $maintenance;

    public function __construct(
        TelegramNotificationService $telegram,
        MaintenanceService $maintenance
    ) {
        parent::__construct();
        $this->telegram = $telegram;
        $this->maintenance = $maintenance;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $runAll = $this->option('all');
        $sent = 0;

        if (!$this->telegram->isConfigured()) {
            $this->warn('Telegram is not configured. Set TELEGRAM_BOT_TOKEN in .env');
            return Command::FAILURE;
        }

        // Generate tasks from schedules
        if ($runAll || $this->option('generate')) {
            $generated = $this->maintenance->generateTasksFromSchedules();
            $this->info("✓ Generated {$generated} tasks from schedules");
        }

        // Send reminders for upcoming tasks (next 3 days)
        if ($runAll || $this->option('reminders')) {
            $upcomingTasks = MaintenanceTask::with(['asset', 'assignedUser'])
                ->whereIn('status', ['pending', 'scheduled'])
                ->whereBetween('scheduled_date', [now(), now()->addDays(3)])
                ->get();

            foreach ($upcomingTasks as $task) {
                if ($this->telegram->sendTaskReminder($task)) {
                    $this->info("✓ Reminder sent for {$task->task_number}");
                    $sent++;
                }
            }
        }

        // Send overdue alerts
        if ($runAll || $this->option('overdue')) {
            $overdueTasks = MaintenanceTask::with(['asset', 'assignedUser'])
                ->where('status', '!=', 'completed')
                ->where('status', '!=', 'cancelled')
                ->where('scheduled_date', '<', now())
                ->get();

            foreach ($overdueTasks as $task) {
                $task->update(['status' => 'overdue']);
                if ($this->telegram->sendOverdueAlert($task)) {
                    $this->warn("⚠ Overdue alert sent for {$task->task_number}");
                    $sent++;
                }
            }
        }

        // Send low stock alerts
        if ($runAll || $this->option('lowstock')) {
            $lowStockParts = InventoryPart::whereColumn('quantity_in_stock', '<=', 'reorder_point')
                ->where('is_active', true)
                ->get();

            foreach ($lowStockParts as $part) {
                if ($this->telegram->sendLowStockAlert(
                    $part->name,
                    $part->part_number,
                    $part->quantity_in_stock,
                    $part->reorder_point
                )) {
                    $this->warn("📦 Low stock alert for {$part->part_number}");
                    $sent++;
                }
            }
        }

        $this->info("\n✅ Notifications sent: {$sent}");
        return Command::SUCCESS;
    }
}
