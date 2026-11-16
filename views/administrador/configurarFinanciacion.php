<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: /public/index.php");
    exit;
}

require_once __DIR__ . '/../../models/Financiacion.php';

$financiacion = new Financiacion();
$mensaje = "";
$error = "";

if (empty($_SESSION["csrf_financiacion"])) {
    $_SESSION["csrf_financiacion"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";

    if (!is_string($token) ||
        !hash_equals($_SESSION["csrf_financiacion"], $token)) {
        $error = "Solicitud no válida.";
    } else {
        $tasa = trim((string) ($_POST["tasa"] ?? ""));

        if (!is_numeric($tasa) ||
            !is_finite((float) $tasa) ||
            (float) $tasa < 0 ||
            (float) $tasa > 100) {
            $error = "Ingresá una tasa entre 0 y 100.";
        } elseif ($financiacion->modificarTasa((float) $tasa)) {
            $mensaje = "Tasa actualizada correctamente.";
        } else {
            $error = "No se pudo actualizar la tasa.";
        }
    }
}

$tasaActual = $financiacion->obtenerTasa();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configurar financiación</title>
</head>
<body>
    <h1>Configuración de financiación</h1>

    <p>
        Tasa mensual actual:
        <strong>
            <?= htmlspecialchars((string) $tasaActual) ?>%
        </strong>
    </p>

    <?php if ($mensaje): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION["csrf_financiacion"]) ?>"
        >

        <label for="tasa">Nueva tasa mensual (%):</label>

        <input
            type="number"
            id="tasa"
            name="tasa"
            min="0"
            max="100"
            step="0.01"
            value="<?= htmlspecialchars((string) $tasaActual) ?>"
            required
        >

        <button type="submit">Guardar tasa</button>
    </form>

    <p>
        <a href="/public/panelAdmin.php">Volver al panel</a>
    </p>
</body>
</html>