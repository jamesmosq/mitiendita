<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
// StoreProductRequest.php
public function authorize(): bool
{
    // Solo admin puede crear productos
    return auth()->check() && auth()->user()->isAdmin();
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'category_id' => ['required', 'exists:categories,id'],
        'name'        => ['required', 'string', 'max:150', 'unique:products,name'],
        'description' => ['nullable', 'string'],
        'price'       => ['required', 'numeric', 'min:0'],
        'stock'       => ['required', 'integer', 'min:0'],
        'image'       => ['nullable', 'image', 'max:2048'], // max 2MB
        'active'      => ['boolean'],
        ];
    }

    public function messages(): array
        {
            return [
                'category_id.required' => 'Debes seleccionar una categoría.',
                'category_id.exists'   => 'La categoría seleccionada no existe.',
                'name.required'        => 'El nombre del producto es obligatorio.',
                'name.unique'          => 'Ya existe un producto con ese nombre.',
                'price.min'            => 'El precio no puede ser negativo.',
                'stock.min'            => 'El stock no puede ser negativo.',
                'image.image'          => 'El archivo debe ser una imagen.',
                'image.max'            => 'La imagen no puede superar 2MB.',
            ];
        }

    
}
