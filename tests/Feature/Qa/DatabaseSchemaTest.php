<?php

namespace Tests\Feature\Qa;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_tables_exist(): void
    {
        $requiredTables = [
            'users',
            'categories',
            'products',
        ];

        foreach ($requiredTables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "La tabla {$table} no existe."
            );
        }
    }

    public function test_users_table_has_required_columns(): void
    {
        $requiredColumns = [
            'id',
            'name',
            'email',
            'password',
            'created_at',
            'updated_at',
        ];

        foreach ($requiredColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('users', $column),
                "La tabla users no tiene la columna {$column}."
            );
        }
    }

    public function test_categories_table_has_required_columns(): void
    {
        $requiredColumns = [
            'id',
            'name',
            'description',
            'created_at',
            'updated_at',
        ];

        foreach ($requiredColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('categories', $column),
                "La tabla categories no tiene la columna {$column}."
            );
        }
    }

    public function test_products_table_has_required_columns(): void
    {
        $requiredColumns = [
            'id',
            'name',
            'description',
            'category_id',
            'units_available',
            'unit_cost',
            'sales_price',
            'attachment',
            'status',
            'deleted_at',
            'created_at',
            'updated_at',
        ];

        foreach ($requiredColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('products', $column),
                "La tabla products no tiene la columna {$column}."
            );
        }
    }

    public function test_products_table_keeps_expected_business_column_names(): void
    {
        $unexpectedColumns = [
            'stock',
            'price',
            'purchase_price',
            'sale_price',
            'minimum_stock',
        ];

        foreach ($unexpectedColumns as $column) {
            $this->assertFalse(
                Schema::hasColumn('products', $column),
                "La tabla products tiene la columna inesperada {$column}. El proyecto usa units_available, unit_cost y sales_price."
            );
        }
    }
}