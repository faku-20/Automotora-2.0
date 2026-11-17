<?php

require_once __DIR__ . "/../controllers/Utilidades.php";
require_once __DIR__ . "/../controllers/UsuarioController.php";
require_once __DIR__ . "/../controllers/AdministradorController.php";
require_once __DIR__ . "/../controllers/CompradorController.php";
require_once __DIR__ . "/../controllers/PerfilController.php";

iniciarSesionSegura();

$ruta = $_GET["ruta"] ?? "inicio";
if (!is_string($ruta)) {
  http_response_code(400);
  exit("Ruta inválida.");
}

$usuario = new UsuarioController();
$administrador = new AdministradorController();
$comprador = new CompradorController();
$perfil = new PerfilController();

// Lista cerrada de rutas: index.php es el único punto de entrada HTTP.
$rutas = [
  "inicio" => ["GET", [$usuario, "mostrarInicio"]],
  "login" => ["POST", [$usuario, "iniciarSesion"]],
  "logout" => ["POST", [$usuario, "cerrarSesion"]],
  "admin" => ["GET", [$administrador, "panel"]],
  "autos" => ["GET", [$administrador, "listarAutos"]],
  "auto-cargar" => [["GET", "POST"], [$administrador, "cargarAuto"]],
  "auto-modificar" => [["GET", "POST"], [$administrador, "modificarAuto"]],
  "auto-eliminar" => ["POST", [$administrador, "eliminarAuto"]],
  "financiacion" => [["GET", "POST"], [$administrador, "configurarFinanciacion"]],
  "comprador" => ["GET", [$comprador, "panel"]],
  "catalogo" => ["GET", [$comprador, "catalogo"]],
  "simular-compra" => [["GET", "POST"], [$comprador, "simularCompra"]],
  "perfil" => [["GET", "POST"], [$perfil, "mostrar"]],
];

if (!array_key_exists($ruta, $rutas)) {
  http_response_code(404);
  exit("Ruta no encontrada.");
}

[$metodosPermitidos, $accion] = $rutas[$ruta];
$metodosPermitidos = (array) $metodosPermitidos;
if (!in_array($_SERVER["REQUEST_METHOD"], $metodosPermitidos, true)) {
  header("Allow: " . implode(", ", $metodosPermitidos));
  http_response_code(405);
  exit("Método no permitido.");
}

$accion();
