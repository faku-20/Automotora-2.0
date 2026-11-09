<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificamos que el usuario sea administrador
if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: /public/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de administración - Automotora</title>
</head>

<body>
    <h1>Bienvenido al panel de administración</h1>

    <a href="/public/logout.php">Cerrar sesión</a>

    <h2>Panel de administración</h2>

    <nav>
        <a href="cargarAuto.php">
            <button type="button">Cargar automóvil</button>
        </a>
        <br>
        <a href="listarAutos.php">
            <button type="button">Listar automóviles</button>
        </a>
    </nav>
</body>

</html>
