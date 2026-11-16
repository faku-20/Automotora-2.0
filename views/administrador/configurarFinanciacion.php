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

    <form action="index.php?ruta=financiacion" method="POST">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>"
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
        <a href="index.php?ruta=admin">Volver al panel</a>
    </p>
</body>
</html>
