<?php
session_start();

if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: ../../public/index.php");
    exit;
}

require_once __DIR__ . '/../../models/Auto.php';

$auto = new Auto();
$autos = $auto->listarAutos();
?>

<!DOCTYPE html>
<html lang="es">

</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Listado de automóviles</h2>

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
            <?php foreach ($autos as $autito) {
            ?>
                <tr>
                    <td><?php echo $autito["marca"]; ?></td>
                    <td><?php echo $autito["modelo"]; ?></td>
                    <td><?php echo $autito["anio"]; ?></td>
                    <td><?php echo $autito["color"]; ?></td>
                    <td><?php echo $autito["kilometros"]; ?></td>
                    <td><?php echo $autito["precio"]; ?></td>
                    <td><a href="modificarAuto.php?id=<?= $autito['id'] ?>">Modificar</a></td>
                </tr>

            <?php } ?>
        </tbody>
    </table>

</body>

</html>