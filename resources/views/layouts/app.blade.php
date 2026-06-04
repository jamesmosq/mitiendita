{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Latiendita')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('shop.index') }}"
               class="text-xl font-bold text-indigo-600">
                Latiendita
            </a>

            {{-- Links de navegación --}}
            <div class="flex items-center gap-6 text-sm">

                <a href="{{ route('shop.index') }}"
                   class="text-gray-600 hover:text-indigo-600">Tienda</a>

                @auth
                    {{-- Carrito con contador --}}
                    @php $cartCount = collect(session('cart', []))->sum('quantity') @endphp
                    <a href="{{ route('cart.index') }}"
                       class="relative text-gray-600 hover:text-indigo-600">
                        Carrito
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-3 bg-indigo-600 text-white
                                         text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                {{ $cartCount > 9 ? '9+' : $cartCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Solo admin ve este link --}}
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.products.index') }}"
                           class="text-gray-600 hover:text-indigo-600">Admin</a>
                    @endif

                    {{-- Cerrar sesión --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-gray-600 hover:text-red-500">
                            Salir
                        </button>
                    </form>

                @else
                    <a href="{{ route('login') }}"
                       class="text-gray-600 hover:text-indigo-600">Entrar</a>
                    <a href="{{ route('register') }}"
                       class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg hover:bg-indigo-700">
                        Registrarse
                    </a>
                @endauth

            </div>
        </div>
    </nav>

    {{-- Mensajes flash --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800
                        rounded-lg px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-800
                        rounded-lg px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Contenido de cada página --}}
    <main class="max-w-7xl mx-auto px-4 py-8">
        @yield('content')
    </main>

</body>
</html>