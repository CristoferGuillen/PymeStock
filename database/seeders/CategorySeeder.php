<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Abarrotes',
                'description' => 'Productos básicos de consumo diario como arroz, aceite, azúcar y granos.',
            ],
            [
                'name' => 'Bebidas',
                'description' => 'Bebidas embotelladas, gaseosas, aguas y jugos.',
            ],
            [
                'name' => 'Limpieza',
                'description' => 'Productos para limpieza del hogar y mantenimiento general.',
            ],
            [
                'name' => 'Papelería',
                'description' => 'Útiles escolares, cuadernos, lápices, carpetas y artículos de oficina.',
            ],
            [
                'name' => 'Tecnología',
                'description' => 'Accesorios tecnológicos, periféricos y productos electrónicos básicos.',
            ],
            [
                'name' => 'Cuidado personal',
                'description' => 'Productos de higiene y cuidado personal.',
            ],
            [
                'name' => 'Ferretería',
                'description' => 'Herramientas, cintas, tornillos y artículos básicos de ferretería.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                ]
            );
        }
    }
}