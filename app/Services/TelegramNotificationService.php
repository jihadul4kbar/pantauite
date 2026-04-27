<?php

namespace App\Services;

use App\Models\MaintenanceTask;
use App\Models\Ticket;
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
     * Send new ticket notification
     */
    public function sendNewTicketAlert(Ticket $ticket): bool
    {
        $managers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['it_manager', 'super_admin']);
        })->get();

        $assigneeInfo = $ticket->assignee ? $ticket->assignee->name : 'Belum ditugaskan';

        $message = "🎫 <b>Ticket Baru Dibuat</b>\n\n";
        $message .= "🔖 Nomor: {$ticket->ticket_number}\n";
        $message .= "📝 Subject: {$ticket->subject}\n";
        $message .= "📄 Deskripsi: " . ($ticket->description ?? '-') . "\n";
        $message .= "👤 Dibuat oleh: {$ticket->user->name}\n";
        $message .= "🔧 Ditugaskan ke: {$assigneeInfo}\n";
        $message .= "⚡ Priority: " . ucfirst($ticket->priority) . "\n";
        $message .= "📂 Kategori: " . ($ticket->category->name ?? '-') . "\n";
        $message .= "🏢 Departemen: " . ($ticket->department->name ?? '-') . "\n";
        $message .= "📅 Waktu: " . $ticket->created_at->format('d M Y H:i');

        // Collect unique chat IDs to avoid duplicate messages
        $sentChatIds = [];
        foreach ($managers as $manager) {
            $chatId = $this->getUserChatId($manager);
            if ($chatId && !in_array($chatId, $sentChatIds)) {
                $this->sendMessage($chatId, $message);
                $sentChatIds[] = $chatId;
            }
        }

        // Also notify assignee if already assigned
        if ($ticket->assignee && $ticket->assignee->telegram_chat_id) {
            $assigneeChatId = $ticket->assignee->telegram_chat_id;
            if (!in_array($assigneeChatId, $sentChatIds)) {
                $assigneeMessage = "📋 <b>Anda Ditugaskan Ticket Baru</b>\n\n";
                $assigneeMessage .= "🔖 Nomor: {$ticket->ticket_number}\n";
                $assigneeMessage .= "📝 Subject: {$ticket->subject}\n";
                $assigneeMessage .= "👤 Dibuat oleh: {$ticket->user->name}\n";
                $assigneeMessage .= "⚡ Priority: " . ucfirst($ticket->priority) . "\n";
                $assigneeMessage .= "📅 Waktu: " . $ticket->created_at->format('d M Y H:i');

                $this->sendMessage($assigneeChatId, $assigneeMessage);
                $sentChatIds[] = $assigneeChatId;
            }
        }

        return count($sentChatIds) > 0;
    }

    /**
     * Send ticket assignment notification
     */
    public function sendTicketAssignment(Ticket $ticket, User $assignee): bool
    {
        if (!$assignee->telegram_chat_id) return false;

        $message = "📋 <b>Anda Ditugaskan Ticket</b>\n\n";
        $message .= "🔖 Nomor: {$ticket->ticket_number}\n";
        $message .= "📝 Subject: {$ticket->subject}\n";
        $message .= "👤 Dibuat oleh: {$ticket->user->name}\n";
        $message .= "⚡ Priority: " . ucfirst($ticket->priority) . "\n";
        $message .= "📂 Kategori: " . ($ticket->category->name ?? '-') . "\n";
        $message .= "📅 Dibuat: " . $ticket->created_at->format('d M Y H:i');

        return $this->sendMessage($assignee->telegram_chat_id, $message);
    }

    /**
     * Send ticket assigned notification to managers
     */
    public function sendTicketAssignedAlert(Ticket $ticket, User $assignee): bool
    {
        $managers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['it_manager', 'super_admin']);
        })->get();

        $message = "🔧 <b>Ticket Ditugaskan</b>\n\n";
        $message .= "🔖 Nomor: {$ticket->ticket_number}\n";
        $message .= "📝 Subject: {$ticket->subject}\n";
        $message .= "👷 Ditugaskan ke: {$assignee->name}\n";
        $message .= "⚡ Priority: " . ucfirst($ticket->priority) . "\n";
        $message .= "📂 Kategori: " . ($ticket->category->name ?? '-') . "\n";
        $message .= "📅 Waktu: " . now()->format('d M Y H:i');

        $sentChatIds = [];
        foreach ($managers as $manager) {
            $chatId = $this->getUserChatId($manager);
            if ($chatId && !in_array($chatId, $sentChatIds)) {
                $this->sendMessage($chatId, $message);
                $sentChatIds[] = $chatId;
            }
        }

        return count($sentChatIds) > 0;
    }

    /**
     * Send ticket completed notification
     */
    public function sendTicketCompleted(Ticket $ticket, string $status): bool
    {
        $managers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['it_manager', 'super_admin']);
        })->get();

        $assigneeInfo = $ticket->assignee ? $ticket->assignee->name : 'N/A';
        $statusLabel = $status === 'resolved' ? '✅ Selesai (Resolved)' : '🔒 Ditutup (Closed)';

        $message = "{$statusLabel}\n\n";
        $message .= "🔖 Nomor: {$ticket->ticket_number}\n";
        $message .= "📝 Subject: {$ticket->subject}\n";
        $message .= "👤 Dibuat oleh: {$ticket->user->name}\n";
        $message .= "👷 Teknisi: {$assigneeInfo}\n";
        $message .= "⚡ Priority: " . ucfirst($ticket->priority) . "\n";
        $message .= "📂 Kategori: " . ($ticket->category->name ?? '-') . "\n";
        $message .= "📅 Selesai: " . now()->format('d M Y H:i');

        $sentChatIds = [];
        foreach ($managers as $manager) {
            $chatId = $this->getUserChatId($manager);
            if ($chatId && !in_array($chatId, $sentChatIds)) {
                $this->sendMessage($chatId, $message);
                $sentChatIds[] = $chatId;
            }
        }

        // Also notify the ticket creator
        if ($ticket->user && $ticket->user->telegram_chat_id) {
            $creatorChatId = $ticket->user->telegram_chat_id;
            if (!in_array($creatorChatId, $sentChatIds)) {
                $creatorMessage = "🎉 <b>Ticket Anda Telah Selesai</b>\n\n";
                $creatorMessage .= "🔖 Nomor: {$ticket->ticket_number}\n";
                $creatorMessage .= "📝 Subject: {$ticket->subject}\n";
                $creatorMessage .= "👷 Teknisi: {$assigneeInfo}\n";
                $creatorMessage .= "📅 Selesai: " . now()->format('d M Y H:i');

                $this->sendMessage($creatorChatId, $creatorMessage);
                $sentChatIds[] = $creatorChatId;
            }
        }

        return count($sentChatIds) > 0;
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

        $sentChatIds = [];
        foreach ($managers as $manager) {
            $chatId = $this->getUserChatId($manager);
            if ($chatId && !in_array($chatId, $sentChatIds)) {
                $this->sendMessage($chatId, $message);
                $sentChatIds[] = $chatId;
            }
        }

        return count($sentChatIds) > 0;
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

        $sentChatIds = [];
        foreach ($managers as $manager) {
            $chatId = $this->getUserChatId($manager);
            if ($chatId && !in_array($chatId, $sentChatIds)) {
                $this->sendMessage($chatId, $message);
                $sentChatIds[] = $chatId;
            }
        }

        return count($sentChatIds) > 0;
    }

    /**
     * Notify all managers
     */
    protected function notifyManagers(string $message): void
    {
        $managers = User::whereHas('role', function($q) {
            $q->whereIn('name', ['it_manager', 'super_admin']);
        })->get();

        $sentChatIds = [];
        foreach ($managers as $manager) {
            $chatId = $this->getUserChatId($manager);
            if ($chatId && !in_array($chatId, $sentChatIds)) {
                $this->sendMessage($chatId, $message);
                $sentChatIds[] = $chatId;
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
