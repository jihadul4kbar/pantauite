<?php

namespace App\Enums;

enum AssetType: string
{
    case HARDWARE = 'hardware';
    case SOFTWARE = 'software';
    case NETWORK = 'network';

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match ($this) {
            self::HARDWARE => 'Hardware',
            self::SOFTWARE => 'Software',
            self::NETWORK => 'Network',
        };
    }

    /**
     * Get asset code prefix
     */
    public function codePrefix(): string
    {
        return match ($this) {
            self::HARDWARE => 'HW',
            self::SOFTWARE => 'SW',
            self::NETWORK => 'NW',
        };
    }

    /**
     * Get icon for UI
     */
    public function icon(): string
    {
        return match ($this) {
            self::HARDWARE => '💻',
            self::SOFTWARE => '💿',
            self::NETWORK => '🌐',
        };
    }
}
