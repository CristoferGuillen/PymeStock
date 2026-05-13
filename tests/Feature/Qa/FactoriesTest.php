<?php

namespace Tests\Feature\Qa;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FactoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_factory_creates_valid_category(): void
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
        ]);

        $this->assertNotEmpty($category->name);
        $this->assertNotEmpty($category->description);
    }

    public function test_product_factory_creates_valid_product(): void
    {
        $product = Product::factory()->create();

        $status = DB::table('products')
            ->where('id', $product->id)
            ->value('status');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name,
            'category_id' => $product->category_id,
        ]);

        $this->assertNotEmpty($product->name);
        $this->assertNotEmpty($product->description);
        $this->assertNotNull($product->category_id);
        $this->assertGreaterThanOrEqual(0, $product->units_available);
        $this->assertGreaterThanOrEqual(0, $product->unit_cost);
        $this->assertGreaterThanOrEqual($product->unit_cost, $product->sales_price);
        $this->assertNotEmpty((string) $status);
    }

    public function test_product_factory_creates_category_automatically(): void
    {
        $product = Product::factory()->create();

        $this->assertNotNull($product->category);
        $this->assertInstanceOf(Category::class, $product->category);
    }

    public function test_product_factory_can_create_inactive_product(): void
    {
        $activeProduct = Product::factory()->create();

        $inactiveProduct = Product::factory()
            ->inactive()
            ->create();

        $activeStatus = DB::table('products')
            ->where('id', $activeProduct->id)
            ->value('status');

        $inactiveStatus = DB::table('products')
            ->where('id', $inactiveProduct->id)
            ->value('status');

        $this->assertNotEmpty((string) $inactiveStatus);

        $this->assertNotSame(
            (string) $activeStatus,
            (string) $inactiveStatus,
            'El estado inactive() debe guardar un valor distinto al estado activo.'
        );
    }

    public function test_product_factory_can_create_product_without_stock(): void
    {
        $product = Product::factory()
            ->withoutStock()
            ->create();

        $this->assertSame(0, (int) $product->units_available);
    }

    public function test_product_factory_can_create_low_stock_product(): void
    {
        $product = Product::factory()
            ->withLowStock()
            ->create();

        $this->assertGreaterThanOrEqual(1, $product->units_available);
        $this->assertLessThanOrEqual(5, $product->units_available);
    }
}