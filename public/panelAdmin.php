<?php
session_start();
//validamos q el tipo de usuario sea administrador
if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: ../../public/index.php");
    exit;
}

require_once __DIR__ . '/../views/administrador/principal.php';