<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de partidas</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <x-barra-usuario />

    <h1>{{ auth()->user()->role === 'admin' ? 'Historial general' : 'Mi historial' }}</h1>

    <p><a href="{{ route('partidas.index') }}">Volver al lobby</a></p>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>#</th>
                <th>Jugador X</th>
                <th>Jugador O</th>
                <th>Resultado</th>
                <th>Fecha</th>
                <th>Ver</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($partidas as $partida)
                <tr>
                    <td>{{ $partida->id }}</td>
                    <td>{{ $partida->jugadorX->name }}</td>
                    <td>{{ $partida->jugadorO?->name ?? '-' }}</td>
                    <td>
                        @if ($partida->estado === 'ganada')
                            Ganó {{ $partida->ganador->name }}
                        @elseif ($partida->estado === 'empate')
                            Empate
                        @elseif ($partida->estado === 'abandonada')
                            Abandonada @if ($partida->ganador) (ganó {{ $partida->ganador->name }}) @endif
                        @else
                            Cancelada por el administrador
                        @endif
                    </td>
                    <td>{{ $partida->updated_at->format('d/m/Y H:i') }}</td>
                    <td><a href="{{ route('partidas.show', $partida) }}">Ver tablero</a></td>
                </tr>
            @empty
                <tr><td colspan="6">Todavía no hay partidas terminadas</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>