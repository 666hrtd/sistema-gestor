<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        // Validamos los filtros que llegan por la URL
        $request->validate([
            'buscar' => ['nullable', 'string', 'max:255'],
            'categoria_id' => ['nullable', 'exists:categorias,id'],
            'precio_min' => ['nullable', 'numeric', 'min:0'],
            'precio_max' => ['nullable', 'numeric', 'min:0'],
            'estado' => ['nullable', Rule::in(['Disponible', 'Agotado'])],
        ]);

        // Solo se aceptan estas cantidades por página
        $porPagina = in_array((int) $request->por_pagina, [10, 15, 25, 50])
            ? (int) $request->por_pagina
            : 10;

        // Lista permitida de ordenamientos
        $ordenes = [
            'recientes' => ['created_at', 'desc'],
            'antiguos' => ['created_at', 'asc'],
            'nombre_asc' => ['nombre', 'asc'],
            'nombre_desc' => ['nombre', 'desc'],
            'precio_desc' => ['precio', 'desc'],
            'precio_asc' => ['precio', 'asc'],
            'existencias_desc' => ['existencias', 'desc'],
            'existencias_asc' => ['existencias', 'asc'],
        ];
        [$columna, $direccion] = $ordenes[$request->orden] ?? $ordenes['recientes'];

        $productos = Producto::with('categoria')
            ->when($request->filled('buscar'), fn ($q) => $q->where('nombre', 'like', '%' . $request->buscar . '%'))
            ->when($request->filled('categoria_id'), fn ($q) => $q->where('categoria_id', $request->categoria_id))
            ->when($request->filled('precio_min'), fn ($q) => $q->where('precio', '>=', $request->precio_min))
            ->when($request->filled('precio_max'), fn ($q) => $q->where('precio', '<=', $request->precio_max))
            ->when($request->filled('estado'), fn ($q) => $q->where('estado', $request->estado))
            ->orderBy($columna, $direccion)
            ->paginate($porPagina)
            ->withQueryString();

        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias', 'porPagina'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.create', compact('categorias'));
    }

    public function store(ProductoRequest $request)
    {
        Producto::create($request->validated());

        return redirect()->route('productos.index')->with('success', 'Producto registrado');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()->route('productos.index')->with('success', 'Producto actualizado');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado');
    }
}