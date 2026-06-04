{{-- resources/views/components/product-card.blade.php --}}
@props(['product'])

<a href="{{ route('shop.show', $product) }}"
   class="group block bg-white rounded-xl border border-gray-200
          hover:shadow-md hover:border-indigo-200 transition-all duration-200">

    {{-- Imagen --}}
    <div class="aspect-square bg-gray-100 rounded-t-xl overflow-hidden">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-300 text-4xl">
                📦
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="p-4">
        <span class="text-xs text-indigo-500 font-medium">
            {{ $product->category->name }}
        </span>
        <h3 class="font-medium text-gray-900 mt-1 group-hover:text-indigo-600">
            {{ $product->name }}
        </h3>
        <div class="flex items-center justify-between mt-3">
            <span class="text-lg font-bold text-gray-900">
                ${{ number_format($product->price, 0, ',', '.') }}
            </span>
            @if($product->stock > 0)
                <span class="text-xs bg-green-50 text-green-700 px-2 py-1 rounded-full">
                    En stock
                </span>
            @else
                <span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-full">
                    Agotado
                </span>
            @endif
        </div>
    </div>

</a>