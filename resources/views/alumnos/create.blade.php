<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar alumno</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <h1>Registrar alumno</h1>

    <form method="POST" action="{{ route('alumnos.store') }}">
        @csrf

        <p>
            <label>Nombre</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <span>{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Correo</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <span>{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Contraseña</label>
            <input type="password" name="password">
            @error('password') <span>{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Faltas</label>
            <input type="number" name="faltas" value="{{ old('faltas', 0) }}">
            @error('faltas') <span>{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Calificación (0 a 10)</label>
            <input type="number" step="0.01" name="calificacion" value="{{ old('calificacion') }}">
            @error('calificacion') <span>{{ $message }}</span> @enderror
        </p>

        <button type="submit">Guardar</button>
        <a href="{{ route('alumnos.index') }}">Cancelar</a>
    </form>
</body>
</html>