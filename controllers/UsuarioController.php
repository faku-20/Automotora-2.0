
<?php

require_once __DIR__ . "/Utilidades.php";
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    public function iniciarSesion()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            exit("Método no permitido.");
        }

        if (!csrfValido("csrf_login", $_POST["csrf_token"] ?? null)) {
            $_SESSION["error_login"] = "Solicitud no válida.";
            redirigir("inicio");
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
            redirigir("inicio");
        }

        $usuario = new Usuario();
        $resultado = $usuario->iniciarSesion(
            trim($usuarioIngresado),
            $claveIngresada
        );

        if (!$resultado) {
            $_SESSION["error_login"] = "Usuario o contraseña incorrectos.";
            redirigir("inicio");
        }

        if (!in_array(
            $resultado["tipo_usuario"],
            ["administrador", "comprador"],
            true
        )) {
            $_SESSION["error_login"] = "Tipo de usuario no válido.";
            redirigir("inicio");
        }

        // Renovamos la sesión al iniciar sesión correctamente.
        session_regenerate_id(true);

        // Evitamos conservar datos de una sesión anterior.
        unset($_SESSION["autos"], $_SESSION["csrf_autos"], $_SESSION["csrf_perfil"], $_SESSION["csrf_financiacion"], $_SESSION["csrf_simulacion"], $_SESSION["csrf_login"]);

        $_SESSION["id"] = (int) $resultado["id"];
        $_SESSION["usuario"] = $resultado["usuario"];
        $_SESSION["tipo_usuario"] = $resultado["tipo_usuario"];

        if ($resultado["tipo_usuario"] === "administrador") {
            redirigir("admin");
        } else {
            redirigir("comprador");
        }
    }

    public function mostrarInicio(): void
    {
        if (($_SESSION["tipo_usuario"] ?? null) === "administrador") {
            redirigir("admin");
        }
        if (($_SESSION["tipo_usuario"] ?? null) === "comprador") {
            redirigir("comprador");
        }

        $error = $_SESSION["error_login"] ?? "";
        unset($_SESSION["error_login"]);
        $csrfToken = tokenCsrf("csrf_login");
        require __DIR__ . "/../views/usuarios/inicio.php";
    }

    public function cerrarSesion(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            exit("Método no permitido.");
        }
        if (!csrfValido("csrf_logout", $_POST["csrf_token"] ?? null)) {
            http_response_code(403);
            exit("Solicitud no válida.");
        }

        $_SESSION = [];
        session_destroy();
        redirigir("inicio");
    }
}
