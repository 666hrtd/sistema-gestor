<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VerificacionController extends Controller
{
    public function mostrar()
    {
        return view('auth.verificacion');
    }

    public function verificar(Request $request)
    {
        $request->validate([
            'codigo' => ['required', 'digits:6'],
        ]);

        $user = User::find(session('pending_2fa_user_id'));

        // Sin usuario pendiente o código vencido
        if (! $user || ! $user->verification_expires_at || now()->greaterThan($user->verification_expires_at)) {
            session()->forget(['pending_2fa_user_id', 'intentos_2fa']);

            return redirect()->route('login')
                ->withErrors(['email' => 'El código expiró. Inicia sesión de nuevo.']);
        }

        // Código incorrecto
        if (! Hash::check($request->codigo, $user->verification_code)) {
            $intentos = session('intentos_2fa', 0) + 1;
            session(['intentos_2fa' => $intentos]);

            if ($intentos >= 3) {
                $user->update(['verification_code' => null, 'verification_expires_at' => null]);
                session()->forget(['pending_2fa_user_id', 'intentos_2fa']);

                return redirect()->route('login')
                    ->withErrors(['email' => 'Demasiados intentos. Inicia sesión de nuevo.']);
            }

            return back()->withErrors(['codigo' => 'Código incorrecto']);
        }

        // Código correcto
        $user->update(['verification_code' => null, 'verification_expires_at' => null]);
        session()->forget(['pending_2fa_user_id', 'intentos_2fa']);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}