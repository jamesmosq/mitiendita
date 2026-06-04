{{-- resources/views/shop/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Tienda — Latiendita')

@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Catálogo</h1>
        <p class="text-gray-500 mt-1">{{ $products->total() }} productos disponibles</p>
    </div>

    {{-- Filtro de categorías --}}
    <div class="flex gap-2 flex-wrap mb-8">
        <a href="{{ route('shop.index') }}"
           class="px-4 py-2 rounded-full text-sm border
                  {{ !request('categoria') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300' }}">
            Todos
        </a>
        @foreach($categories as $category)
            <a href="{{ route('shop.index', ['categoria' => $category->slug]) }}"
               class="px-4 py-2 rounded-full text-sm border
                      {{ request('categoria') === $category->slug ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300' }}">
                {{ $category->name }}
                <span class="opacity-60">({{ $category->products_count }})</span>
            </a>
        @endforeach
    </div>

    {{-- Grid de productos --}}
    @if($products->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-20 text-gray-400">
            <p class="text-5xl mb-4">🛍️</p>
            <p class="text-lg">No hay productos disponibles.</p>
        </div>
    @endif

@endsection