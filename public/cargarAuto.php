<?php
session_start();

if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: ../../public/index.php");
    exit;
}

require_once __DIR__ . '/../views/administrador/cargarAuto.php';