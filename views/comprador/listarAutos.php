<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_SESSION["tipo_usuario"] ?? null) !== "comprador") {
    header("Location: /public/index.php");
    exit;
}

// este código se engarca de mostar una lista de autos en una tabla web, obteniendo los datos de la BD

// importa el archivo de la clase Auto
require_once __DIR__ . '/../../models/Auto.php';

// crea una instancia de la clase Auto
$auto = new Auto();
// llama al método listarAutos() para obtener la lista de autos y lo guarda en la variable $autos
$autos = $auto->listarAutos();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de vehículos</title>
</head>

<body>

    <h1>Catálogo de vehículos</h1>

    <!-- Agrega un enlace para volver al panel del comprador -->
    <a href="/public/panelComprador.php">Volver al panel</a>

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

                        <!-- crea celda en la tupla con enlace a simularCompra.php-->
                        <td>
                            <a href="/public/simularCompra.php?id=<?= (int) $autito['id'] ?>">
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