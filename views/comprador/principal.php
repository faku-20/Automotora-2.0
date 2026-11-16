<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificamos que el usuario sea comprador
if (($_SESSION["tipo_usuario"] ?? null) !== "comprador") {
    header("Location: /public/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del comprador</title>
</head>

<body>

    <h1>Panel del comprador</h1>

    <h2>
        Bienvenido,
        <?= htmlspecialchars($_SESSION["usuario"] ?? "", ENT_QUOTES, "UTF-8") ?>
    </h2>

    <nav>
        <ul>
            <li>
                <a href="/public/verCatalogo.php">
                    Ver catálogo de vehículos
                </a>
            </li>

            <li>
                <a href="/public/miPerfil.php">
                    Mi perfil
                </a>
            </li>

            <li>
                <a href="/public/logout.php">
                    Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>

</body>

</html>