<?php
$tituloPagina = $tituloPagina ?? "Automotora 2.0";
$seccionActual = $seccionActual ?? "";
$rolNavegacion = $_SESSION["tipo_usuario"] ?? "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111315">
    <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, "UTF-8") ?> · Automotora 2.0</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <?php if ($rolNavegacion !== ""): ?>
        <header class="site-header">
            <a class="brand" href="index.php?ruta=<?= $rolNavegacion === "administrador" ? "admin" : "comprador" ?>">
                <span class="brand-mark" aria-hidden="true">A</span>
                <span>Automotora <strong>2.0</strong></span>
            </a>
            <nav class="primary-nav" aria-label="Navegación principal">
                <?php if ($rolNavegacion === "administrador"): ?>
                    <a class="<?= $seccionActual === "admin" ? "is-active" : "" ?>" href="index.php?ruta=admin">Resumen</a>
                    <a class="<?= in_array($seccionActual, ["autos", "auto-cargar", "auto-modificar"], true) ? "is-active" : "" ?>" href="index.php?ruta=autos">Vehículos</a>
                    <a class="<?= $seccionActual === "financiacion" ? "is-active" : "" ?>" href="index.php?ruta=financiacion">Financiación</a>
                <?php else: ?>
                    <a class="<?= $seccionActual === "comprador" ? "is-active" : "" ?>" href="index.php?ruta=comprador">Inicio</a>
                    <a class="<?= in_array($seccionActual, ["catalogo", "simular-compra"], true) ? "is-active" : "" ?>" href="index.php?ruta=catalogo">Catálogo</a>
                <?php endif; ?>
            </nav>
            <div class="header-actions">
                <a class="profile-link <?= $seccionActual === "perfil" ? "is-active" : "" ?>" href="index.php?ruta=perfil">Mi perfil</a>
                <form action="index.php?ruta=logout" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfLogout ?? "", ENT_QUOTES, "UTF-8") ?>">
                    <button class="button button-quiet" type="submit">Cerrar sesión</button>
                </form>
            </div>
        </header>
    <?php endif; ?>
    <main class="site-main <?= $rolNavegacion === "" ? "login-main" : "" ?>">
