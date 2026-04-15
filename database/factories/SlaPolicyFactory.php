<?php

namespace Database\Factories;

use App\Models\SlaPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlaPolicyFactory extends Factory
{
    protected $model = SlaPolicy::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word . ' SLA Policy',
            'priority' => $this->faker->randomElement(['critical', 'high', 'medium', 'low']),
            'response_time_minutes' => $this->faker->randomElement([15, 60, 240, 480]),
            'resolution_time_minutes' => $this->faker->randomElement([240, 480, 1440, 4320]),
            'use_business_hours' => $this->faker->boolean(70),
            'business_hours_start' => '08:00:00',
            'business_hours_end' => '17:00:00',
            'business_days' => [1, 2, 3, 4, 5],
            'escalation_enabled' => $this->faker->boolean(50),
            'escalation_threshold_minutes' => 30,
            'escalation_user_id' => null,
            'is_active' => true,
            'description' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function critical(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'critical',
            'response_time_minutes' => 15,
            'resolution_time_minutes' => 240,
            'use_business_hours' => false,
        ]);
    }

    public function high(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
            'response_time_minutes' => 60,
            'resolution_time_minutes' => 480,
        ]);
    }

    public function medium(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'medium',
            'response_time_minutes' => 240,
            'resolution_time_minutes' => 1440,
        ]);
    }

    public function low(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'low',
            'response_time_minutes' => 480,
            'resolution_time_minutes' => 4320,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
