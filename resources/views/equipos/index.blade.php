<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Equipos</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <x-barra-usuario />

    <h1>Equipos</h1>

    <p>
        <a href="{{ route('dashboard') }}">Volver al dashboard</a> |
        <a href="{{ route('reservaciones.index') }}">
            {{ auth()->user()->role === 'admin' ? 'Todas las reservaciones' : 'Mis reservaciones' }}
        </a>
    </p>

    <x-alerta />

    @if (auth()->user()->role === 'admin')
        <p><a href="{{ route('equipos.create') }}">Registrar equipo</a></p>
    @endif

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($equipos as $equipo)
                <tr>
                    <td>{{ $equipo->nombre }}</td>
                    <td>{{ $equipo->descripcion }}</td>
                    <td>{{ $equipo->disponible ? 'Disponible' : 'Reservado' }}</td>
                    <td>
                        @if (auth()->user()->role === 'estudiante' && $equipo->disponible)
                            <form method="POST" action="{{ route('reservaciones.store', $equipo) }}" style="display:inline">
                                @csrf
                                <button type="submit">Reservar</button>
                            </form>
                        @endif

                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('equipos.edit', $equipo) }}">Editar</a>

                            <form method="POST" action="{{ route('equipos.destroy', $equipo) }}" style="display:inline"
                                  onsubmit="return confirm('¿Eliminar este equipo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Eliminar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No hay equipos disponibles</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>