<?php

require_once __DIR__ . "/Utilidades.php";
require_once __DIR__ . "/../models/Usuario.php";

class PerfilController
{
    public function mostrar(): void
    {
        $rol = App::obtener(App::USUARIO_ROL, "");
        $idUsuario = App::obtener(App::USUARIO_ID);
        $nombreUsuario = App::obtener(App::USUARIO_NOMBRE);
        if (!is_int($idUsuario) || !is_string($nombreUsuario) || !in_array($rol, ["administrador", "comprador"], true)) {
            redirigir("inicio");
        }

        $usuario = new Usuario();
        $csrfLogout = tokenCsrf("csrf_logout");
        $datos = $usuario->buscarUsuario($nombreUsuario);
        if (!$datos || (int) $datos["id"] !== $idUsuario) {
            http_response_code(403);
            exit("No se pudo verificar el usuario.");
        }

        $error = "";
        $mensaje = "";
        $csrfToken = tokenCsrf("csrf_perfil");
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nombre = $_POST["nombre"] ?? null;
            $apellido = $_POST["apellido"] ?? null;
            $claveActual = $_POST["clave_actual"] ?? null;
            $nuevaClave = $_POST["nueva_clave"] ?? null;

            if (!csrfValido("csrf_perfil", $_POST["csrf_token"] ?? null)) {
                $error = "Solicitud no válida.";
            } elseif (!is_string($nombre) || !is_string($apellido) || !is_string($claveActual) || !is_string($nuevaClave)) {
                $error = "Los datos enviados no son válidos.";
            } else {
                $nombre = trim($nombre);
                $apellido = trim($apellido);
                if ($nombre === "" || $apellido === "") {
                    $error = "El nombre y el apellido son obligatorios.";
                } elseif (mb_strlen($nombre) > 100 || mb_strlen($apellido) > 100) {
                    $error = "El nombre o apellido es demasiado largo.";
                } elseif ($nuevaClave !== "" && strlen($nuevaClave) < 8) {
                    $error = "La nueva contraseña debe tener al menos 8 caracteres.";
                } elseif ($nuevaClave !== "" && !password_verify($claveActual, $datos["password"])) {
                    $error = "La contraseña actual es incorrecta.";
                } elseif ($usuario->modificarPerfil($idUsuario, $nombre, $apellido, $nuevaClave)) {
                    $mensaje = "Perfil actualizado correctamente.";
                    $datos = $usuario->buscarUsuario($nombreUsuario);
                    $csrfToken = bin2hex(random_bytes(32));
                    App::guardar("csrf_perfil", $csrfToken);
                } else {
                    $error = "No se pudo actualizar el perfil.";
                }
            }
        }

        $volverRuta = $rol === "administrador" ? "admin" : "comprador";
        require __DIR__ . "/../views/usuarios/miPerfil.php";
    }
}
