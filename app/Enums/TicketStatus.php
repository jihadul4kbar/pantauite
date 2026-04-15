<?php

namespace App\Enums;

enum TicketStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case REOPENED = 'reopened';

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::RESOLVED => 'Resolved',
            self::CLOSED => 'Closed',
            self::REOPENED => 'Reopened',
        };
    }

    /**
     * Get badge color for UI
     */
    public function color(): string
    {
        return match ($this) {
            self::OPEN => 'blue',
            self::IN_PROGRESS => 'yellow',
            self::RESOLVED => 'green',
            self::CLOSED => 'gray',
            self::REOPENED => 'red',
        };
    }

    /**
     * Check if ticket can be transitioned to another status
     */
    public function canTransitionTo(self $to): bool
    {
        return match ($this) {
            self::OPEN => in_array($to, [self::IN_PROGRESS, self::CLOSED]),
            self::IN_PROGRESS => in_array($to, [self::RESOLVED, self::OPEN]),
            self::RESOLVED => in_array($to, [self::CLOSED, self::REOPENED]),
            self::CLOSED => $to === self::REOPENED,
            self::REOPENED => in_array($to, [self::IN_PROGRESS, self::RESOLVED]),
        };
    }

    /**
     * Get all possible next statuses
     */
    public function nextStatuses(): array
    {
        return match ($this) {
            self::OPEN => [self::IN_PROGRESS, self::CLOSED],
            self::IN_PROGRESS => [self::RESOLVED, self::OPEN],
            self::RESOLVED => [self::CLOSED, self::REOPENED],
            self::CLOSED => [self::REOPENED],
            self::REOPENED => [self::IN_PROGRESS, self::RESOLVED],
        };
    }
}
