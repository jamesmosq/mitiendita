@auth
    <form method="POST" action="{{ route('cart.add', $product) }}" class="flex gap-3 mt-6">
        @csrf
        <input type="number" name="quantity" value="1"
               min="1" max="{{ $product->stock }}"
               class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center text-sm">
        <button type="submit"
                class="flex-1 bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700
                       font-medium transition disabled:opacity-50"
                {{ $product->stock === 0 ? 'disabled' : '' }}>
            {{ $product->stock > 0 ? 'Agregar al carrito' : 'Sin stock' }}
        </button>
    </form>
@else
    <a href="{{ route('login') }}"
       class="block mt-6 text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
        Inicia sesión para comprar
    </a>
@endauth