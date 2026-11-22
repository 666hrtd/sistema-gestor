<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    public function create()
    {
        return view('auth.registro');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // El rol lo pone el servidor, nunca viene del formulario
        $user = User::create($data + ['role' => 'jugador']);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}