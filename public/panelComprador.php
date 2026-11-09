<?php
session_start();

if (($_SESSION["tipo_usuario"] ?? null) !== "comprador") {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../views/comprador/principal.php';