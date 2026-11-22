<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar equipo</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <x-barra-usuario />

    <h1>Registrar equipo</h1>

    <form method="POST" action="{{ route('equipos.store') }}">
        @csrf

        <p>
            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}">
            @error('nombre') <span style="color: red;">{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Descripción</label>
            <input type="text" name="descripcion" value="{{ old('descripcion') }}">
            @error('descripcion') <span style="color: red;">{{ $message }}</span> @enderror
        </p>

        <button type="submit">Guardar</button>
        <a href="{{ route('equipos.index') }}">Cancelar</a>
    </form>
</body>
</html>