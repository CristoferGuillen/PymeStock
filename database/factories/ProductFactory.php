<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $unitCost = fake()->numberBetween(1000, 50000);
        $profitMargin = fake()->numberBetween(500, 25000);

        return [
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(12),
            'category_id' => Category::factory(),
            'units_available' => fake()->numberBetween(1, 500),
            'unit_cost' => $unitCost,
            'sales_price' => $unitCost + $profitMargin,
            'attachment' => null,
            'status' => 'in_stock',
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'out_of_stock',
        ]);
    }

    public function withoutStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'units_available' => 0,
            'status' => 'out_of_stock',
        ]);
    }

    public function withLowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'units_available' => fake()->numberBetween(1, 5),
            'status' => 'in_stock',
        ]);
    }

    public function pendingPricing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending_pricing',
        ]);
    }
}