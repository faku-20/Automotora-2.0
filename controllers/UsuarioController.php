
<?php

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    public function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /public/index.php");
            exit;
        }

        $usuarioIngresado = $_POST["usuario"] ?? "";
        $claveIngresada = $_POST["clave"] ?? "";

        if (
            !is_string($usuarioIngresado) ||
            !is_string($claveIngresada) ||
            trim($usuarioIngresado) === "" ||
            $claveIngresada === ""
        ) {
            $_SESSION["error_login"] = "Ingresá usuario y contraseña.";
            header("Location: /public/index.php");
            exit;
        }

        $usuario = new Usuario();
        $resultado = $usuario->iniciarSesion(
            trim($usuarioIngresado),
            $claveIngresada
        );

        if (!$resultado) {
            $_SESSION["error_login"] = "Usuario o contraseña incorrectos.";
            header("Location: /public/index.php");
            exit;
        }

        if (!in_array(
            $resultado["tipo_usuario"],
            ["administrador", "comprador"],
            true
        )) {
            $_SESSION["error_login"] = "Tipo de usuario no válido.";
            header("Location: /public/index.php");
            exit;
        }

        // Renovamos la sesión al iniciar sesión correctamente.
        session_regenerate_id(true);

        // Evitamos conservar datos de una sesión anterior.
        unset($_SESSION["autos"], $_SESSION["csrf_token"], $_SESSION["csrf_perfil"]);

        $_SESSION["id"] = (int) $resultado["id"];
        $_SESSION["usuario"] = $resultado["usuario"];
        $_SESSION["tipo_usuario"] = $resultado["tipo_usuario"];

        if ($resultado["tipo_usuario"] === "administrador") {
            header("Location: /public/panelAdmin.php");
        } else {
            header("Location: /public/panelComprador.php");
        }

        exit;
    }
}
