<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_confirmar_orden(): void
    {
        $user    = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create([
            'price'  => 50000,
            'stock'  => 10,
            'active' => true,
        ]);

        // Simular carrito en sesión
        $this->actingAs($user)
             ->withSession(['cart' => [
                 $product->id => ['quantity' => 2, 'price' => 50000],
             ]])
             ->post(route('orders.store'))
             ->assertRedirect();

        // Verificar que la orden se creó
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total'   => 100000,
            'status'  => 'pending',
        ]);

        // Verificar que el stock decrementó
        $this->assertEquals(8, $product->fresh()->stock);

        // Verificar que el order_item se creó
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity'   => 2,
            'subtotal'   => 100000,
        ]);
    }

    public function test_orden_falla_si_no_hay_stock_suficiente(): void
    {
        $user    = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create(['stock' => 1, 'active' => true]);

        $this->actingAs($user)
             ->withSession(['cart' => [
                 $product->id => ['quantity' => 5, 'price' => 10000],
             ]])
             ->post(route('orders.store'))
             ->assertRedirect(route('cart.index'))
             ->assertSessionHas('error');

        // El stock no debe cambiar
        $this->assertEquals(1, $product->fresh()->stock);

        // No se creó ninguna orden
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_cliente_no_puede_ver_orden_de_otro_usuario(): void
    {
        $user1 = User::factory()->create(['role' => 'customer']);
        $user2 = User::factory()->create(['role' => 'customer']);
        $order = Order::factory()->create(['user_id' => $user1->id]);

        $this->actingAs($user2)
             ->get(route('orders.show', $order))
             ->assertStatus(403);
    }

    public function test_admin_puede_ver_cualquier_orden(): void
    {
        $admin   = User::factory()->create(['role' => 'admin']);
        $cliente = User::factory()->create(['role' => 'customer']);
        $order   = Order::factory()->create(['user_id' => $cliente->id]);

        $this->actingAs($admin)
             ->get(route('orders.show', $order))
             ->assertStatus(200);
    }

    public function test_carrito_vacio_no_crea_orden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post(route('orders.store'))
             ->assertRedirect(route('cart.index'));

        $this->assertDatabaseCount('orders', 0);
    }
}