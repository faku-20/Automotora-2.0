<?php
$tituloPagina = "Cargar automóvil";
$seccionActual = "auto-cargar";
require __DIR__ . "/../layouts/header.php";
?>
<section class="page-heading compact-heading"><div><p class="eyebrow">Inventario</p><h1>Cargar automóvil</h1><p>Ingresá los datos para incorporarlo al catálogo.</p></div><a class="button button-secondary" href="index.php?ruta=autos">Volver al inventario</a></section>
<?php if ($error !== ""): ?><div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div><?php endif; ?>
<section class="content-card form-card wide-form"><form class="form-grid" action="index.php?ruta=auto-cargar" method="POST"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>"><div class="field-group"><label for="marca">Marca</label><input type="text" id="marca" name="marca" required></div><div class="field-group"><label for="modelo">Modelo</label><input type="text" id="modelo" name="modelo" required></div><div class="field-group"><label for="anio">Año</label><input type="number" id="anio" name="anio" required></div><div class="field-group"><label for="color">Color</label><input type="text" id="color" name="color" required></div><div class="field-group"><label for="kilometros">Kilómetros</label><input type="number" id="kilometros" name="kilometros" min="0" required></div><div class="field-group"><label for="precio">Precio</label><input type="number" id="precio" name="precio" min="0" step="0.01" required></div><div class="form-actions"><button class="button button-primary" type="submit">Cargar automóvil</button></div></form></section>
<?php require __DIR__ . "/../layouts/footer.php"; ?>
