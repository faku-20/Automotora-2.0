<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de administración - Automotora</title>
</head>

<body>
    <h1>Bienvenido al panel de administración</h1>

    <a href="index.php?ruta=perfil">Mi perfil</a>
    <form action="index.php?ruta=logout" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfLogout, ENT_QUOTES, "UTF-8") ?>">
        <button type="submit">Cerrar sesión</button>
    </form>

    <h2>Panel de administración</h2>

    <nav>
        <a href="index.php?ruta=auto-cargar">
            <button type="button">Cargar automóvil</button>
        </a>
        <br>

        <a href="index.php?ruta=autos">
            <button type="button">Listar automóviles</button>
        </a>
        <br>

        <a href="index.php?ruta=financiacion">
            Configurar financiación
        </a>
    </nav>
</body>

</html>
