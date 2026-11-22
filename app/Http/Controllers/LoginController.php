<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Credenciales incorrectas'])
                ->onlyInput('email');
        }

        if ($user->role === 'admin') {
            $codigo = random_int(100000, 999999);

            $user->update([
                'verification_code' => Hash::make((string) $codigo),
                'verification_expires_at' => now()->addMinutes(5),
            ]);

            Mail::raw("Tu código de verificación es: $codigo", function ($mensaje) use ($user) {
                $mensaje->to($user->email)->subject('Código de verificación');
            });

            session(['pending_2fa_user_id' => $user->id]);

            return redirect(URL::temporarySignedRoute('verificacion', now()->addMinutes(5)));
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}