<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Order::class);

        $orders = auth()->user()->isAdmin()
            ? Order::with('user')->latest()->paginate(20)
            : auth()->user()->orders()->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items.product', 'user');

        return view('orders.show', compact('order'));
    }

    // Confirmar la compra — el corazón de la app
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                             ->with('error', 'Tu carrito está vacío.');
        }

        // Cargar los productos del carrito de una sola query
        $productIds = array_keys($cart);
        $products   = Product::whereIn('id', $productIds)
                              ->lockForUpdate() // bloqueo pesimista → evita race conditions
                              ->get()
                              ->keyBy('id');

        // Verificar stock ANTES de abrir la transacción
        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);

            if (!$product || !$product->active) {
                return redirect()->route('cart.index')
                                 ->with('error', "Un producto ya no está disponible.");
            }

            if ($product->stock < $item['quantity']) {
                return redirect()->route('cart.index')
                                 ->with('error',
                                     "Stock insuficiente para '{$product->name}'. " .
                                     "Disponible: {$product->stock}.");
            }
        }

        // Todo bien → abrimos la transacción
        $order = DB::transaction(function () use ($cart, $products, $request) {

            // 1. Calcular el total
            $total = collect($cart)->sum(
                fn($item) => $item['price'] * $item['quantity']
            );

            // 2. Crear la orden
            $order = Order::create([
                'user_id' => auth()->id(),
                'status'  => 'pending',
                'total'   => $total,
                'notes'   => $request->notes,
            ]);

            // 3. Crear los order_items y decrementar stock
            foreach ($cart as $productId => $item) {
                $product = $products->get($productId);

                $order->items()->create([
                    'product_id' => $productId,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal'   => $item['price'] * $item['quantity'],
                ]);

                // Decrementar stock directamente en la BD (no $product->stock -= n)
                $product->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        // 4. Limpiar el carrito solo si la transacción fue exitosa
        session()->forget('cart');

        return redirect()->route('orders.show', $order)
                         ->with('success', '¡Orden confirmada! Tu pedido está en camino.');
    }

    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

        DB::transaction(function () use ($order) {
            // Restaurar el stock de cada producto
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);
        });

        return redirect()->route('orders.show', $order)
                         ->with('success', 'Orden cancelada y stock restaurado.');
    }
}