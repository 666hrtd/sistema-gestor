@props(['action', 'categorias', 'producto' => null, 'method' => 'POST', 'boton' => 'Guardar'])

<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <p>
        <label>Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre', $producto?->nombre) }}">
        @error('nombre') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <p>
        <label>Descripción</label>
        <textarea name="descripcion">{{ old('descripcion', $producto?->descripcion) }}</textarea>
        @error('descripcion') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <p>
        <label>Categoría</label>
        <select name="categoria_id">
            <option value="">-- Selecciona --</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}"
                    @selected(old('categoria_id', $producto?->categoria_id) == $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
        @error('categoria_id') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <p>
        <label>Precio</label>
        <input type="text" name="precio" value="{{ old('precio', $producto?->precio) }}">
        @error('precio') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <p>
        <label>Existencias</label>
        <input type="text" name="existencias" value="{{ old('existencias', $producto?->existencias) }}">
        @error('existencias') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <p>
        <label>Estado</label>
        <select name="estado">
            <option value="Disponible" @selected(old('estado', $producto?->estado) === 'Disponible')>Disponible</option>
            <option value="Agotado" @selected(old('estado', $producto?->estado) === 'Agotado')>Agotado</option>
        </select>
        @error('estado') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <button type="submit">{{ $boton }}</button>
    <a href="{{ route('productos.index') }}">Cancelar</a>
</form>