
<?php
// inicia o reanuda la sesión. El servidor recuerda quien es el usuario y sus datos
session_start();

// Verificar que sea un comprador
// si el usuario no es comprador o no esta inicializado lo manda a index.php
if (($_SESSION["tipo_usuario"] ?? null) !== "comprador") {
    header("Location: index.php");
    exit;
}

// Cargar la vista del catálogo de la view del comprador
require_once __DIR__ . '/../views/comprador/listarAutos.php';
