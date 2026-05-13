<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            'units_available' => fake()->numberBetween(0, 500),
            'unit_cost' => $unitCost,
            'sales_price' => $unitCost + $profitMargin,
            'attachment' => null,
            'status' => $this->activeStatusValue(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $this->inactiveStatusValue(),
        ]);
    }

    public function withoutStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'units_available' => 0,
        ]);
    }

    public function withLowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'units_available' => fake()->numberBetween(1, 5),
        ]);
    }

    private function activeStatusValue(): mixed
    {
        return $this->statusValue(
            preferredValues: ['active', 'activo', 'available', 'disponible', 'enabled', 'habilitado', '1'],
            fallbackEnumIndex: 0,
            fallbackValue: true
        );
    }

    private function inactiveStatusValue(): mixed
    {
        return $this->statusValue(
            preferredValues: ['inactive', 'inactivo', 'unavailable', 'no disponible', 'disabled', 'deshabilitado', '0'],
            fallbackEnumIndex: 1,
            fallbackValue: false
        );
    }

    private function statusValue(array $preferredValues, int $fallbackEnumIndex, mixed $fallbackValue): mixed
    {
        if (! Schema::hasColumn('products', 'status')) {
            return $fallbackValue;
        }

        $column = DB::selectOne("SHOW COLUMNS FROM products WHERE Field = 'status'");
        $type = $column->Type ?? '';

        if (str_starts_with($type, 'enum(')) {
            $enumValues = $this->parseEnumValues($type);

            foreach ($preferredValues as $preferredValue) {
                foreach ($enumValues as $enumValue) {
                    if (mb_strtolower($enumValue) === mb_strtolower($preferredValue)) {
                        return $enumValue;
                    }
                }
            }

            return $enumValues[$fallbackEnumIndex] ?? $enumValues[0] ?? $fallbackValue;
        }

        return $fallbackValue;
    }

    private function parseEnumValues(string $type): array
    {
        preg_match_all("/'((?:[^'\\\\]|\\\\.)*)'/", $type, $matches);

        return array_map(
            fn (string $value): string => stripcslashes($value),
            $matches[1] ?? []
        );
    }
}