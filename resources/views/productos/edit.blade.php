<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}"></head>
<body>
    <h1>Editar producto</h1>

    <x-producto-form
        :action="route('productos.update', $producto)"
        :categorias="$categorias"
        :producto="$producto"
        method="PUT"
        boton="Actualizar" />
</body>
</html>