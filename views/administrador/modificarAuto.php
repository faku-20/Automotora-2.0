<?php
/**
 * Variables provistas por AdministradorController::modificarAuto() antes del require.
 * Se anotan para eliminar falsos positivos del IDE sin crear valores por defecto que oculten fallos.
 * @var string $csrfLogout
 * @var int $id
 * @var string $csrfToken
 * @var string $error
 * @var array{id:int, marca:string, modelo:string, anio:int, color:string, kilometros:int, precio:float} $datos
 */
$tituloPagina = "Modificar automóvil";
$seccionActual = "auto-modificar";
require __DIR__ . "/../layouts/header.php";
?>
<section class="page-heading compact-heading">
    <div>
        <p class="eyebrow">Inventario</p>
        <h1>Modificar automóvil</h1>
        <p>Actualizá los datos del vehículo seleccionado.</p>
    </div><a class="button button-secondary" href="index.php?ruta=autos">Volver al inventario</a>
</section>
<?php if ($error !== ""): ?><div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div><?php endif; ?>
<section class="content-card form-card wide-form">
    <form class="form-grid" action="index.php?ruta=auto-modificar&amp;id=<?= (int) $id ?>" method="POST"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>">
        <div class="field-group"><label for="marca">Marca</label><input type="text" id="marca" name="marca" value="<?= htmlspecialchars($datos["marca"], ENT_QUOTES, "UTF-8") ?>" required></div>
        <div class="field-group"><label for="modelo">Modelo</label><input type="text" id="modelo" name="modelo" value="<?= htmlspecialchars($datos["modelo"], ENT_QUOTES, "UTF-8") ?>" required></div>
        <div class="field-group"><label for="anio">Año</label><input type="number" id="anio" name="anio" value="<?= htmlspecialchars((string) $datos["anio"], ENT_QUOTES, "UTF-8") ?>" required></div>
        <div class="field-group"><label for="color">Color</label><input type="text" id="color" name="color" value="<?= htmlspecialchars($datos["color"], ENT_QUOTES, "UTF-8") ?>" required></div>
        <div class="field-group"><label for="kilometros">Kilómetros</label><input type="number" id="kilometros" name="kilometros" value="<?= htmlspecialchars((string) $datos["kilometros"], ENT_QUOTES, "UTF-8") ?>" min="0" required></div>
        <div class="field-group"><label for="precio">Precio</label><input type="number" id="precio" name="precio" step="0.01" min="0" value="<?= htmlspecialchars((string) $datos["precio"], ENT_QUOTES, "UTF-8") ?>" required></div>
        <div class="form-actions"><button class="button button-primary" type="submit">Guardar cambios</button></div>
    </form>
</section>
<?php require __DIR__ . "/../layouts/footer.php"; ?>
