<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Mi perfil</title></head>
<body>
    <h1>Mi perfil</h1>
    <p>Usuario: <?= htmlspecialchars($datos["usuario"], ENT_QUOTES, "UTF-8") ?></p>
    <p>Tipo de usuario: <?= htmlspecialchars($datos["tipo_usuario"], ENT_QUOTES, "UTF-8") ?></p>
    <?php if ($error !== ""): ?><p><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></p><?php endif; ?>
    <?php if ($mensaje !== ""): ?><p><?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?></p><?php endif; ?>
    <form action="index.php?ruta=perfil" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" maxlength="100" value="<?= htmlspecialchars($datos["nombre"], ENT_QUOTES, "UTF-8") ?>" required>
        <br><br>
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" maxlength="100" value="<?= htmlspecialchars($datos["apellido"], ENT_QUOTES, "UTF-8") ?>" required>
        <hr><h2>Cambiar contraseña (opcional)</h2>
        <label for="clave_actual">Contraseña actual:</label>
        <input type="password" id="clave_actual" name="clave_actual">
        <br><br>
        <label for="nueva_clave">Nueva contraseña:</label>
        <input type="password" id="nueva_clave" name="nueva_clave" minlength="8">
        <br><br><button type="submit">Guardar cambios</button>
    </form>
    <p><a href="index.php?ruta=<?= htmlspecialchars($volverRuta, ENT_QUOTES, "UTF-8") ?>">Volver al panel</a></p>
</body>
</html>
