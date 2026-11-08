<?php
session_start();
//comprobamos q el usuario haya iniciado sesion como administrador
if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    //lo mmanda al index.php
    header("Location: index.php");
    exit;
}
//y lo redirige a la vista de listar autos
require_once __DIR__ . '/../views/administrador/listarAutos.php';