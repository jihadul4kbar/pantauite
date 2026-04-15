<?php

namespace Database\Seeders;

use App\Models\SlaPolicy;
use Illuminate\Database\Seeder;

class SlaPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Critical - 24/7 support
        SlaPolicy::updateOrCreate(
            ['priority' => 'critical'],
            [
                'name' => 'Critical Priority SLA',
                'response_time_minutes' => 15,
                'resolution_time_minutes' => 240, // 4 hours
                'use_business_hours' => false, // 24/7
                'business_hours_start' => '08:00:00',
                'business_hours_end' => '17:00:00',
                'business_days' => [1, 2, 3, 4, 5],
                'escalation_enabled' => true,
                'escalation_threshold_minutes' => 30, // Warn 30 min before breach
                'is_active' => true,
                'description' => 'Critical issues - System down, wide impact, immediate action required',
            ]
        );

        // High Priority
        SlaPolicy::updateOrCreate(
            ['priority' => 'high'],
            [
                'name' => 'High Priority SLA',
                'response_time_minutes' => 60, // 1 hour
                'resolution_time_minutes' => 480, // 8 hours
                'use_business_hours' => true,
                'business_hours_start' => '08:00:00',
                'business_hours_end' => '17:00:00',
                'business_days' => [1, 2, 3, 4, 5],
                'escalation_enabled' => true,
                'escalation_threshold_minutes' => 60,
                'is_active' => true,
                'description' => 'High priority - Major feature broken, urgent but workaround exists',
            ]
        );

        // Medium Priority
        SlaPolicy::updateOrCreate(
            ['priority' => 'medium'],
            [
                'name' => 'Medium Priority SLA',
                'response_time_minutes' => 240, // 4 hours
                'resolution_time_minutes' => 1440, // 24 hours
                'use_business_hours' => true,
                'business_hours_start' => '08:00:00',
                'business_hours_end' => '17:00:00',
                'business_days' => [1, 2, 3, 4, 5],
                'escalation_enabled' => false,
                'is_active' => true,
                'description' => 'Medium priority - Partial issue, can wait for normal business hours',
            ]
        );

        // Low Priority
        SlaPolicy::updateOrCreate(
            ['priority' => 'low'],
            [
                'name' => 'Low Priority SLA',
                'response_time_minutes' => 480, // 8 hours
                'resolution_time_minutes' => 4320, // 72 hours (3 days)
                'use_business_hours' => true,
                'business_hours_start' => '08:00:00',
                'business_hours_end' => '17:00:00',
                'business_days' => [1, 2, 3, 4, 5],
                'escalation_enabled' => false,
                'is_active' => true,
                'description' => 'Low priority - Minor issue, cosmetic, enhancement request',
            ]
        );

        $this->command->info('SLA Policies seeded successfully!');
    }
}
