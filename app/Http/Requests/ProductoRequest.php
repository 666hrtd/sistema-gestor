<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Segunda barrera además del middleware: solo el admin escribe productos
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required', 'string', 'max:255',
                Rule::unique('productos', 'nombre')->ignore($this->route('producto')),
            ],
            'descripcion' => ['required', 'string'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'precio' => ['required', 'numeric', 'gt:0'],
            'existencias' => ['required', 'integer', 'min:0'],
            'estado' => ['required', Rule::in(['Disponible', 'Agotado'])],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe un producto con ese nombre.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'categoria_id.required' => 'Selecciona una categoría.',
            'categoria_id.exists' => 'La categoría no existe en el catálogo.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.gt' => 'El precio debe ser mayor que cero.',
            'existencias.required' => 'Las existencias son obligatorias.',
            'existencias.integer' => 'Las existencias deben ser un número entero.',
            'existencias.min' => 'Las existencias no pueden ser negativas.',
            'estado.in' => 'Selecciona un estado válido.',
        ];
    }
}