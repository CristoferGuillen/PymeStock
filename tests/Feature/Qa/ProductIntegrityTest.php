<?php

namespace Tests\Feature\Qa;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_have_valid_categories(): void
    {
        $this->seed();

        $products = Product::query()->get();

        $this->assertGreaterThan(
            0,
            $products->count(),
            'No existen productos para validar.'
        );

        foreach ($products as $product) {
            $this->assertNotNull(
                $product->category_id,
                "El producto {$product->name} no tiene categoría asignada."
            );

            $this->assertTrue(
                Category::whereKey($product->category_id)->exists(),
                "El producto {$product->name} tiene una categoría inexistente."
            );
        }
    }

    public function test_products_do_not_have_negative_inventory_or_prices(): void
    {
        $this->seed();

        $products = Product::query()->get();

        $this->assertGreaterThan(
            0,
            $products->count(),
            'No existen productos para validar.'
        );

        foreach ($products as $product) {
            $this->assertGreaterThanOrEqual(
                0,
                $product->units_available,
                "El producto {$product->name} tiene unidades disponibles negativas."
            );

            $this->assertGreaterThanOrEqual(
                0,
                $product->unit_cost,
                "El producto {$product->name} tiene costo unitario negativo."
            );

            $this->assertGreaterThanOrEqual(
                0,
                $product->sales_price,
                "El producto {$product->name} tiene precio de venta negativo."
            );
        }
    }

    public function test_products_are_not_sold_below_cost(): void
    {
        $this->seed();

        $products = Product::query()->get();

        $this->assertGreaterThan(
            0,
            $products->count(),
            'No existen productos para validar.'
        );

        foreach ($products as $product) {
            $this->assertGreaterThanOrEqual(
                $product->unit_cost,
                $product->sales_price,
                "El producto {$product->name} tiene precio de venta menor que su costo."
            );
        }
    }

    public function test_active_products_have_required_commercial_information(): void
    {
        $this->seed();

        $activeProducts = Product::query()
            ->where('status', true)
            ->get();

        $this->assertGreaterThan(
            0,
            $activeProducts->count(),
            'No existen productos activos para validar.'
        );

        foreach ($activeProducts as $product) {
            $this->assertNotEmpty(
                $product->name,
                'Existe un producto activo sin nombre.'
            );

            $this->assertNotEmpty(
                $product->description,
                "El producto {$product->name} no tiene descripción."
            );

            $this->assertNotNull(
                $product->category_id,
                "El producto {$product->name} no tiene categoría."
            );

            $this->assertGreaterThan(
                0,
                $product->sales_price,
                "El producto {$product->name} debe tener precio de venta mayor a cero."
            );
        }
    }

    public function test_products_have_expected_profit_margin(): void
    {
        $this->seed();

        $products = Product::query()->get();

        $this->assertGreaterThan(
            0,
            $products->count(),
            'No existen productos para validar.'
        );

        foreach ($products as $product) {
            $profit = $product->sales_price - $product->unit_cost;

            $this->assertGreaterThanOrEqual(
                0,
                $profit,
                "El producto {$product->name} tiene margen de ganancia negativo."
            );
        }
    }
}