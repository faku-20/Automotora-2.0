<?php
$tituloPagina = "Panel del comprador";
$seccionActual = "comprador";
require __DIR__ . "/../layouts/header.php";
?>
<section class="hero-panel buyer-hero"><p class="eyebrow">Bienvenido, <?= htmlspecialchars($_SESSION["usuario"] ?? "", ENT_QUOTES, "UTF-8") ?></p><h1>Encontrá el vehículo que acompaña tu próximo paso.</h1><p>Explorá el catálogo y simulá alternativas de financiación de forma simple.</p><a class="button button-primary" href="index.php?ruta=catalogo">Ver catálogo</a></section>
<section class="dashboard-grid buyer-grid"><a class="action-card" href="index.php?ruta=catalogo"><span class="card-index">01</span><h2>Catálogo</h2><p>Conocé los vehículos disponibles y sus características.</p><span class="card-link">Explorar vehículos</span></a><a class="action-card" href="index.php?ruta=perfil"><span class="card-index">02</span><h2>Mi perfil</h2><p>Administrá tus datos personales y tu contraseña.</p><span class="card-link">Ver mi perfil</span></a></section>
<?php require __DIR__ . "/../layouts/footer.php"; ?>
