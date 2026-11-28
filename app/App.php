<?php

/** Centraliza las variables compartidas entre solicitudes del sistema. */
final class App
{
    public const USUARIO_ID = "id";
    public const USUARIO_NOMBRE = "usuario";
    public const USUARIO_ROL = "tipo_usuario";
    public const AUTOS = "autos";
    public const MENSAJE = "mensaje";
    public const ERROR_LOGIN = "error_login";
    public const SIMULACION_CONTACTO = "simulacion_contacto";

    private function __construct()
    {
    }

    public static function obtener(string $variable, mixed $valorPorDefecto = null): mixed
    {
        return $_SESSION[$variable] ?? $valorPorDefecto;
    }

    public static function guardar(string $variable, mixed $valor): void
    {
        $_SESSION[$variable] = $valor;
    }

    public static function eliminar(string ...$variables): void
    {
        foreach ($variables as $variable) {
            unset($_SESSION[$variable]);
        }
    }

    public static function vaciarSesion(): void
    {
        $_SESSION = [];
    }
}
