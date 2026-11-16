<?php

/** Funciones compartidas por los controladores del proyecto. */
function iniciarSesionSegura(): void
{
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }

    session_set_cookie_params([
        "httponly" => true,
        "samesite" => "Lax",
        "secure" => (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off"),
    ]);
    session_start();
}

function redirigir(string $ruta, array $parametros = []): never
{
    $url = "index.php?ruta=" . rawurlencode($ruta);
    if ($parametros !== []) {
        $url .= "&" . http_build_query($parametros, "", "&", PHP_QUERY_RFC3986);
    }

    header("Location: " . $url, true, 303);
    exit;
}

function requiereRol(string $rol): void
{
    if (($_SESSION["tipo_usuario"] ?? null) !== $rol) {
        redirigir("inicio");
    }
}

function tokenCsrf(string $nombre): string
{
    if (empty($_SESSION[$nombre])) {
        $_SESSION[$nombre] = bin2hex(random_bytes(32));
    }

    return $_SESSION[$nombre];
}

function csrfValido(string $nombre, mixed $token): bool
{
    return is_string($token)
        && isset($_SESSION[$nombre])
        && is_string($_SESSION[$nombre])
        && hash_equals($_SESSION[$nombre], $token);
}

function idPositivo(mixed $valor): ?int
{
    if (!is_string($valor) || !ctype_digit($valor)) {
        return null;
    }

    $id = (int) $valor;
    return $id > 0 ? $id : null;
}
