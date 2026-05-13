<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category' => 'Abarrotes',
                'name' => 'Arroz 1kg',
                'description' => 'Arroz blanco paquete de 1 kilogramo',
                'units_available' => 40,
                'unit_cost' => 2800,
                'sales_price' => 3900,
            ],
            [
                'category' => 'Abarrotes',
                'name' => 'Aceite vegetal 900ml',
                'description' => 'Aceite vegetal botella de 900ml',
                'units_available' => 25,
                'unit_cost' => 7200,
                'sales_price' => 9200,
            ],
            [
                'category' => 'Bebidas',
                'name' => 'Agua botella 600ml',
                'description' => 'Agua sin gas botella personal',
                'units_available' => 80,
                'unit_cost' => 900,
                'sales_price' => 1800,
            ],
            [
                'category' => 'Bebidas',
                'name' => 'Gaseosa 1.5L',
                'description' => 'Bebida gaseosa familiar',
                'units_available' => 30,
                'unit_cost' => 3200,
                'sales_price' => 5200,
            ],
            [
                'category' => 'Limpieza',
                'name' => 'Detergente en polvo 500g',
                'description' => 'Detergente para ropa',
                'units_available' => 35,
                'unit_cost' => 3500,
                'sales_price' => 5500,
            ],
            [
                'category' => 'Papelería',
                'name' => 'Cuaderno cuadriculado',
                'description' => 'Cuaderno universitario 100 hojas',
                'units_available' => 50,
                'unit_cost' => 2800,
                'sales_price' => 4500,
            ],
            [
                'category' => 'Tecnología',
                'name' => 'Mouse USB',
                'description' => 'Mouse óptico con cable USB',
                'units_available' => 15,
                'unit_cost' => 12000,
                'sales_price' => 22000,
            ],
            [
                'category' => 'Cuidado personal',
                'name' => 'Jabón antibacterial',
                'description' => 'Jabón en barra antibacterial',
                'units_available' => 45,
                'unit_cost' => 1800,
                'sales_price' => 3200,
            ],
            [
                'category' => 'Ferretería',
                'name' => 'Cinta aislante',
                'description' => 'Cinta aislante negra',
                'units_available' => 20,
                'unit_cost' => 2500,
                'sales_price' => 4800,
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('name', $data['category'])->first();

            if (! $category) {
                continue;
            }

            Product::updateOrCreate(
                ['name' => $data['name']],
                [
                    'description' => $data['description'],
                    'category_id' => $category->id,
                    'units_available' => $data['units_available'],
                    'unit_cost' => $data['unit_cost'],
                    'sales_price' => $data['sales_price'],
                    'attachment' => null,
                    'status' => true,
                ]
            );
        }
    }
}