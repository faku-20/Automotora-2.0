
<?php

// ?? null , operador que evita si nadie inicio sesión. Tambíen si el rol no es comprador, lo manda a index.php
if (($_SESSION["tipo_usuario"] ?? null) !== "comprador") {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del comprador</title>
</head>
<body>

    <h1>Panel del comprador</h1>

    <!-- saludo personalizado al usuario de sesión -->
    <!-- htmlspelichars() protegiendo el texto contra ataques XSS -->
    <h2>Bienvenido, <?= htmlspecialchars($_SESSION["usuario"]) ?></h2>

    <!-- lista con dos enlaces para el cliente, catalogo.php y logout.php -->
    <nav>
        <ul>
            <li>
                <a href="verCatalogo.php">Ver catálogo de vehículos</a>
            </li>

            <li>
                <a href="logout.php">Cerrar sesión</a>
            </li>
        </ul>
    </nav>

</body>
</html>
