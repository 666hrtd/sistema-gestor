<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de jugador</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <h1>Registro de jugador</h1>

    <form method="POST" action="{{ route('registro.store') }}">
        @csrf

        <p>
            <label>Nombre</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <span style="color: red;">{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Correo</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <span style="color: red;">{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Contraseña</label>
            <input type="password" name="password">
            @error('password') <span style="color: red;">{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Confirmar contraseña</label>
            <input type="password" name="password_confirmation">
        </p>

        <button type="submit">Registrarme</button>
        <a href="{{ route('login') }}">Ya tengo cuenta</a>
    </form>
</body>
</html>
