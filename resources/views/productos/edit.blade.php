<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
</head>
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