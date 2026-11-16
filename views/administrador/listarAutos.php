<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de automóviles - Automotora</title>
</head>

<body>
    <h2>Listado de automóviles</h2>
    <?php if ($mensaje !== ""): ?>
        <p><?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?></p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Año</th>
                <th>Color</th>
                <th>Kilómetros</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION["autos"] as $autito) {
            ?>
                <tr>
                    <td><?= htmlspecialchars((string) $autito["marca"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string) $autito["modelo"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string) $autito["anio"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string) $autito["color"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string) $autito["kilometros"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string) $autito["precio"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><a href="index.php?ruta=auto-modificar&amp;id=<?= (int) $autito['id'] ?>">Modificar</a>
                        <form action="index.php?ruta=auto-eliminar" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= (int) $autito['id'] ?>">
                            <button type="submit" onclick="return confirm('¿Seguro que querés eliminar este automóvil?');">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>

</body>

</html>
