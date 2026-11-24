
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
            App::guardar(App::ERROR_LOGIN, "Solicitud no válida.");
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
            App::guardar(App::ERROR_LOGIN, "Ingresá usuario y contraseña.");
            redirigir("inicio");
        }

        $usuario = new Usuario();
        $resultado = $usuario->iniciarSesion(
            trim($usuarioIngresado),
            $claveIngresada
        );

        if (!$resultado) {
            App::guardar(App::ERROR_LOGIN, "Usuario o contraseña incorrectos.");
            redirigir("inicio");
        }

        if (!in_array(
            $resultado["tipo_usuario"],
            ["administrador", "comprador"],
            true
        )) {
            App::guardar(App::ERROR_LOGIN, "Tipo de usuario no válido.");
            redirigir("inicio");
        }

        // Renovamos la sesión al iniciar sesión correctamente.
        session_regenerate_id(true);

        // Evitamos conservar datos de una sesión anterior.
        App::eliminar(App::AUTOS, "csrf_autos", "csrf_perfil", "csrf_financiacion", "csrf_simulacion", "csrf_login");

        App::guardar(App::USUARIO_ID, (int) $resultado["id"]);
        App::guardar(App::USUARIO_NOMBRE, $resultado["usuario"]);
        App::guardar(App::USUARIO_ROL, $resultado["tipo_usuario"]);

        if ($resultado["tipo_usuario"] === "administrador") {
            redirigir("admin");
        } else {
            redirigir("comprador");
        }
    }

    public function mostrarInicio(): void
    {
        if (App::obtener(App::USUARIO_ROL) === "administrador") {
            redirigir("admin");
        }
        if (App::obtener(App::USUARIO_ROL) === "comprador") {
            redirigir("comprador");
        }

        $error = App::obtener(App::ERROR_LOGIN, "");
        App::eliminar(App::ERROR_LOGIN);
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

        App::vaciarSesion();
        session_destroy();
        redirigir("inicio");
    }
}
