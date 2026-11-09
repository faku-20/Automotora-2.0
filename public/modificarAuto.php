<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../views/administrador/modificarAuto.php';