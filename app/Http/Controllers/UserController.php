<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'empleado'])],
        ]);

        // Un empleado solo puede crear empleados
        if ($request->user()->role !== 'admin') {
            $data['role'] = 'empleado';
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Usuario registrado');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'empleado'])],
        ]);

        // Un empleado no puede cambiar roles
        if ($request->user()->role !== 'admin') {
            $data['role'] = $user->role;
        }

        // Si no escribió contraseña, no se cambia
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado');
    }

    // Eliminación lógica (Soft Delete)
    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario desactivado');
    }

    // Eliminación permanente (Delete)
    public function forceDestroy(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user->forceDelete();

        return back()->with('success', 'Usuario eliminado permanentemente');
    }

    // Lista de usuarios con eliminación lógica
    public function trashed()
    {
        $users = User::onlyTrashed()->orderBy('name')->get();

        return view('users.trashed', compact('users'));
    }

    // Recuperar un usuario
    public function restore($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('users.trashed')->with('success', 'Usuario recuperado');
    }
}