<?php
$tituloPagina = "Financiación";
$seccionActual = "financiacion";
require __DIR__ . "/../layouts/header.php";
?>
<section class="page-heading compact-heading"><div><p class="eyebrow">Configuración</p><h1>Financiación</h1><p>Definí la tasa mensual que se utiliza en los préstamos.</p></div><a class="button button-secondary" href="index.php?ruta=admin">Volver al panel</a></section>
<?php if ($mensaje): ?><div class="alert alert-success" role="status"><?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div><?php endif; ?>
<section class="finance-layout"><article class="rate-card"><p class="eyebrow">Tasa vigente</p><strong><?= htmlspecialchars((string) $tasaActual, ENT_QUOTES, "UTF-8") ?>%</strong><p>Interés mensual aplicado a los préstamos de la automotora.</p></article><section class="content-card form-card"><p class="eyebrow">Actualizar configuración</p><h2>Nueva tasa mensual</h2><form class="form-stack" action="index.php?ruta=financiacion" method="POST"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>"><div class="field-group"><label for="tasa">Tasa mensual (%)</label><input type="number" id="tasa" name="tasa" min="0" max="100" step="0.01" value="<?= htmlspecialchars((string) $tasaActual, ENT_QUOTES, "UTF-8") ?>" required></div><button class="button button-primary" type="submit">Guardar tasa</button></form></section></section>
<?php require __DIR__ . "/../layouts/footer.php"; ?>
