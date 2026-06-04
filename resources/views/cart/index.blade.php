@extends('layouts.app')
@section('title', 'Mi carrito')
@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Mi carrito</h1>

    @if($cart && count($cart))

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
            @foreach($cart as $productId => $item)
                @php $product = $products->get($productId) @endphp
                @if($product)
                <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-100 last:border-0">

                    {{-- Imagen --}}
                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl">📦</div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 truncate">{{ $product->name }}</p>
                        <p class="text-sm text-gray-400">{{ $product->category->name }}</p>
                        <p class="text-sm font-medium text-indigo-600 mt-1">
                            ${{ number_format($item['price'], 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Cantidad --}}
                    <form method="POST" action="{{ route('cart.update', $product) }}"
                          class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity"
                               value="{{ $item['quantity'] }}"
                               min="1" max="{{ $product->stock }}"
                               class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-sm text-center">
                        <button class="text-xs text-indigo-600 hover:underline">
                            Actualizar
                        </button>
                    </form>

                    {{-- Subtotal --}}
                    <div class="text-right w-28 shrink-0">
                        <p class="font-bold text-gray-900">
                            ${{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Eliminar --}}
                    <form method="POST" action="{{ route('cart.remove', $product) }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-400 hover:text-red-600 text-lg"
                                title="Eliminar">✕</button>
                    </form>
                </div>
                @endif
            @endforeach
        </div>

        {{-- Resumen y confirmar --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex justify-between text-lg font-bold mb-6">
                <span>Total</span>
                <span>${{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <form method="POST" action="{{ route('orders.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Notas del pedido (opcional)
                    </label>
                    <textarea name="notes" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                     focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                              placeholder="Instrucciones de entrega, color, talla..."></textarea>
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg font-medium
                               hover:bg-indigo-700 transition">
                    Confirmar pedido
                </button>
            </form>

            <form method="POST" action="{{ route('cart.clear') }}" class="mt-3">
                @csrf
                @method('DELETE')
                <button class="w-full text-sm text-gray-400 hover:text-red-500 py-2">
                    Vaciar carrito
                </button>
            </form>
        </div>

    @else
        <div class="text-center py-20 text-gray-400">
            <p class="text-5xl mb-4">🛒</p>
            <p class="text-lg mb-6">Tu carrito está vacío.</p>
            <a href="{{ route('shop.index') }}"
               class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                Ver productos
            </a>
        </div>
    @endif
</div>

@endsection