<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

            // store ahora recibe el Form Request en lugar de Request genérico
        public function store(StoreCategoryRequest $request)
        {
            // $request->validated() solo contiene los campos que pasaron las reglas
            Category::create([
                ...$request->validated(),
                'slug' => Str::slug($request->name),
            ]);

            return redirect()->route('admin.categories.index')
                            ->with('success', 'Categoría creada correctamente.');
        }


    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

        public function update(UpdateCategoryRequest $request, Category $category)
        {
            $category->update([
                ...$request->validated(),
                'slug' => Str::slug($request->name),
            ]);

            return redirect()->route('admin.categories.index')
                            ->with('success', 'Categoría actualizada.');
        }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Categoría eliminada.');
    }
}