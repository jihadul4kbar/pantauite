<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'code' => 'V-' . strtoupper(fake()->unique()->bothify('???###')),
            'contact_person' => fake()->name(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'website' => fake()->url(),
            'vendor_type' => fake()->randomElement(['hardware', 'software', 'network', 'maintenance', 'other']),
            'is_active' => true,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function hardware(): static
    {
        return $this->state(fn (array $attributes) => [
            'vendor_type' => 'hardware',
        ]);
    }

    public function software(): static
    {
        return $this->state(fn (array $attributes) => [
            'vendor_type' => 'software',
        ]);
    }

    public function network(): static
    {
        return $this->state(fn (array $attributes) => [
            'vendor_type' => 'network',
        ]);
    }
}
