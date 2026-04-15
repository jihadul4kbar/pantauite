<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\SlaPolicy;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'ticket_number' => 'TKT-' . now()->format('Y') . '-' . str_pad(Ticket::count() + 1, 4, '0', STR_PAD_LEFT),
            'subject' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement(['open', 'in_progress', 'resolved', 'closed']),
            'priority' => $this->faker->randomElement(['critical', 'high', 'medium', 'low']),
            'user_id' => User::factory(),
            'assignee_id' => null,
            'department_id' => Department::factory(),
            'category_id' => TicketCategory::factory(),
            'sla_policy_id' => SlaPolicy::factory(),
            'sla_deadline' => now()->addHours(24),
            'sla_breached' => false,
            'source' => $this->faker->randomElement(['web', 'email', 'phone', 'walk-in']),
            'created_at' => now()->subDays(rand(0, 30)),
            'updated_at' => now(),
        ];
    }

    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'first_response_at' => now()->subHours(2),
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'resolved_at' => now()->subDays(2),
            'first_response_at' => now()->subDays(3),
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
            'resolved_at' => now()->subDays(5),
            'closed_at' => now()->subDays(3),
            'first_response_at' => now()->subDays(6),
        ]);
    }

    public function critical(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'critical',
        ]);
    }

    public function high(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
        ]);
    }

    public function withAssignee(): static
    {
        return $this->state(fn (array $attributes) => [
            'assignee_id' => User::factory(),
        ]);
    }
}
