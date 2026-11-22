<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Juego del gato</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <x-barra-usuario />

    <h1>Juego del gato</h1>

    <p>
        <a href="{{ route('dashboard') }}">Volver al dashboard</a> |
        <a href="{{ route('partidas.historial') }}">
            {{ auth()->user()->role === 'admin' ? 'Historial general' : 'Mi historial' }}
        </a>
    </p>

    <x-alerta />

    @if (auth()->user()->role === 'jugador')
        <form method="POST" action="{{ route('partidas.store') }}">
            @csrf
            <button type="submit">Crear nueva partida</button>
        </form>
    @endif

    <h2>{{ auth()->user()->role === 'admin' ? 'Partidas activas' : 'Mis partidas activas' }}</h2>
    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>#</th>
                <th>Jugador X</th>
                <th>Jugador O</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activas as $partida)
                <tr>
                    <td>{{ $partida->id }}</td>
                    <td>{{ $partida->jugadorX->name }}</td>
                    <td>{{ $partida->jugadorO?->name ?? 'Esperando rival' }}</td>
                    <td>{{ $partida->estado }}</td>
                    <td>
                        <a href="{{ route('partidas.show', $partida) }}">
                            {{ auth()->user()->role === 'admin' ? 'Ver' : 'Entrar' }}
                        </a>

                        @if (auth()->user()->role === 'admin')
                            <form method="POST" action="{{ route('partidas.cancelar', $partida) }}" style="display:inline"
                                  onsubmit="return confirm('¿Cancelar esta partida?')">
                                @csrf
                                <button type="submit">Cancelar partida</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No hay partidas activas</td></tr>
            @endforelse
        </tbody>
    </table>

    @if (auth()->user()->role === 'jugador')
        <h2>Partidas disponibles para unirse</h2>
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Creada por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($disponibles as $partida)
                    <tr>
                        <td>{{ $partida->id }}</td>
                        <td>{{ $partida->jugadorX->name }}</td>
                        <td>
                            <form method="POST" action="{{ route('partidas.unirse', $partida) }}" style="display:inline">
                                @csrf
                                <button type="submit">Unirme</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No hay partidas esperando rival</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <h2>Jugadores disponibles</h2>
    <ul>
        @forelse ($jugadores as $jugador)
            <li>{{ $jugador->name }}</li>
        @empty
            <li>No hay otros jugadores libres</li>
        @endforelse
    </ul>
</body>
</html>