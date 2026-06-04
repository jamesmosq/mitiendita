<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electrónica',   'description' => 'Gadgets, teléfonos y accesorios tecnológicos'],
            ['name' => 'Ropa',          'description' => 'Moda para hombre, mujer y niños'],
            ['name' => 'Hogar',         'description' => 'Muebles, decoración y utensilios'],
            ['name' => 'Deportes',      'description' => 'Equipos y ropa deportiva'],
            ['name' => 'Libros',        'description' => 'Literatura, técnica y educativa'],
            ['name' => 'Juguetes',      'description' => 'Para todas las edades'],
            ['name' => 'Belleza',       'description' => 'Cosméticos y cuidado personal'],
            ['name' => 'Herramientas',  'description' => 'Para el hogar y el taller'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'description' => $cat['description'],
            ]);
        }
    }
}