<?php

namespace App\Enums;

enum AssetStatus: string
{
    case PROCUREMENT = 'procurement';
    case INVENTORY = 'inventory';
    case DEPLOYED = 'deployed';
    case MAINTENANCE = 'maintenance';
    case RETIRED = 'retired';
    case DISPOSED = 'disposed';

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::PROCUREMENT => 'Procurement',
            self::INVENTORY => 'Inventory',
            self::DEPLOYED => 'Deployed',
            self::MAINTENANCE => 'Maintenance',
            self::RETIRED => 'Retired',
            self::DISPOSED => 'Disposed',
        };
    }

    /**
     * Get badge color for UI
     */
    public function color(): string
    {
        return match ($this) {
            self::PROCUREMENT => 'purple',
            self::INVENTORY => 'blue',
            self::DEPLOYED => 'green',
            self::MAINTENANCE => 'yellow',
            self::RETIRED => 'gray',
            self::DISPOSED => 'red',
        };
    }

    /**
     * Check if asset is available for assignment
     */
    public function isAvailable(): bool
    {
        return in_array($this, [self::PROCUREMENT, self::INVENTORY], true);
    }

    /**
     * Check if asset is in active use
     */
    public function isActive(): bool
    {
        return in_array($this, [self::DEPLOYED, self::MAINTENANCE], true);
    }
}
