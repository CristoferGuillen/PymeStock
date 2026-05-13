<?php

namespace Tests\Feature\Qa;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeedersTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_initial_admin_categories_and_products(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@pymestock.test',
        ]);

        $this->assertGreaterThanOrEqual(1, Category::count());
        $this->assertGreaterThanOrEqual(1, Product::count());
    }

    public function test_seeded_products_have_valid_inventory_values(): void
    {
        $this->seed();

        $products = Product::query()->get();

        $this->assertGreaterThan(0, $products->count());

        foreach ($products as $product) {
            $this->assertNotEmpty($product->name);
            $this->assertNotEmpty($product->description);

            $this->assertNotNull($product->category_id);

            $this->assertGreaterThanOrEqual(
                0,
                $product->units_available,
                "El producto {$product->name} tiene unidades negativas."
            );

            $this->assertGreaterThanOrEqual(
                0,
                $product->unit_cost,
                "El producto {$product->name} tiene costo negativo."
            );

            $this->assertGreaterThanOrEqual(
                0,
                $product->sales_price,
                "El producto {$product->name} tiene precio de venta negativo."
            );

            $this->assertGreaterThanOrEqual(
                $product->unit_cost,
                $product->sales_price,
                "El producto {$product->name} se está vendiendo por debajo del costo."
            );
        }
    }

    public function test_seeded_categories_have_required_fields(): void
    {
        $this->seed();

        $categories = Category::query()->get();

        $this->assertGreaterThan(0, $categories->count());

        foreach ($categories as $category) {
            $this->assertNotEmpty($category->name);
            $this->assertNotEmpty($category->description);
        }
    }
}