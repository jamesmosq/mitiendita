<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Electrónica
            ['category' => 'Electrónica', 'name' => 'Audífonos Bluetooth Pro',   'price' => 89900,  'stock' => 25],
            ['category' => 'Electrónica', 'name' => 'Cargador Inalámbrico 15W',  'price' => 39900,  'stock' => 40],
            ['category' => 'Electrónica', 'name' => 'Smartwatch Fit Band',       'price' => 149900, 'stock' => 15],
            ['category' => 'Electrónica', 'name' => 'Parlante Portátil 20W',     'price' => 119900, 'stock' => 20],
            // Ropa
            ['category' => 'Ropa', 'name' => 'Camiseta Oversize Negra',  'price' => 29900, 'stock' => 50],
            ['category' => 'Ropa', 'name' => 'Sudadera Zip Gris',         'price' => 79900, 'stock' => 30],
            ['category' => 'Ropa', 'name' => 'Jean Slim Azul Oscuro',     'price' => 89900, 'stock' => 35],
            // Hogar
            ['category' => 'Hogar', 'name' => 'Set de Tazas Minimalista', 'price' => 49900, 'stock' => 20],
            ['category' => 'Hogar', 'name' => 'Lámpara de Escritorio LED', 'price' => 69900, 'stock' => 18],
            // Deportes
            ['category' => 'Deportes', 'name' => 'Botella Térmica 750ml',  'price' => 34900, 'stock' => 60],
            ['category' => 'Deportes', 'name' => 'Mochila Trail 30L',      'price' => 129900, 'stock' => 12],
            // Libros
            ['category' => 'Libros', 'name' => 'Clean Code - Robert Martin',  'price' => 59900, 'stock' => 10],
            ['category' => 'Libros', 'name' => 'Laravel Up & Running',        'price' => 79900, 'stock' => 8],
        ];

        foreach ($products as $data) {
            $category = Category::where('name', $data['category'])->first();
            Product::create([
                'category_id' => $category->id,
                'name'        => $data['name'],
                'slug'        => Str::slug($data['name']),
                'description' => "Descripción de {$data['name']}. Producto de alta calidad.",
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'active'      => true,
            ]);
        }
    }
}