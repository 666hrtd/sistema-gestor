<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<p><a href="{{ route('registro') }}">Registrarme como jugador</a></p>
<body>
    <h1>Iniciar sesión</h1>

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <label>Correo</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        @error('email')
            <p>{{ $message }}</p>
        @enderror

        <button type="submit">Entrar</button>
    </form>
</body>
</html>