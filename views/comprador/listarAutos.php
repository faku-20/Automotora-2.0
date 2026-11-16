<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de vehículos</title>
</head>

<body>

    <h1>Catálogo de vehículos</h1>

    <a href="index.php?ruta=comprador">Volver al panel</a>

    <?php if (empty($autos)) { ?>

        <p>No hay vehículos disponibles.</p>

        <!-- Si hay autos, "else" muestra la tabla con los datos -->
    <?php } else { ?>

        <table border="1">
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Color</th>
                    <th>Kilómetros</th>
                    <th>Precio</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                <!-- Recorre la lista de autos y muestra cada tupla en la tabla -->
                <?php foreach ($autos as $autito) { ?>
                    <!-- <tr> crea la fila en la tabla y <td> crea las celdas con las propiedades correspondientes -->
                    <tr>
                        <td>
                            <?= htmlspecialchars($autito["marca"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($autito["modelo"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($autito["anio"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($autito["color"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($autito["kilometros"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($autito["precio"]) ?>
                        </td>

                        <td>
                            <a href="index.php?ruta=simular-compra&amp;id=<?= (int) $autito['id'] ?>">
                                Simular compra
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } ?>

</body>

</html>
