<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <h1>Verificación de administrador</h1>
    <p>Escribe el código que se envió a tu correo. Vence en 5 minutos.</p>

    <form method="POST" action="">
        @csrf

        <label>Código</label>
        <input type="text" name="codigo" maxlength="6" required>

        @error('codigo')
            <p>{{ $message }}</p>
        @enderror

        <button type="submit">Verificar</button>
    </form>
</body>
</html>