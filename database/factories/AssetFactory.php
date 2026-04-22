<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'asset_code' => 'AST-' . strtoupper(fake()->unique()->bothify('???###')),
            'asset_type' => fake()->randomElement(['hardware', 'software', 'network']),
            'name' => fake()->words(3, true),
            'brand' => fake()->company(),
            'model' => fake()->bothify('???-####'),
            'serial_number' => fake()->unique()->bothify('SN-##########'),
            'part_number' => fake()->optional()->bothify('PN-##########'),
            'specs' => [
                'cpu' => fake()->optional()->word(),
                'ram' => fake()->optional()->randomElement(['4GB', '8GB', '16GB', '32GB']),
                'storage' => fake()->optional()->randomElement(['256GB SSD', '512GB SSD', '1TB HDD', '1TB SSD']),
            ],
            'status' => fake()->randomElement(['procurement', 'inventory', 'deployed', 'maintenance', 'retired', 'disposed']),
            'condition' => fake()->randomElement(['new', 'good', 'fair', 'poor', 'broken']),
            'assigned_to_user_id' => null,
            'assigned_to_department_id' => null,
            'assigned_at' => null,
            'assigned_notes' => null,
            'location' => fake()->optional()->city(),
            'vendor_id' => Vendor::factory(),
            'vendor_name' => fake()->company(),
            'purchase_order_number' => fake()->optional()->bothify('PO-##########'),
            'purchase_date' => fake()->optional()->dateTimeBetween('-3 years', 'now'),
            'price' => fake()->randomFloat(2, 1000000, 100000000),
            'currency' => 'IDR',
            'warranty_start' => fake()->optional()->dateTimeBetween('-2 years', 'now'),
            'warranty_end' => fake()->optional()->dateTimeBetween('now', '+3 years'),
            'warranty_provider' => fake()->optional()->company(),
            'warranty_notes' => fake()->optional()->sentence(),
            'depreciation_method' => fake()->randomElement(['straight_line', 'declining_balance', 'none']),
            'useful_life_years' => fake()->numberBetween(1, 10),
            'depreciated_value' => null,
            'depreciation_start_date' => null,
            'disposal_date' => null,
            'disposal_reason' => null,
            'disposal_value' => null,
            'notes' => fake()->optional()->sentence(),
            'images' => [],
        ];
    }

    public function hardware(): static
    {
        return $this->state(fn (array $attributes) => [
            'asset_type' => 'hardware',
        ]);
    }

    public function software(): static
    {
        return $this->state(fn (array $attributes) => [
            'asset_type' => 'software',
        ]);
    }

    public function network(): static
    {
        return $this->state(fn (array $attributes) => [
            'asset_type' => 'network',
        ]);
    }

    public function deployed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'deployed',
        ]);
    }

    public function inventory(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inventory',
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }

    public function withImages(int $count = 3): static
    {
        $images = [];
        for ($i = 0; $i < $count; $i++) {
            $images[] = 'assets/images/asset_' . uniqid() . '_' . time() . '_' . $i . '.webp';
        }

        return $this->state(fn (array $attributes) => [
            'images' => $images,
        ]);
    }
}
