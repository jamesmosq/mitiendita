<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase; // borra y re-migra la BD en cada test

    public function test_catalogo_carga_correctamente(): void
    {
        // Arrange: crear datos
        $category = Category::factory()->create(['name' => 'Electrónica', 'slug' => 'electronica']);
        Product::factory(6)->create(['category_id' => $category->id, 'active' => true]);
        Product::factory(2)->inactive()->create(['category_id' => $category->id]);

        // Act: hacer la petición
        $response = $this->get(route('shop.index'));

        // Assert: verificar resultado
        $response->assertStatus(200);
        $response->assertViewIs('shop.index');
        $response->assertViewHas('products', function ($products) {
            return $products->total() === 6; // solo los activos
        });
    }

    public function test_detalle_producto_activo_carga(): void
    {
        $product = Product::factory()->create(['active' => true]);

        $response = $this->get(route('shop.show', $product));

        $response->assertStatus(200);
        $response->assertViewIs('shop.show');
        $response->assertSee($product->name);
    }

    public function test_producto_inactivo_retorna_404(): void
    {
        $product = Product::factory()->inactive()->create();

        $response = $this->get(route('shop.show', $product));

        $response->assertStatus(404);
    }
}
