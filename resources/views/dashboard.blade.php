<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Bienvenido, {{ auth()->user()->name }}</h1>
    <p>Rol: {{ auth()->user()->role }}</p>

    @if (in_array(auth()->user()->role, ['admin', 'empleado']))
        <p><a href="{{ route('users.index') }}">Gestionar usuarios</a></p>
    @endif

    @if (auth()->user()->role === 'profesor')
        <p><a href="{{ route('alumnos.index') }}">Gestionar alumnos</a></p>
    @endif

    @if (auth()->user()->role === 'alumno')
        <p><a href="{{ route('alumnos.mi') }}">Mi información académica</a></p>
    @endif

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>