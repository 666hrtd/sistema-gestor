<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservaciones</title>
</head>
<body>
    <x-barra-usuario />

    <h1>{{ auth()->user()->role === 'admin' ? 'Todas las reservaciones' : 'Mis reservaciones' }}</h1>

    <p>
        <a href="{{ route('dashboard') }}">Volver al dashboard</a> |
        <a href="{{ route('equipos.index') }}">Equipos</a>
    </p>

    <x-alerta />

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                @if (auth()->user()->role === 'admin')
                    <th>Usuario</th>
                @endif
                <th>Equipo</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reservaciones as $reservacion)
                <tr>
                    @if (auth()->user()->role === 'admin')
                        <td>{{ $reservacion->user->name }}</td>
                    @endif
                    <td>{{ $reservacion->equipo->nombre }}</td>
                    <td>{{ $reservacion->estado }}</td>
                    <td>{{ $reservacion->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if ($reservacion->estado === 'activa')
                            <form method="POST" action="{{ route('reservaciones.destroy', $reservacion) }}" style="display:inline"
                                  onsubmit="return confirm('¿Cancelar esta reservación?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Cancelar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No hay reservaciones</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>