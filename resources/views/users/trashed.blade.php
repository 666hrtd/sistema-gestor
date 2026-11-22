<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios desactivados</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <h1>Usuarios desactivados</h1>

    <p><a href="{{ route('users.index') }}">Volver a usuarios</a></p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Desactivado el</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->deleted_at }}</td>
                    <td>
                        <form method="POST" action="{{ route('users.restore', $user->id) }}" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Recuperar</button>
                        </form>

                        <form method="POST" action="{{ route('users.force', $user->id) }}" style="display:inline"
                              onsubmit="return confirm('Se eliminará DEFINITIVAMENTE. ¿Continuar?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar permanente</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No hay usuarios desactivados</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>