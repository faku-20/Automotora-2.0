<?php

if (!isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] != "administrador") {

    header("Location: index.php");
    exit;
}
echo "Bienvenido al panel de administración";
?>
<a href="logout.php">Cerrar sesión</a>