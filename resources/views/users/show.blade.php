<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de usuario</title>
</head>
<body>
    <h1>Detalle de usuario</h1>

    <p><strong>Nombre:</strong> {{ $user->name }}</p>
    <p><strong>Correo:</strong> {{ $user->email }}</p>
    <p><strong>Rol:</strong> {{ $user->role }}</p>
    <p><strong>Registrado:</strong> {{ $user->created_at }}</p>

    <a href="{{ route('users.edit', $user) }}">Editar</a>
    <a href="{{ route('users.index') }}">Volver</a>
</body>
</html>