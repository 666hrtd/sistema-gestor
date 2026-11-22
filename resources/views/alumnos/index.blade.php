<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alumnos</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <h1>Alumnos</h1>

    <p><a href="{{ route('dashboard') }}">Volver al dashboard</a></p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <p><a href="{{ route('alumnos.create') }}">Registrar alumno</a></p>

    <p>
        Ordenar:
        <a href="{{ route('alumnos.index', ['orden' => 'nombre', 'dir' => 'asc']) }}">Nombre A-Z</a> |
        <a href="{{ route('alumnos.index', ['orden' => 'nombre', 'dir' => 'desc']) }}">Nombre Z-A</a> |
        <a href="{{ route('alumnos.index', ['orden' => 'calificacion', 'dir' => 'desc']) }}">Calificación mayor a menor</a> |
        <a href="{{ route('alumnos.index', ['orden' => 'calificacion', 'dir' => 'asc']) }}">Calificación menor a mayor</a> |
        <a href="{{ route('alumnos.index', ['orden' => 'faltas', 'dir' => 'desc']) }}">Faltas mayor a menor</a> |
        <a href="{{ route('alumnos.index', ['orden' => 'faltas', 'dir' => 'asc']) }}">Faltas menor a mayor</a>
    </p>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Faltas</th>
                <th>Calificación</th>
                <th>Equivalencia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($alumnos as $alumno)
                <tr>
                    <td>{{ $alumno->user->name }}</td>
                    <td>{{ $alumno->faltas }}</td>
                    <td>{{ $alumno->calificacion }}</td>
                    <td>{{ $alumno->equivalencia }}</td>
                    <td>
                        <a href="{{ route('alumnos.edit', $alumno) }}">Editar</a>

                        <form method="POST" action="{{ route('alumnos.destroy', $alumno) }}" style="display:inline"
                              onsubmit="return confirm('¿Eliminar este alumno?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No hay alumnos</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>