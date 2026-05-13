<?php

namespace Tests\Feature\Qa;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_belongs_to_category_relationship_exists(): void
    {
        $product = new Product();

        $this->assertTrue(
            method_exists($product, 'category'),
            'El modelo Product debe tener una relación category().'
        );

        $this->assertInstanceOf(
            BelongsTo::class,
            $product->category(),
            'La relación Product::category() debe ser de tipo BelongsTo.'
        );
    }

    public function test_category_has_many_products_relationship_exists(): void
    {
        $category = new Category();

        $this->assertTrue(
            method_exists($category, 'products'),
            'El modelo Category debe tener una relación products().'
        );

        $this->assertInstanceOf(
            HasMany::class,
            $category->products(),
            'La relación Category::products() debe ser de tipo HasMany.'
        );
    }

    public function test_seeded_products_can_access_their_category(): void
    {
        $this->seed();

        $products = Product::query()->with('category')->get();

        $this->assertGreaterThan(
            0,
            $products->count(),
            'No existen productos sembrados para validar relaciones.'
        );

        foreach ($products as $product) {
            $this->assertNotNull(
                $product->category,
                "El producto {$product->name} no puede acceder a su categoría."
            );

            $this->assertInstanceOf(
                Category::class,
                $product->category,
                "La relación category del producto {$product->name} no devuelve una instancia de Category."
            );
        }
    }

    public function test_seeded_categories_can_access_their_products(): void
    {
        $this->seed();

        $categories = Category::query()->with('products')->get();

        $this->assertGreaterThan(
            0,
            $categories->count(),
            'No existen categorías sembradas para validar relaciones.'
        );

        foreach ($categories as $category) {
            $this->assertTrue(
                $category->relationLoaded('products'),
                "La relación products no fue cargada para la categoría {$category->name}."
            );

            $this->assertGreaterThanOrEqual(
                0,
                $category->products->count(),
                "La categoría {$category->name} no pudo cargar sus productos."
            );
        }
    }

    public function test_products_table_category_id_matches_existing_category(): void
    {
        $this->seed();

        $products = Product::query()->get();

        $this->assertGreaterThan(
            0,
            $products->count(),
            'No existen productos para validar category_id.'
        );

        foreach ($products as $product) {
            $this->assertTrue(
                Category::query()->whereKey($product->category_id)->exists(),
                "El producto {$product->name} tiene category_id {$product->category_id}, pero esa categoría no existe."
            );
        }
    }
}