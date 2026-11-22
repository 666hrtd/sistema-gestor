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
        <p><a href="{{ route('productos.index') }}">Productos</a></p>
    @endif

    @if (auth()->user()->role === 'profesor')
        <p><a href="{{ route('alumnos.index') }}">Gestionar alumnos</a></p>
    @endif

    @if (auth()->user()->role === 'alumno')
        <p><a href="{{ route('alumnos.mi') }}">Mi información académica</a></p>
    @endif

    @if (in_array(auth()->user()->role, ['admin', 'estudiante']))
        <p><a href="{{ route('equipos.index') }}">Equipos</a></p>
        <p><a href="{{ route('reservaciones.index') }}">Reservaciones</a></p>
    @endif

    @if (in_array(auth()->user()->role, ['admin', 'jugador']))
        <p><a href="{{ route('partidas.index') }}">Juego del gato</a></p>
    @endif

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>