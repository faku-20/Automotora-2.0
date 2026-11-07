<?php

if (!isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] != "comprador") {

    header("Location: index.php");
    exit;
}
echo "Bienvenido al panel";
?>
<a href="logout.php">Cerrar sesión</a>