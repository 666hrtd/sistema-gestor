<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar producto</title>
</head>
<body>
    <h1>Registrar producto</h1>

    <x-producto-form :action="route('productos.store')" :categorias="$categorias" />
</body>
</html>