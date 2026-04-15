<?php

namespace App\Services;

use App\Models\MaintenanceTask;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    protected ?string $botToken;
    protected ?string $baseUrl;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token') ?: null;
        $this->baseUrl = $this->botToken ? 'https://api.telegram.org/bot' . $this->botToken : null;
    }

    /**
     * Send message to a specific chat ID
     */
    public function sendMessage(string $chatId, string $text, array $options = []): bool
    {
        if (empty($this->botToken)) {
            Log::warning('Telegram bot token not configured');
            return false;
        }

        try {
            $response = Http::post("{$this->baseUrl}/sendMessage", array_merge([
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ], $options));

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send task reminder notification
     */
    public function sendTaskReminder(MaintenanceTask $task): bool
    {
        $chatId = $this->getUserChatId($task->assignedUser);
        if (!$chatId) return false;

        $message = "📅 <b>Maintenance Reminder</b>\n\n";
        $message .= "🔧 Task: {$task->task_number}\n";
        $message .= "📝 {$task->title}\n";
        $message .= "📦 Asset: {$task->asset->asset_code}\n";
        $message .= "📅 Scheduled: {$task->scheduled_date->format('d M Y')}\n";
        $message .= "⚡ Priority: " . ucfirst($task->priority);

        return $this->sendMessage($chatId, $message);
    }

    /**
     * Send overdue alert notification
     */
    public function sendOverdueAlert(MaintenanceTask $task): bool
    {
        $chatId = $this->getUserChatId($task->assignedUser);
        if (!$chatId) return false;

        $daysOverdue = now()->diffInDays($task->scheduled_date);

        $message = "🚨 <b>Maintenance Overdue!</b>\n\n";
        $message .= "🔧 Task: {$task->task_number}\n";
        $message .= "📝 {$task->title}\n";
        $message .= "📦 Asset: {$task->asset->asset_code}\n";
        $message .= "⏰ <b>{$daysOverdue} day(s) overdue</b>\n";
        $message .= "\n⚠️ Please complete this task as soon as possible.";

        // Also notify manager
        $this->notifyManagers($message);

        return $this->sendMessage($chatId, $message);
    }

    /**
     * Send approval request notification
     */
    public function sendApprovalRequest(MaintenanceTask $task): bool
    {
        $managers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['it_manager', 'super_admin']);
        })->get();

        $message = "✅ <b>Approval Required</b>\n\n";
        $message .= "🔧 Task: {$task->task_number}\n";
        $message .= "📝 {$task->title}\n";
        $message .= "💰 Est. Cost: Rp " . number_format($task->estimated_cost ?? 0, 0, ',', '.') . "\n";
        $message .= "\n⏳ Awaiting your approval.";

        $sent = false;
        foreach ($managers as $manager) {
            $chatId = $this->getUserChatId($manager);
            if ($chatId) {
                $this->sendMessage($chatId, $message);
                $sent = true;
            }
        }

        return $sent;
    }

    /**
     * Send task completion notification
     */
    public function sendTaskCompleted(MaintenanceTask $task): bool
    {
        $chatId = $this->getUserChatId($task->assignedUser);
        if (!$chatId) return false;

        $message = "✅ <b>Task Completed</b>\n\n";
        $message .= "🔧 Task: {$task->task_number}\n";
        $message .= "📝 {$task->title}\n";
        $message .= "⏱️ Duration: " . ($task->actual_duration_minutes ? round($task->actual_duration_minutes / 60, 1) . ' hours' : 'N/A') . "\n";
        $message .= "💰 Cost: Rp " . number_format($task->actual_cost ?? 0, 0, ',', '.');

        return $this->sendMessage($chatId, $message);
    }

    /**
     * Send low stock alert
     */
    public function sendLowStockAlert(string $partName, string $partNumber, float $currentStock, float $reorderPoint): bool
    {
        $managers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['it_manager', 'super_admin']);
        })->get();

        $message = "📦 <b>Low Stock Alert</b>\n\n";
        $message .= "Part: {$partName}\n";
        $message .= "Code: {$partNumber}\n";
        $message .= "Current Stock: <b>{$currentStock}</b>\n";
        $message .= "Reorder Point: {$reorderPoint}\n";
        $message .= "\n⚠️ Please reorder this part.";

        $sent = false;
        foreach ($managers as $manager) {
            $chatId = $this->getUserChatId($manager);
            if ($chatId) {
                $this->sendMessage($chatId, $message);
                $sent = true;
            }
        }

        return $sent;
    }

    /**
     * Notify all managers
     */
    protected function notifyManagers(string $message): void
    {
        $managers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['it_manager', 'super_admin']);
        })->get();

        foreach ($managers as $manager) {
            $chatId = $this->getUserChatId($manager);
            if ($chatId) {
                $this->sendMessage($chatId, $message);
            }
        }
    }

    /**
     * Get user's Telegram chat ID
     */
    protected function getUserChatId(?User $user): ?string
    {
        if (!$user) return null;
        return $user->telegram_chat_id ?? config('services.telegram.default_chat_id');
    }

    /**
     * Check if Telegram is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->botToken);
    }
}
