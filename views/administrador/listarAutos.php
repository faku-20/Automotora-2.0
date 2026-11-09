<?php
session_start();
/*
Evitamos que otra página pueda provocar una eliminación utilizando la sesión del administrador generando un token CSRF.
*/
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

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
                    <td><a href="modificarAuto.php?id=<?= $autito['id'] ?>">Modificar</a>
                        <form action="eliminarAuto.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= $autito['id'] ?>">
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