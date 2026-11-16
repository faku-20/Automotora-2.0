<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del comprador</title>
</head>

<body>

    <h1>Panel del comprador</h1>

    <h2>
        Bienvenido,
        <?= htmlspecialchars($_SESSION["usuario"] ?? "", ENT_QUOTES, "UTF-8") ?>
    </h2>

    <nav>
        <ul>
            <li>
                <a href="index.php?ruta=catalogo">
                    Ver catálogo de vehículos
                </a>
            </li>

            <li>
                <a href="index.php?ruta=perfil">
                    Mi perfil
                </a>
            </li>

            <li>
                <form action="index.php?ruta=logout" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfLogout, ENT_QUOTES, "UTF-8") ?>">
                    <button type="submit">Cerrar sesión</button>
                </form>
            </li>
        </ul>
    </nav>

</body>

</html>
