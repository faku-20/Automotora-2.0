<?php

require_once __DIR__ . "/Utilidades.php";
require_once __DIR__ . "/../models/Usuario.php";

class PerfilController
{
    public function mostrar(): void
    {
        $rol = $_SESSION["tipo_usuario"] ?? "";
        if (!isset($_SESSION["id"], $_SESSION["usuario"]) || !in_array($rol, ["administrador", "comprador"], true)) {
            redirigir("inicio");
        }

        $usuario = new Usuario();
        $datos = $usuario->buscarUsuario($_SESSION["usuario"]);
        if (!$datos || (int) $datos["id"] !== (int) $_SESSION["id"]) {
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
                } elseif ($usuario->modificarPerfil((int) $_SESSION["id"], $nombre, $apellido, $nuevaClave)) {
                    $mensaje = "Perfil actualizado correctamente.";
                    $datos = $usuario->buscarUsuario($_SESSION["usuario"]);
                    $_SESSION["csrf_perfil"] = bin2hex(random_bytes(32));
                    $csrfToken = $_SESSION["csrf_perfil"];
                } else {
                    $error = "No se pudo actualizar el perfil.";
                }
            }
        }

        $volverRuta = $rol === "administrador" ? "admin" : "comprador";
        require __DIR__ . "/../views/usuarios/miPerfil.php";
    }
}
