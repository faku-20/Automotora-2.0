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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tasa = trim($_POST["tasa"] ?? "");

    if (!is_numeric($tasa) || $tasa < 0 || $tasa > 100) {
        $error = "Ingresá una tasa válida entre 0 y 100.";
    } elseif ($financiacion->modificarTasa((float) $tasa)) {
        $mensaje = "Tasa de interés actualizada correctamente.";
    } else {
        $error = "No se pudo actualizar la tasa.";
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
        Tasa de interés mensual actual:
        <strong><?= htmlspecialchars((string) $tasaActual) ?>%</strong>
    </p>

    <?php if ($mensaje): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
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