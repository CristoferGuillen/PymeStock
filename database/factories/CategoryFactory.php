<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Abarrotes',
                'Bebidas',
                'Limpieza',
                'Papelería',
                'Tecnología',
                'Cuidado personal',
                'Ferretería',
                'Mascotas',
                'Hogar',
                'Oficina',
            ]) . ' ' . fake()->numberBetween(1, 9999),

            'description' => fake()->sentence(10),
        ];
    }
}