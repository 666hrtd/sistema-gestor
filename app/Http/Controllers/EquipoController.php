<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipoController extends Controller
{
    // Admin ve todos los equipos, el estudiante solo los disponibles
    public function index(Request $request)
    {
        $equipos = Equipo::query()
            ->when($request->user()->role !== 'admin', fn ($q) => $q->where('disponible', true))
            ->orderBy('nombre')
            ->get();

        return view('equipos.index', compact('equipos'));
    }

    public function create()
    {
        return view('equipos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:equipos,nombre'],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        Equipo::create($data);

        return redirect()->route('equipos.index')->with('success', 'Equipo registrado');
    }

    public function edit(Equipo $equipo)
    {
        return view('equipos.edit', compact('equipo'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('equipos', 'nombre')->ignore($equipo->id)],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        $equipo->update($data);

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado');
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();

        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado');
    }
}