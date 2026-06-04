<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Electrónica', 'Ropa', 'Hogar', 'Deportes',
            'Libros', 'Juguetes', 'Belleza', 'Alimentos',
            'Herramientas', 'Mascotas',
        ]);

        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'description' => fake()->sentence(10),
        ];
    }
}