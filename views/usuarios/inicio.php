<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automotora Pérez</title>
</head>
<body>
    <h1>Automotora Pérez</h1>
    <h2>Inicio de sesión</h2>
    <?php if ($error !== ""): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></p>
    <?php endif; ?>
    <form action="index.php?ruta=login" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
        <label for="clave">Contraseña:</label>
        <input type="password" id="clave" name="clave" required>
        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>
