<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Reservacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservacionController extends Controller
{
    // Admin ve todas; el estudiante SOLO las suyas
    public function index(Request $request)
    {
        $reservaciones = Reservacion::with(['user', 'equipo'])
            ->when($request->user()->role !== 'admin', fn ($q) => $q->where('user_id', $request->user()->id))
            ->latest()
            ->get();

        return view('reservaciones.index', compact('reservaciones'));
    }

    public function store(Request $request, Equipo $equipo)
    {
        try {
            DB::transaction(function () use ($request, $equipo) {
                // Bloquea la fila del equipo mientras dura la transacción
                $actual = Equipo::whereKey($equipo->id)->lockForUpdate()->first();

                if (! $actual || ! $actual->disponible) {
                    throw new \RuntimeException('El equipo ya no está disponible.');
                }

                Reservacion::create([
                    'user_id' => $request->user()->id,
                    'equipo_id' => $actual->id,
                ]);

                $actual->update(['disponible' => false]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('reservaciones.index')->with('success', 'Equipo reservado');
    }

    public function destroy(Request $request, Reservacion $reservacion)
    {
        $user = $request->user();

        // Un estudiante solo puede cancelar las suyas
        if ($user->role !== 'admin' && $reservacion->user_id !== $user->id) {
            abort(403, 'No puedes cancelar la reservación de otro usuario.');
        }

        if ($reservacion->estado !== 'activa') {
            return back()->with('error', 'Esta reservación ya está cancelada.');
        }

        DB::transaction(function () use ($reservacion) {
            $reservacion->update(['estado' => 'cancelada']);
            $reservacion->equipo->update(['disponible' => true]);
        });

        return back()->with('success', 'Reservación cancelada');
    }
}