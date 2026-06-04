<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_no_autenticado_no_puede_ver_carrito(): void
    {
        $this->get(route('cart.index'))
             ->assertRedirect(route('login'));
    }

    public function test_usuario_puede_agregar_producto_al_carrito(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'active' => true]);

        $response = $this->actingAs($user)
                         ->post(route('cart.add', $product), ['quantity' => 2]);

        $response->assertRedirect();
        $response->assertSessionHas('cart');

        $cart = session('cart');
        $this->assertArrayHasKey($product->id, $cart);
        $this->assertEquals(2, $cart[$product->id]['quantity']);
    }

    public function test_no_se_puede_agregar_mas_del_stock_disponible(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock' => 3, 'active' => true]);

        $response = $this->actingAs($user)
                         ->post(route('cart.add', $product), ['quantity' => 10]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_usuario_puede_eliminar_producto_del_carrito(): void
    {
        $user    = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'active' => true]);

        // Primero agregar
        $this->actingAs($user)
             ->post(route('cart.add', $product), ['quantity' => 1]);

        // Luego eliminar
        $response = $this->actingAs($user)
                         ->delete(route('cart.remove', $product));

        $response->assertRedirect();
        $cart = session('cart', []);
        $this->assertArrayNotHasKey($product->id, $cart);
    }
}
