<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar automóvil - Automotora</title>
</head>

<body>
    <h1>Modificar automóvil</h1>
    <?php if ($error !== ""): ?><p><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></p><?php endif; ?>
    <form action="index.php?ruta=auto-modificar&amp;id=<?= (int) $id ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($datos['marca']) ?>" required>
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" value="<?= htmlspecialchars($datos['modelo']) ?>" required>
        <label for="anio">Año:</label>
        <input type="number" id="anio" name="anio" value="<?= htmlspecialchars($datos['anio']) ?>" required>
        <label for="color">Color:</label>
        <input type="text" id="color" name="color" value="<?= htmlspecialchars($datos['color']) ?>" required>
        <label for="kilometros">Kilometros:</label>
        <input type="number" id="kilometros" name="kilometros" value="<?= htmlspecialchars($datos['kilometros']) ?>" required>
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" min="0" value="<?= htmlspecialchars($datos['precio']) ?>" required>

        <button type="submit">Guardar cambios</button>
    </form>
</body>

</html>
