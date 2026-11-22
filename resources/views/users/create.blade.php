<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar usuario</title>
</head>
<body>
    <h1>Registrar usuario</h1>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <p>
            <label>Nombre</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <span>{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Correo</label>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email') <span>{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Contraseña</label>
            <input type="password" name="password">
            @error('password') <span>{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Confirmar contraseña</label>
            <input type="password" name="password_confirmation">
        </p>

        @if (auth()->user()->role === 'admin')
            <p>
                <label>Rol</label>
                <select name="role">
                    <option value="empleado">Empleado</option>
                    <option value="admin">Admin</option>
                </select>
            </p>
        @else
            <input type="hidden" name="role" value="empleado">
        @endif

        <button type="submit">Guardar</button>
        <a href="{{ route('users.index') }}">Cancelar</a>
    </form>
</body>
</html>