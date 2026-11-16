<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION["id"], $_SESSION["usuario"]) ||
    !in_array(
        $_SESSION["tipo_usuario"] ?? "",
        ["administrador", "comprador"],
        true
    )
) {
    header("Location: /public/index.php");
    exit;
}

require_once __DIR__ . '/../models/Usuario.php';

$usuario = new Usuario();
$datos = $usuario->buscarUsuario($_SESSION["usuario"]);

if (!$datos || (int) $datos["id"] !== (int) $_SESSION["id"]) {
    http_response_code(403);
    exit("No se pudo verificar el usuario.");
}

$error = "";
$mensaje = "";

if (empty($_SESSION["csrf_perfil"])) {
    $_SESSION["csrf_perfil"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";
    $nombre = $_POST["nombre"] ?? "";
    $apellido = $_POST["apellido"] ?? "";
    $claveActual = $_POST["clave_actual"] ?? "";
    $nuevaClave = $_POST["nueva_clave"] ?? "";

    if (
        !is_string($token) ||
        !hash_equals($_SESSION["csrf_perfil"], $token)
    ) {
        $error = "Solicitud no válida.";
    } elseif (
        !is_string($nombre) ||
        !is_string($apellido) ||
        !is_string($claveActual) ||
        !is_string($nuevaClave)
    ) {
        $error = "Los datos enviados no son válidos.";
    } else {
        $nombre = trim($nombre);
        $apellido = trim($apellido);

        if ($nombre === "" || $apellido === "") {
            $error = "El nombre y el apellido son obligatorios.";
        } elseif (
            mb_strlen($nombre) > 100 ||
            mb_strlen($apellido) > 100
        ) {
            $error = "El nombre o apellido es demasiado largo.";
        } elseif (
            $nuevaClave !== "" &&
            strlen($nuevaClave) < 8
        ) {
            $error = "La nueva contraseña debe tener al menos 8 caracteres.";
        } elseif (
            $nuevaClave !== "" &&
            !password_verify($claveActual, $datos["password"])
        ) {
            $error = "La contraseña actual es incorrecta.";
        } elseif (
            $usuario->modificarPerfil(
                (int) $_SESSION["id"],
                $nombre,
                $apellido,
                $nuevaClave
            )
        ) {
            $mensaje = "Perfil actualizado correctamente.";
            $datos = $usuario->buscarUsuario($_SESSION["usuario"]);
            $_SESSION["csrf_perfil"] = bin2hex(random_bytes(32));
        } else {
            $error = "No se pudo actualizar el perfil.";
        }
    }
}

$volver = $_SESSION["tipo_usuario"] === "administrador"
    ? "/public/panelAdmin.php"
    : "/public/panelComprador.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi perfil</title>
</head>

<body>

    <h1>Mi perfil</h1>

    <p>
        Usuario:
        <?= htmlspecialchars($datos["usuario"]) ?>
    </p>

    <p>
        Tipo de usuario:
        <?= htmlspecialchars($datos["tipo_usuario"]) ?>
    </p>

    <?php if ($error !== ""): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($mensaje !== ""): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION["csrf_perfil"]) ?>">

        <label for="nombre">Nombre:</label>
        <input
            type="text"
            id="nombre"
            name="nombre"
            maxlength="100"
            value="<?= htmlspecialchars($datos["nombre"]) ?>"
            required>

        <br><br>

        <label for="apellido">Apellido:</label>
        <input
            type="text"
            id="apellido"
            name="apellido"
            maxlength="100"
            value="<?= htmlspecialchars($datos["apellido"]) ?>"
            required>

        <hr>

        <h2>Cambiar contraseña (opcional)</h2>

        <label for="clave_actual">Contraseña actual:</label>
        <input
            type="password"
            id="clave_actual"
            name="clave_actual">

        <br><br>

        <label for="nueva_clave">Nueva contraseña:</label>
        <input
            type="password"
            id="nueva_clave"
            name="nueva_clave"
            minlength="8">

        <br><br>

        <button type="submit">Guardar cambios</button>
    </form>

    <p><a href="<?= $volver ?>">Volver al panel</a></p>

</body>

</html>