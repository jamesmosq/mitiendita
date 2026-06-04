<?php

// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Ver el carrito
    public function index()
    {
        $cart     = $this->getCart();
        $products = $this->getCartProducts($cart);
        $total    = $this->calcTotal($cart);

        return view('cart.index', compact('cart', 'products', 'total'));
    }

    // Agregar producto al carrito
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        abort_if(!$product->active, 404);

        if ($product->stock < $request->quantity) {
            return back()->with('error',
                "Solo hay {$product->stock} unidades disponibles.");
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            // Si ya existe en el carrito, suma la cantidad
            $newQty = $cart[$product->id]['quantity'] + $request->quantity;

            if ($newQty > $product->stock) {
                return back()->with('error',
                    "No puedes agregar más de {$product->stock} unidades.");
            }

            $cart[$product->id]['quantity'] = $newQty;
        } else {
            $cart[$product->id] = [
                'quantity' => $request->quantity,
                'price'    => $product->price,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', "'{$product->name}' agregado al carrito.");
    }

    // Actualizar cantidad de un producto
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$product->id])) {
            return back()->with('error', 'Producto no encontrado en el carrito.');
        }

        if ($request->quantity > $product->stock) {
            return back()->with('error',
                "Solo hay {$product->stock} unidades disponibles.");
        }

        $cart[$product->id]['quantity'] = $request->quantity;
        session()->put('cart', $cart);

        return back()->with('success', 'Cantidad actualizada.');
    }

    // Eliminar un producto del carrito
    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    // Vaciar el carrito completo
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('shop.index')
                         ->with('success', 'Carrito vaciado.');
    }

    // -----------------------------------------------
    // Helpers privados
    // -----------------------------------------------
    private function getCart(): array
    {
        return session()->get('cart', []);
    }

    private function getCartProducts(array $cart): \Illuminate\Support\Collection
    {
        if (empty($cart)) {
            return collect();
        }
        return Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
    }

    private function calcTotal(array $cart): float
    {
        return collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }
}