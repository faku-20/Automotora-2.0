<?php
// este archivo funciona como controlador. Verifica permisos, valida ID y carga la vista.

session_start();

// Verificar que sea un comprador
if (($_SESSION["tipo_usuario"] ?? null) !== "comprador") {
    header("Location: index.php");
    exit;
}

// Verificar que se haya recibido un ID
if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("Location: verCatalogo.php");
    exit;
}

// Cargar la vista
require_once __DIR__ . '/../views/comprador/simularCompra.php';

?>
