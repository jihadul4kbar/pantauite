<?php

namespace App\Services;

use App\Models\SlaPolicy;
use App\Models\Ticket;
use App\Models\TicketAuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SlaService
{
    /**
     * Default holidays (can be moved to config/database later)
     * Format: 'YYYY-MM-DD' => 'Holiday Name'
     */
    protected array $defaultHolidays = [
        // Indonesian National Holidays (example, can be configured)
        '01-01' => "New Year's Day",
        '12-25' => 'Christmas Day',
        // Add more holidays as needed or load from config
    ];

    /**
     * Calculate SLA deadline for a ticket
     */
    public function calculateDeadline(Ticket $ticket): ?Carbon
    {
        $slaPolicy = $ticket->slaPolicy;

        if (!$slaPolicy) {
            // Try to get SLA policy based on priority
            $slaPolicy = SlaPolicy::where('priority', $ticket->priority)
                ->where('is_active', true)
                ->first();

            if (!$slaPolicy) {
                return null;
            }

            // Associate SLA policy with ticket
            $ticket->update(['sla_policy_id' => $slaPolicy->id]);
        }

        // Determine which time to use (response or resolution)
        $minutes = $slaPolicy->resolution_time_minutes;

        // For new tickets, use resolution time
        // For first response tracking, could use response_time_minutes
        if ($slaPolicy->is247()) {
            // 24/7 SLA - just add minutes to created_at
            return Carbon::parse($ticket->created_at)->addMinutes($minutes);
        }

        // Business hours SLA
        return $this->calculateBusinessHoursDeadline(
            Carbon::parse($ticket->created_at),
            $minutes,
            $slaPolicy
        );
    }

    /**
     * Calculate deadline considering business hours, weekends, and holidays
     */
    protected function calculateBusinessHoursDeadline(
        Carbon $startDate,
        int $minutes,
        SlaPolicy $policy
    ): Carbon {
        $businessStart = Carbon::parse($policy->business_hours_start)->format('H:i');
        $businessEnd = Carbon::parse($policy->business_hours_end)->format('H:i');
        $businessDays = $policy->business_days ?? [1, 2, 3, 4, 5]; // Mon-Fri default

        $current = clone $startDate;
        $remainingMinutes = $minutes;

        // If start time is outside business hours or on non-business day,
        // move to next business day at business hours start
        if (!$this->isWithinBusinessHours($current, $policy)) {
            $current = $this->getNextBusinessDayOrTime($current, $policy);
        }

        while ($remainingMinutes > 0) {
            // Skip non-business days and holidays
            while (!$this->isBusinessDay($current, $policy)) {
                $current->addDay()->startOfDay();
            }

            // Ensure we're at business hours start if before start time
            $currentTime = $current->format('H:i');
            if ($currentTime < $businessStart) {
                $current->setTimeFromTimeString($businessStart);
            }

            // Calculate available minutes in current day
            $endOfDay = clone $current;
            $endOfDay->setTimeFromTimeString($businessEnd);

            $availableMinutes = $current->diffInMinutes($endOfDay, false);

            if ($availableMinutes <= 0) {
                // No more time today, move to next business day
                $current->addDay()->startOfDay();
                continue;
            }

            $useMinutes = min($remainingMinutes, $availableMinutes);
            $current->addMinutes($useMinutes);
            $remainingMinutes -= $useMinutes;

            // If we still have remaining minutes, move to next business day
            if ($remainingMinutes > 0) {
                $current->addDay()->startOfDay();
            }
        }

        return $current;
    }

    /**
     * Calculate elapsed business hours between two dates
     */
    public function calculateBusinessHoursElapsed(
        Carbon $startDate,
        Carbon $endDate,
        SlaPolicy $policy
    ): float {
        // If SLA is paused, adjust the end date
        $adjustedEndDate = $endDate;

        $businessStart = Carbon::parse($policy->business_hours_start)->format('H:i');
        $businessEnd = Carbon::parse($policy->business_hours_end)->format('H:i');

        $elapsedMinutes = 0;
        $current = clone $startDate;

        while ($current < $adjustedEndDate) {
            // Skip non-business days and holidays
            if (!$this->isBusinessDay($current, $policy)) {
                $current->addDay()->startOfDay();
                continue;
            }

            // Calculate business hours for this day
            $dayStart = clone $current;
            if ($dayStart->format('H:i') < $businessStart) {
                $dayStart->setTimeFromTimeString($businessStart);
            }

            $dayEnd = clone $current;
            $dayEnd->setTimeFromTimeString($businessEnd);

            if ($dayEnd > $adjustedEndDate) {
                $dayEnd = clone $adjustedEndDate;
            }

            if ($dayStart < $dayEnd) {
                $elapsedMinutes += $dayStart->diffInMinutes($dayEnd, false);
            }

            $current->addDay()->startOfDay();
        }

        return round($elapsedMinutes, 2);
    }

    /**
     * Check if datetime is within business hours
     */
    protected function isWithinBusinessHours(Carbon $datetime, SlaPolicy $policy): bool
    {
        if (!$this->isBusinessDay($datetime, $policy)) {
            return false;
        }

        $time = $datetime->format('H:i');
        $businessStart = Carbon::parse($policy->business_hours_start)->format('H:i');
        $businessEnd = Carbon::parse($policy->business_hours_end)->format('H:i');

        return $time >= $businessStart && $time <= $businessEnd;
    }

    /**
     * Check if a date is a business day (not weekend/holiday)
     */
    protected function isBusinessDay(Carbon $date, SlaPolicy $policy): bool
    {
        $businessDays = $policy->business_days ?? [1, 2, 3, 4, 5];

        // Check if it's a configured business day
        if (!in_array($date->dayOfWeek, $businessDays)) {
            return false;
        }

        // Check if it's a holiday
        if ($this->isHoliday($date)) {
            return false;
        }

        return true;
    }

    /**
     * Check if a date is a holiday
     */
    protected function isHoliday(Carbon $date): bool
    {
        // Check default holidays
        $monthDay = $date->format('m-d');
        if (isset($this->defaultHolidays[$monthDay])) {
            return true;
        }

        // Can be extended to load from database or config
        return false;
    }

    /**
     * Get next business day or adjust time to business hours
     */
    protected function getNextBusinessDayOrTime(Carbon $date, SlaPolicy $policy): Carbon
    {
        $businessStart = Carbon::parse($policy->business_hours_start)->format('H:i');
        $businessEnd = Carbon::parse($policy->business_hours_end)->format('H:i');

        $next = clone $date;

        // Skip non-business days and holidays
        while (!$this->isBusinessDay($next, $policy)) {
            $next->addDay()->startOfDay();
        }

        // If time is after business hours, move to next business day
        $currentTime = $next->format('H:i');
        if ($currentTime > $businessEnd) {
            $next->addDay()->startOfDay();
            while (!$this->isBusinessDay($next, $policy)) {
                $next->addDay()->startOfDay();
            }
            $next->setTimeFromTimeString($businessStart);
        } elseif ($currentTime < $businessStart) {
            // Before business hours, move to start of business
            $next->setTimeFromTimeString($businessStart);
        }

        return $next;
    }

    /**
     * Get next business day
     */
    protected function getNextBusinessDay(Carbon $date, SlaPolicy $policy): Carbon
    {
        $next = clone $date;
        $next->addDay()->startOfDay();

        // Skip non-business days and holidays (max 14 iterations)
        for ($i = 0; $i < 14; $i++) {
            if ($this->isBusinessDay($next, $policy)) {
                return $next;
            }
            $next->addDay();
        }

        return $next;
    }

    /**
     * Initialize SLA for a new ticket
     */
    public function initialize(Ticket $ticket): void
    {
        $deadline = $this->calculateDeadline($ticket);

        if ($deadline) {
            $ticket->update([
                'sla_deadline' => $deadline,
                'sla_breached' => false,
                'paused_at' => null,
            ]);

            // Log SLA initialization
            $this->logSlaEvent($ticket, 'sla_initialized', [
                'deadline' => $deadline->toDateTimeString(),
                'policy_id' => $ticket->sla_policy_id,
            ]);
        }
    }

    /**
     * Pause SLA timer (e.g., waiting for customer response)
     */
    public function pauseSla(Ticket $ticket): bool
    {
        if ($ticket->paused_at) {
            return false; // Already paused
        }

        if (in_array($ticket->status, ['closed', 'resolved'])) {
            return false; // Can't pause closed/resolved tickets
        }

        $ticket->update(['paused_at' => now()]);

        // Calculate elapsed time before pause for audit
        $elapsed = null;
        if ($ticket->sla_deadline) {
            $policy = $ticket->slaPolicy;
            if ($policy && !$policy->is247()) {
                $elapsed = $this->calculateBusinessHoursElapsed(
                    Carbon::parse($ticket->created_at),
                    now(),
                    $policy
                );
            }
        }

        $this->logSlaEvent($ticket, 'sla_paused', [
            'paused_at' => now()->toDateTimeString(),
            'elapsed_minutes' => $elapsed,
        ]);

        return true;
    }

    /**
     * Resume SLA timer
     */
    public function resumeSla(Ticket $ticket): bool
    {
        if (!$ticket->paused_at) {
            return false; // Not paused
        }

        $pauseDuration = $ticket->paused_at->diffInMinutes(now());

        // Recalculate deadline by adding pause duration
        if ($ticket->sla_deadline && !$ticket->sla_breached) {
            $policy = $ticket->slaPolicy;

            if ($policy && !$policy->is247()) {
                // For business hours SLA, recalculate from scratch
                // using original created_at + pause time as new start
                $originalCreated = Carbon::parse($ticket->created_at);
                $pauseMinutes = $ticket->paused_at->diffInMinutes(now());

                // Add the pause time to the original deadline
                $newDeadline = $ticket->sla_deadline->copy()->addMinutes($pauseMinutes);

                $ticket->update([
                    'sla_deadline' => $newDeadline,
                    'paused_at' => null,
                ]);
            } else {
                // For 24/7 SLA, simply add pause duration
                $ticket->update([
                    'sla_deadline' => $ticket->sla_deadline->copy()->addMinutes($pauseDuration),
                    'paused_at' => null,
                ]);
            }
        } else {
            $ticket->update(['paused_at' => null]);
        }

        $this->logSlaEvent($ticket, 'sla_resumed', [
            'paused_duration_minutes' => $pauseDuration,
            'resumed_at' => now()->toDateTimeString(),
        ]);

        return true;
    }

    /**
     * Toggle SLA pause state
     */
    public function toggleSlaPause(Ticket $ticket): array
    {
        if ($ticket->paused_at) {
            $success = $this->resumeSla($ticket);
            return ['action' => 'resumed', 'success' => $success];
        } else {
            $success = $this->pauseSla($ticket);
            return ['action' => 'paused', 'success' => $success];
        }
    }

    /**
     * Check and mark breached tickets
     */
    public function checkBreaches(): int
    {
        $breached = 0;

        // Only check tickets that are not paused and not closed/resolved
        $tickets = Ticket::whereNotNull('sla_deadline')
            ->where('sla_breached', false)
            ->whereNull('paused_at') // Don't check paused tickets
            ->whereNotIn('status', ['closed', 'resolved'])
            ->where('sla_deadline', '<', now())
            ->get();

        foreach ($tickets as $ticket) {
            $ticket->update([
                'sla_breached' => true,
                'sla_breached_at' => now(),
            ]);

            $this->logSlaEvent($ticket, 'sla_breached', [
                'breached_at' => now()->toDateTimeString(),
                'deadline' => $ticket->sla_deadline->toDateTimeString(),
            ]);

            // Trigger escalation if enabled
            $this->triggerEscalation($ticket);

            $breached++;
        }

        return $breached;
    }

    /**
     * Get SLA status for a ticket
     */
    public function getStatus(Ticket $ticket): string
    {
        if ($ticket->paused_at) {
            return 'paused';
        }

        if ($ticket->sla_breached) {
            return 'breached';
        }

        if (!$ticket->sla_deadline) {
            return 'no_sla';
        }

        if (in_array($ticket->status, ['closed', 'resolved'])) {
            return 'met';
        }

        // Check if at risk (within threshold minutes of breach)
        $policy = $ticket->slaPolicy;
        $threshold = $policy?->escalation_threshold_minutes ?? 30;

        if (now()->addMinutes($threshold)->gte($ticket->sla_deadline)) {
            return 'at_risk';
        }

        return 'on_track';
    }

    /**
     * Get time remaining until SLA breach
     */
    public function getTimeRemaining(Ticket $ticket): ?array
    {
        if ($ticket->paused_at) {
            return [
                'status' => 'paused',
                'paused_at' => $ticket->paused_at->toDateTimeString(),
                'paused_duration_minutes' => $ticket->paused_at->diffInMinutes(now()),
            ];
        }

        if (!$ticket->sla_deadline) {
            return null;
        }

        if ($ticket->sla_breached) {
            return [
                'status' => 'breached',
                'breached_at' => $ticket->sla_breached_at?->toDateTimeString(),
                'minutes_overdue' => $ticket->sla_deadline->diffInMinutes(now()),
            ];
        }

        $remaining = now()->diffInMinutes($ticket->sla_deadline, false);

        if ($remaining < 0) {
            return [
                'status' => 'breached',
                'minutes_overdue' => abs($remaining),
            ];
        }

        $policy = $ticket->slaPolicy;
        $threshold = $policy?->escalation_threshold_minutes ?? 30;

        $status = 'on_track';
        if ($remaining <= $threshold) {
            $status = 'at_risk';
        }

        return [
            'status' => $status,
            'minutes_remaining' => round($remaining, 2),
            'hours_remaining' => round($remaining / 60, 2),
            'deadline' => $ticket->sla_deadline->toDateTimeString(),
        ];
    }

    /**
     * Calculate SLA compliance percentage
     */
    public function getCompliancePercentage(?string $period = null): float
    {
        $query = Ticket::whereNotNull('sla_deadline');

        if ($period) {
            $query->where('created_at', '>=', match ($period) {
                'today' => now()->startOfDay(),
                'week' => now()->startOfWeek(),
                'month' => now()->startOfMonth(),
                default => now()->subDays(30),
            });
        }

        $total = $query->count();

        if ($total === 0) {
            return 100.0;
        }

        $met = (clone $query)->where(function ($q) {
            $q->where('sla_breached', false)
                ->orWhereIn('status', ['closed', 'resolved']);
        })->count();

        return round(($met / $total) * 100, 2);
    }

    /**
     * Trigger escalation for breached ticket
     */
    protected function triggerEscalation(Ticket $ticket): void
    {
        $policy = $ticket->slaPolicy;

        if (!$policy || !$policy->escalation_enabled || !$policy->escalation_user_id) {
            return;
        }

        // Log escalation event
        $this->logSlaEvent($ticket, 'sla_escalated', [
            'escalated_to_user_id' => $policy->escalation_user_id,
            'escalated_at' => now()->toDateTimeString(),
        ]);

        // In future: send notification, create escalation task, etc.
    }

    /**
     * Log SLA event to audit trail
     */
    protected function logSlaEvent(Ticket $ticket, string $action, array $data = []): void
    {
        try {
            TicketAuditLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'action' => $action,
                'old_values' => null,
                'new_values' => $data,
                'notes' => "SLA Event: {$action}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Log silently fails - don't break the flow
            \Log::warning('Failed to log SLA event: ' . $e->getMessage());
        }
    }

    /**
     * Get list of configured holidays
     */
    public function getHolidays(?int $year = null): array
    {
        $year = $year ?? now()->year;
        $holidays = [];

        foreach ($this->defaultHolidays as $monthDay => $name) {
            $holidays[] = [
                'date' => "{$year}-{$monthDay}",
                'name' => $name,
            ];
        }

        return $holidays;
    }

    /**
     * Add a holiday to the list
     */
    public function addHoliday(string $date, string $name): void
    {
        $carbonDate = Carbon::parse($date);
        $monthDay = $carbonDate->format('m-d');
        $this->defaultHolidays[$monthDay] = $name;
    }
}
