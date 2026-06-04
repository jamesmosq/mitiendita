<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake('es_CO')->words(3, true);

        return [
            'category_id' => Category::factory(), // crea una categoría si no existe
            'name'        => ucfirst($name),
            'slug'        => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'description' => fake()->paragraph(3),
            'price'       => fake()->randomElement([
                9900, 19900, 29900, 49900, 89900, 129900, 249900, 499900
            ]),
            'stock'       => fake()->numberBetween(0, 100),
            'image'       => null,
            'active'      => fake()->boolean(85), // 85% de probabilidad de estar activo
        ];
    }

    // Estado: producto agotado
    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }

    // Estado: producto inactivo
    public function inactive(): static
    {
        return $this->state(['active' => false]);
    }
}