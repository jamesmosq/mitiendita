@extends('layouts.app')
@section('title', 'Productos — Admin')
@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Productos</h1>
        <a href="{{ route('admin.products.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            + Nuevo producto
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Producto</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Categoría</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Precio</th>
                    <th class="text-center px-6 py-3 font-medium text-gray-500">Stock</th>
                    <th class="text-center px-6 py-3 font-medium text-gray-500">Activo</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Imagen miniatura --}}
                                <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-lg">📦</div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $product->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $product->category->name }}
                        </td>
                        <td class="px-6 py-4 font-medium">
                            ${{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                {{ $product->stock === 0 ? 'bg-red-50 text-red-600' :
                                   ($product->stock <= 5 ? 'bg-yellow-50 text-yellow-600' :
                                   'bg-green-50 text-green-700') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($product->active)
                                <span class="bg-green-50 text-green-700 text-xs px-2 py-0.5 rounded-full">Activo</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="text-indigo-600 hover:underline">Editar</a>

                                <form method="POST"
                                      action="{{ route('admin.products.destroy', $product) }}"
                                      onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                            No hay productos aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>

@endsection