<?php
$tituloPagina = "Catálogo";
$seccionActual = "catalogo";
require __DIR__ . "/../layouts/header.php";
?>
<section class="page-heading catalog-heading"><div><p class="eyebrow">Selección disponible</p><h1>Vehículos seleccionados</h1><p>Una selección de vehículos lista para que proyectes tu próxima compra.</p></div><a class="button button-secondary" href="index.php?ruta=comprador">Volver al inicio</a></section>
<?php if (empty($autos)): ?>
    <section class="empty-state content-card"><h2>No hay vehículos disponibles</h2><p>Volvé más tarde para conocer las nuevas incorporaciones.</p></section>
<?php else: ?>
    <section class="vehicle-grid" aria-label="Vehículos disponibles">
        <?php foreach ($autos as $autito): ?>
            <article class="vehicle-card"><div class="vehicle-monogram" aria-hidden="true"><?= htmlspecialchars(mb_strtoupper(mb_substr((string) $autito["marca"], 0, 1)), ENT_QUOTES, "UTF-8") ?></div><div class="vehicle-details"><p class="vehicle-brand"><?= htmlspecialchars((string) $autito["marca"], ENT_QUOTES, "UTF-8") ?></p><h2><?= htmlspecialchars((string) $autito["modelo"], ENT_QUOTES, "UTF-8") ?></h2><ul class="vehicle-specs"><li><?= htmlspecialchars((string) $autito["anio"], ENT_QUOTES, "UTF-8") ?></li><li><?= htmlspecialchars((string) $autito["color"], ENT_QUOTES, "UTF-8") ?></li><li><?= htmlspecialchars((string) $autito["kilometros"], ENT_QUOTES, "UTF-8") ?> km</li></ul></div><div class="vehicle-price"><strong>$<?= htmlspecialchars((string) $autito["precio"], ENT_QUOTES, "UTF-8") ?></strong><a class="button button-primary button-small" href="index.php?ruta=simular-compra&amp;id=<?= (int) $autito["id"] ?>">Simular compra</a></div></article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
<?php require __DIR__ . "/../layouts/footer.php"; ?>
