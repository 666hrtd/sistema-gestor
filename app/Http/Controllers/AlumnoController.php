<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlumnoController extends Controller
{
    // Listado del profesor, con ordenamiento
    public function index(Request $request)
    {
        $orden = in_array($request->orden, ['nombre', 'calificacion', 'faltas'])
            ? $request->orden
            : 'nombre';
        $dir = $request->dir === 'desc' ? 'desc' : 'asc';

        $columna = match ($orden) {
            'nombre' => 'users.name',
            'calificacion' => 'alumnos.calificacion',
            'faltas' => 'alumnos.faltas',
        };

        $alumnos = Alumno::join('users', 'users.id', '=', 'alumnos.user_id')
            ->whereNull('users.deleted_at')
            ->select('alumnos.*')
            ->with('user')
            ->orderBy($columna, $dir)
            ->get();

        return view('alumnos.index', compact('alumnos', 'orden', 'dir'));
    }

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'faltas' => ['required', 'integer', 'min:0', 'max:99'],
            'calificacion' => ['required', 'numeric', 'min:0', 'max:10'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'alumno',
        ]);

        $user->alumno()->create([
            'faltas' => $data['faltas'],
            'calificacion' => $data['calificacion'],
        ]);

        return redirect()->route('alumnos.index')->with('success', 'Alumno registrado');
    }

    public function edit(Alumno $alumno)
    {
        return view('alumnos.edit', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($alumno->user_id)],
            'faltas' => ['required', 'integer', 'min:0', 'max:99'],
            'calificacion' => ['required', 'numeric', 'min:0', 'max:10'],
        ]);

        $alumno->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $alumno->update([
            'faltas' => $data['faltas'],
            'calificacion' => $data['calificacion'],
        ]);

        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado');
    }

    public function destroy(Alumno $alumno)
    {
        $user = $alumno->user;

        $alumno->delete();
        $user->forceDelete();

        return redirect()->route('alumnos.index')->with('success', 'Alumno eliminado');
    }

    // Vista del alumno: solo ve lo suyo
    public function miInformacion(Request $request)
    {
        $alumno = $request->user()->alumno;

        abort_if(! $alumno, 404);

        return view('alumnos.mi-informacion', compact('alumno'));
    }
}