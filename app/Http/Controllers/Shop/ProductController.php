<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // Lista todos los productos activos
    public function index()
    {
        $products = Product::with('category')
            ->where('active', true)
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('products')->get();

        return view('shop.index', compact('products', 'categories'));
    }

    // Muestra el detalle de un producto
    public function show(Product $product)
    {
        // Si el producto no está activo, 404
        abort_if(!$product->active, 404);

        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('active', true)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }
}