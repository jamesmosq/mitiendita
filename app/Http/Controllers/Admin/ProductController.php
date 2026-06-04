<?php

// app/Http/Controllers/Admin/ProductController.php
namespace App\Http\Controllers\Admin;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
{
    $data = $request->validated();
    $data['slug']   = Str::slug($request->name);
    $data['active'] = $request->boolean('active');

    // Subida de imagen
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')
                                 ->store('products', 'public');
    }

    Product::create($data);

    return redirect()->route('admin.products.index')
                     ->with('success', 'Producto creado.');
}

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
{
    $data = $request->validated();
    $data['slug']   = Str::slug($request->name);
    $data['active'] = $request->boolean('active');

    if ($request->hasFile('image')) {
        // Eliminar imagen anterior si existe
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }
        $data['image'] = $request->file('image')
                                 ->store('products', 'public');
    }

    $product->update($data);

    return redirect()->route('admin.products.index')
                     ->with('success', 'Producto actualizado.');
}

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
                         ->with('success', 'Producto eliminado.');
    }
}