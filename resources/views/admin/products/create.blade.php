@extends('layouts.app')
@section('title', 'Nuevo producto')
@section('content')

    <div class="max-w-2xl">
        <h1 class="text-2xl font-bold mb-6">Nuevo producto</h1>

        <form method="POST" action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data"
              class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            @csrf

            {{-- Nombre --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-500
                              @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Categoría --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                <select name="category_id"
                        class="w-full border rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500
                               @error('category_id') border-red-400 @enderror">
                    <option value="">Selecciona una categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="description" rows="3"
                          class="w-full border rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
            </div>

            {{-- Precio y Stock en dos columnas --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                    <input type="number" name="price" step="0.01"
                           value="{{ old('price') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500
                                  @error('price') border-red-400 @enderror">
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock"
                           value="{{ old('stock', 0) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500
                                  @error('stock') border-red-400 @enderror">
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Imagen --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Imagen del producto</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-gray-500
                              @error('image') border border-red-400 rounded-lg p-2 @enderror">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Activo --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="active" value="1" id="active"
                       {{ old('active', true) ? 'checked' : '' }}
                       class="rounded border-gray-300">
                <label for="active" class="text-sm text-gray-700">
                    Producto activo (visible en la tienda)
                </label>
            </div>

            {{-- Botones --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    Guardar
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="px-5 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

@endsection