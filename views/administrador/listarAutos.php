<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/*
Evitamos que otra página pueda provocar una eliminación utilizando la sesión del administrador generando un token CSRF.
*/
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: /public/index.php");
    exit;
}

require_once __DIR__ . '/../../models/Auto.php';

$auto = new Auto();
/*
isset comprueba si ya existe el array en la sesion, si no existe el array hacemos la consilta a la bd y lo guardamos
Despes utilizamos los autos almacenados en $_SESION["autos"]
*/

if (!isset($_SESSION["autos"])) {
    $_SESSION["autos"] = $auto->listarAutos();
}

$autos = $_SESSION["autos"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de automóviles - Automotora</title>
</head>

<body>
    <h2>Listado de automóviles</h2>
    <?php
    if (isset($_SESSION["mensaje"])) {
        echo "<p>" . htmlspecialchars($_SESSION["mensaje"], ENT_QUOTES, "UTF-8") . "</p>";

        unset($_SESSION["mensaje"]);
    }
    ?>

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
                    <td><?= htmlspecialchars((string) $autito["marca"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string) $autito["modelo"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string) $autito["anio"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string) $autito["color"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string) $autito["kilometros"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><?= htmlspecialchars((string) $autito["precio"], ENT_QUOTES, "UTF-8") ?></td>
                    <td><a href="modificarAuto.php?id=<?= (int) $autito['id'] ?>">Modificar</a>
                        <form action="eliminarAuto.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
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