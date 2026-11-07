<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarioIngresado = $_POST["usuario"];
    $contraseñaIngresada = $_POST["clave"];
    $usuario = new Usuario();
    $resultado = $usuario->iniciarSesion($usuarioIngresado, $contraseñaIngresada);
    if ($resultado){
        $_SESSION["id"] = $resultado["id"];
        $_SESSION["usuario"] = $resultado["usuario"];
        $_SESSION["tipo_usuario"] = $resultado["tipo_usuario"];
        echo "Inicio de sesion correcto";
    }else{
        echo "Inicio de sesión incorrecto, vuelva a intentarlo";
    }
}
?>