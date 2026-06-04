{{-- create.blade.php --}}
@extends('layouts.app')
@section('title', 'Nueva categoría')
@section('content')

    <div class="max-w-lg">
        <h1 class="text-2xl font-bold mb-6">Nueva categoría</h1>

        <form method="POST" action="{{ route('admin.categories.store') }}"
              class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-indigo-500
                              @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    Guardar
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="px-5 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-50">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

@endsection