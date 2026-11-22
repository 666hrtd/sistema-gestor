<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar alumno</title>
</head>
<body>
    <h1>Editar alumno</h1>

    <form method="POST" action="{{ route('alumnos.update', $alumno) }}">
        @csrf
        @method('PUT')

        <p>
            <label>Nombre</label>
            <input type="text" name="name" value="{{ old('name', $alumno->user->name) }}">
            @error('name') <span>{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Correo</label>
            <input type="email" name="email" value="{{ old('email', $alumno->user->email) }}">
            @error('email') <span>{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Faltas</label>
            <input type="number" name="faltas" value="{{ old('faltas', $alumno->faltas) }}">
            @error('faltas') <span>{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Calificación (0 a 10)</label>
            <input type="number" step="0.01" name="calificacion" value="{{ old('calificacion', $alumno->calificacion) }}">
            @error('calificacion') <span>{{ $message }}</span> @enderror
        </p>

        <button type="submit">Actualizar</button>
        <a href="{{ route('alumnos.index') }}">Cancelar</a>
    </form>
</body>
</html>