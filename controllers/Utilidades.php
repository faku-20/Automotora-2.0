<?php

require_once __DIR__ . "/../app/App.php";

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
    if (App::obtener(App::USUARIO_ROL) !== $rol) {
        redirigir("inicio");
    }
}

function tokenCsrf(string $nombre): string
{
    $token = App::obtener($nombre);
    if (!is_string($token) || $token === "") {
        $token = bin2hex(random_bytes(32));
        App::guardar($nombre, $token);
    }

    return $token;
}

function csrfValido(string $nombre, mixed $token): bool
{
    return is_string($token)
        && is_string(App::obtener($nombre))
        && hash_equals(App::obtener($nombre), $token);
}

function idPositivo(mixed $valor): ?int
{
    if (!is_string($valor) || !ctype_digit($valor)) {
        return null;
    }

    $id = (int) $valor;
    return $id > 0 ? $id : null;
}
