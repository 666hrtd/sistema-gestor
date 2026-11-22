<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
</head>
<body>
    <h1>Usuarios</h1>

    <p><a href="{{ route('dashboard') }}">Volver al dashboard</a></p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <p><a href="{{ route('users.create') }}">Registrar usuario</a></p>

    @if (auth()->user()->role === 'admin')
        <p><a href="{{ route('users.trashed') }}">Ver usuarios desactivados</a></p>
    @endif

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <a href="{{ route('users.show', $user) }}">Ver</a>
                        <a href="{{ route('users.edit', $user) }}">Editar</a>

                        @if (auth()->user()->role === 'admin')
                            <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline"
                                  onsubmit="return confirm('¿Desactivar este usuario? Podrás recuperarlo después.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Desactivar</button>
                            </form>

                            <form method="POST" action="{{ route('users.force', $user->id) }}" style="display:inline"
                                  onsubmit="return confirm('Se eliminará DEFINITIVAMENTE. ¿Continuar?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Eliminar permanente</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>