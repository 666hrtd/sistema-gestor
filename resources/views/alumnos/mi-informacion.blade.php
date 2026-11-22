<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi información académica</title>
</head>
<body>
    <h1>Mi información académica</h1>

    <p><a href="{{ route('dashboard') }}">Volver al dashboard</a></p>

    <p><strong>Nombre:</strong> {{ $alumno->user->name }}</p>
    <p><strong>Faltas:</strong> {{ $alumno->faltas }}</p>
    <p><strong>Calificación:</strong> {{ $alumno->calificacion }}</p>
    <p><strong>Equivalencia:</strong> {{ $alumno->equivalencia }}</p>
</body>
</html>