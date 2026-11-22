<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Partida;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartidaController extends Controller
{
    private const LINEAS = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8],
        [0, 3, 6], [1, 4, 7], [2, 5, 8],
        [0, 4, 8], [2, 4, 6],
    ];

    private const FINALIZADAS = ['ganada', 'empate', 'abandonada', 'cancelada'];

    // Lobby: partidas disponibles, mis partidas y jugadores libres
    public function index(Request $request)
    {
        $user = $request->user();

        $disponibles = Partida::with('jugadorX')
            ->where('estado', 'esperando')
            ->where('jugador_x_id', '!=', $user->id)
            ->latest()
            ->get();

        // El jugador ve las suyas; el admin ve todas las activas
        $activas = Partida::with(['jugadorX', 'jugadorO'])
            ->whereIn('estado', ['esperando', 'en_curso'])
            ->when($user->role !== 'admin', fn ($q) => $q->where(
                fn ($q2) => $q2->where('jugador_x_id', $user->id)->orWhere('jugador_o_id', $user->id)
            ))
            ->latest()
            ->get();

        // Jugadores que no están en una partida activa
        $ocupados = Partida::whereIn('estado', ['esperando', 'en_curso'])->get(['jugador_x_id', 'jugador_o_id']);
        $idsOcupados = $ocupados->pluck('jugador_x_id')
            ->merge($ocupados->pluck('jugador_o_id'))
            ->filter()
            ->unique();

        $jugadores = User::where('role', 'jugador')
            ->where('id', '!=', $user->id)
            ->whereNotIn('id', $idsOcupados)
            ->orderBy('name')
            ->get();

        return view('partidas.index', compact('disponibles', 'activas', 'jugadores'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $existente = Partida::where('jugador_x_id', $user->id)->where('estado', 'esperando')->first();
        if ($existente) {
            return redirect()->route('partidas.show', $existente)
                ->with('error', 'Ya tienes una partida esperando rival.');
        }

        $partida = Partida::create(['jugador_x_id' => $user->id]);

        return redirect()->route('partidas.show', $partida);
    }

    public function unirse(Request $request, Partida $partida)
    {
        try {
            DB::transaction(function () use ($request, $partida) {
                $actual = Partida::whereKey($partida->id)->lockForUpdate()->first();

                if ($actual->estado !== 'esperando') {
                    throw new \RuntimeException('La partida ya no está disponible.');
                }
                if ((int) $actual->jugador_x_id === $request->user()->id) {
                    throw new \RuntimeException('No puedes unirte a tu propia partida.');
                }

                $actual->update([
                    'jugador_o_id' => $request->user()->id,
                    'estado' => 'en_curso',
                ]);
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('partidas.index')->with('error', $e->getMessage());
        }

        return redirect()->route('partidas.show', $partida);
    }

    public function show(Request $request, Partida $partida)
    {
        $this->soloParticipantes($request, $partida);

        $partida->load(['jugadorX', 'jugadorO', 'ganador']);

        return view('partidas.show', compact('partida'));
    }

    // Lo consulta el JavaScript cada 2 segundos (polling)
    public function estado(Request $request, Partida $partida)
    {
        $this->soloParticipantes($request, $partida);

        return response()->json([
            'version' => "{$partida->tablero}|{$partida->turno}|{$partida->estado}|{$partida->jugador_o_id}",
        ]);
    }

    public function mover(Request $request, Partida $partida)
    {
        $this->soloParticipantes($request, $partida);

        $data = $request->validate([
            'posicion' => ['required', 'integer', 'between:0,8'],
        ]);
        $posicion = (int) $data['posicion'];
        $user = $request->user();

        try {
            DB::transaction(function () use ($partida, $user, $posicion) {
                // Bloquea la partida: dos movimientos simultáneos se hacen en fila
                $actual = Partida::whereKey($partida->id)->lockForUpdate()->first();

                if ($actual->estado !== 'en_curso') {
                    throw new \RuntimeException('La partida no está en curso.');
                }

                // El símbolo sale de la sesión del usuario, no de lo que mande el navegador
                $simbolo = match (true) {
                    (int) $actual->jugador_x_id === $user->id => 'X',
                    (int) $actual->jugador_o_id === $user->id => 'O',
                    default => null,
                };

                if (! $simbolo) {
                    throw new \RuntimeException('No participas en esta partida.');
                }
                if ($simbolo !== $actual->turno) {
                    throw new \RuntimeException('No es tu turno.');
                }
                if ($actual->tablero[$posicion] !== '-') {
                    throw new \RuntimeException('Esa casilla ya está ocupada.');
                }

                $tablero = $actual->tablero;
                $tablero[$posicion] = $simbolo;

                $cambios = ['tablero' => $tablero];

                if ($this->hayGanador($tablero)) {
                    $cambios['estado'] = 'ganada';
                    $cambios['ganador_id'] = $user->id;
                } elseif (! str_contains($tablero, '-')) {
                    $cambios['estado'] = 'empate';
                } else {
                    $cambios['turno'] = $simbolo === 'X' ? 'O' : 'X';
                }

                $actual->update($cambios);

                Movimiento::create([
                    'partida_id' => $actual->id,
                    'user_id' => $user->id,
                    'posicion' => $posicion,
                    'simbolo' => $simbolo,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back();
    }

    public function abandonar(Request $request, Partida $partida)
    {
        $this->soloParticipantes($request, $partida);

        try {
            DB::transaction(function () use ($request, $partida) {
                $actual = Partida::whereKey($partida->id)->lockForUpdate()->first();

                if (! in_array($actual->estado, ['esperando', 'en_curso'])) {
                    throw new \RuntimeException('La partida ya terminó.');
                }

                $yo = $request->user()->id;
                $rival = (int) $actual->jugador_x_id === $yo ? $actual->jugador_o_id : $actual->jugador_x_id;

                $actual->update([
                    'estado' => 'abandonada',
                    // Si ya había rival, gana por abandono
                    'ganador_id' => $actual->estado === 'en_curso' ? $rival : null,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('partidas.index')->with('success', 'Abandonaste la partida');
    }

    // Solo admin (lo protege el middleware de la ruta)
    public function cancelar(Partida $partida)
    {
        try {
            DB::transaction(function () use ($partida) {
                $actual = Partida::whereKey($partida->id)->lockForUpdate()->first();

                if (! in_array($actual->estado, ['esperando', 'en_curso'])) {
                    throw new \RuntimeException('La partida ya terminó.');
                }

                $actual->update(['estado' => 'cancelada']);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Partida cancelada');
    }

    // Jugador: su historial. Admin: el historial general
    public function historial(Request $request)
    {
        $user = $request->user();

        $partidas = Partida::with(['jugadorX', 'jugadorO', 'ganador'])
            ->whereIn('estado', self::FINALIZADAS)
            ->when($user->role !== 'admin', fn ($q) => $q->where(
                fn ($q2) => $q2->where('jugador_x_id', $user->id)->orWhere('jugador_o_id', $user->id)
            ))
            ->latest()
            ->get();

        return view('partidas.historial', compact('partidas'));
    }

    // Solo los dos jugadores (o el admin, para consultar) pueden ver una partida
    private function soloParticipantes(Request $request, Partida $partida): void
    {
        $user = $request->user();
        $participa = in_array($user->id, [$partida->jugador_x_id, $partida->jugador_o_id]);

        abort_unless($user->role === 'admin' || $participa, 403, 'Esta partida no es tuya.');
    }

    private function hayGanador(string $tablero): bool
    {
        foreach (self::LINEAS as [$a, $b, $c]) {
            if ($tablero[$a] !== '-' && $tablero[$a] === $tablero[$b] && $tablero[$a] === $tablero[$c]) {
                return true;
            }
        }

        return false;
    }
}