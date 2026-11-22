<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <style>
        .pagination { list-style: none; display: flex; gap: 8px; padding: 0; }
    </style>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <h1>Productos</h1>

    <p><a href="{{ route('dashboard') }}">Volver al dashboard</a></p>

    <x-alerta />

    @if (auth()->user()->role === 'admin')
        <p><a href="{{ route('productos.create') }}">Registrar producto</a></p>
    @endif

    <form method="GET" action="{{ route('productos.index') }}">
        <p>
            <input type="text" name="buscar" placeholder="Buscar por nombre" value="{{ request('buscar') }}">

            <select name="categoria_id">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="precio_min" placeholder="Precio mínimo" size="10" value="{{ request('precio_min') }}">
            <input type="text" name="precio_max" placeholder="Precio máximo" size="10" value="{{ request('precio_max') }}">

            <select name="estado">
                <option value="">Todos los estados</option>
                <option value="Disponible" @selected(request('estado') === 'Disponible')>Disponible</option>
                <option value="Agotado" @selected(request('estado') === 'Agotado')>Agotado</option>
            </select>
        </p>

        <p>
            <select name="orden">
                <option value="recientes" @selected(request('orden', 'recientes') === 'recientes')>Más recientes</option>
                <option value="antiguos" @selected(request('orden') === 'antiguos')>Más antiguos</option>
                <option value="nombre_asc" @selected(request('orden') === 'nombre_asc')>Nombre A-Z</option>
                <option value="nombre_desc" @selected(request('orden') === 'nombre_desc')>Nombre Z-A</option>
                <option value="precio_desc" @selected(request('orden') === 'precio_desc')>Precio mayor a menor</option>
                <option value="precio_asc" @selected(request('orden') === 'precio_asc')>Precio menor a mayor</option>
                <option value="existencias_desc" @selected(request('orden') === 'existencias_desc')>Existencias mayor a menor</option>
                <option value="existencias_asc" @selected(request('orden') === 'existencias_asc')>Existencias menor a mayor</option>
            </select>

            <select name="por_pagina">
                @foreach ([10, 15, 25, 50] as $cantidad)
                    <option value="{{ $cantidad }}" @selected($porPagina === $cantidad)>{{ $cantidad }} por página</option>
                @endforeach
            </select>

            <button type="submit">Filtrar</button>
            <a href="{{ route('productos.index') }}">Limpiar filtros</a>
        </p>
    </form>

    @foreach (['buscar', 'categoria_id', 'precio_min', 'precio_max', 'estado'] as $campo)
        @error($campo)
            <p style="color: red;">{{ $message }}</p>
        @enderror
    @endforeach

    <p>
        Total encontrados: <strong>{{ $productos->total() }}</strong> |
        Página {{ $productos->currentPage() }} de {{ $productos->lastPage() }}
    </p>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Existencias</th>
                <th>Estado</th>
                <th>Fecha de registro</th>
                @if (auth()->user()->role === 'admin')
                    <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>{{ $producto->categoria->nombre }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
                    <td>{{ $producto->existencias }}</td>
                    <td>{{ $producto->estado }}</td>
                    <td>{{ $producto->created_at->format('d/m/Y') }}</td>
                    @if (auth()->user()->role === 'admin')
                        <td>
                            <a href="{{ route('productos.edit', $producto) }}">Editar</a>

                            <form method="POST" action="{{ route('productos.destroy', $producto) }}" style="display:inline"
                                  onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="9">No se encontraron productos</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $productos->links('pagination.simple') }}
    </div>
</body>
</html>