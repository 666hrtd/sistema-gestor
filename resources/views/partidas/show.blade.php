

@php
    $yo = auth()->id();
    $miSimbolo = $yo === $partida->jugador_x_id ? 'X' : ($yo === $partida->jugador_o_id ? 'O' : null);
    $activa = in_array($partida->estado, ['esperando', 'en_curso']);
    $miTurno = $partida->estado === 'en_curso' && $miSimbolo === $partida->turno;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Partida #{{ $partida->id }}</title>
    <style>
        .tablero { border-collapse: collapse; }
        .tablero td { border: 2px solid #000; width: 80px; height: 80px; text-align: center; font-size: 36px; }
        .tablero button { width: 80px; height: 80px; font-size: 20px; cursor: pointer; }
    </style>
</head>
<body>
    <x-barra-usuario />

    <h1>Partida #{{ $partida->id }}</h1>

    <p><a href="{{ route('partidas.index') }}">Volver al lobby</a></p>

    <x-alerta />

    <p>
        <strong>X:</strong> {{ $partida->jugadorX->name }} |
        <strong>O:</strong> {{ $partida->jugadorO?->name ?? 'Esperando rival...' }}
    </p>

    @if ($miSimbolo)
        <p>Tú juegas con: <strong>{{ $miSimbolo }}</strong></p>
    @else
        <p>Estás viendo esta partida como administrador.</p>
    @endif

    <p>
        @if ($partida->estado === 'esperando')
            Esperando a que otro jugador se una...
        @elseif ($partida->estado === 'en_curso')
            Turno de: <strong>{{ $partida->turno }}</strong>
            @if ($miTurno)
                (es tu turno)
            @elseif ($miSimbolo)
                (espera a tu rival)
            @endif
        @elseif ($partida->estado === 'ganada')
            <strong>Ganó {{ $partida->ganador->name }}</strong>
        @elseif ($partida->estado === 'empate')
            <strong>Empate</strong>
        @elseif ($partida->estado === 'abandonada')
            <strong>Partida abandonada</strong>
            @if ($partida->ganador) (ganó {{ $partida->ganador->name }}) @endif
        @else
            <strong>Partida cancelada por el administrador</strong>
        @endif
    </p>

    <table class="tablero">
        @for ($fila = 0; $fila < 3; $fila++)
            <tr>
                @for ($col = 0; $col < 3; $col++)
                    @php
                        $pos = $fila * 3 + $col;
                        $celda = $partida->tablero[$pos];
                    @endphp
                    <td>
                        @if ($celda !== '-')
                            {{ $celda }}
                        @elseif ($miTurno)
                            <form method="POST" action="{{ route('partidas.mover', $partida) }}">
                                @csrf
                                <input type="hidden" name="posicion" value="{{ $pos }}">
                                <button type="submit">&nbsp;</button>
                            </form>
                        @endif
                    </td>
                @endfor
            </tr>
        @endfor
    </table>

    @if ($miSimbolo && $activa)
        <form method="POST" action="{{ route('partidas.abandonar', $partida) }}"
              onsubmit="return confirm('¿Abandonar la partida?')">
            @csrf
            <button type="submit">Abandonar partida</button>
        </form>
    @endif

    @if (auth()->user()->role === 'admin' && $activa)
        <form method="POST" action="{{ route('partidas.cancelar', $partida) }}"
              onsubmit="return confirm('¿Cancelar esta partida?')">
            @csrf
            <button type="submit">Cancelar partida</button>
        </form>
    @endif

    @if ($activa)
        <script>
            // Polling: cada 2 segundos pregunta si algo cambió y recarga si es así
            const versionActual = @json($partida->tablero . '|' . $partida->turno . '|' . $partida->estado . '|' . $partida->jugador_o_id);

            setInterval(async () => {
                try {
                    const r = await fetch(@json(route('partidas.estado', $partida)), {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!r.ok) return;
                    const datos = await r.json();
                    if (datos.version !== versionActual) {
                        location.reload();
                    }
                } catch (e) {}
            }, 2000);
        </script>
    @endif
</body>
</html>