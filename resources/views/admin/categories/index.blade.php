@extends('layouts.app')
@section('title', 'Categorías — Admin')
@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Categorías</h1>
        <a href="{{ route('admin.categories.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            + Nueva categoría
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Nombre</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Slug</th>
                    <th class="text-center px-6 py-3 font-medium text-gray-500">Productos</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-gray-400 font-mono">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full text-xs">
                                {{ $category->products_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="text-indigo-600 hover:underline">Editar</a>

                                <form method="POST"
                                      action="{{ route('admin.categories.destroy', $category) }}"
                                      onsubmit="return confirm('¿Eliminar esta categoría?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                            No hay categorías aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>

@endsection