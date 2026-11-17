<?php
/**
 * Variables provistas por PerfilController::mostrar() antes del require.
 * Se anotan para evitar falsos positivos del IDE; no se inicializan valores que podrían esconder errores reales.
 * @var string $csrfLogout
 * @var string $csrfToken
 * @var string $error
 * @var string $mensaje
 * @var string $volverRuta
 * @var array{id:int, usuario:string, nombre:string, apellido:string, tipo_usuario:string, password:string} $datos
 */
$tituloPagina = "Mi perfil";
$seccionActual = "perfil";
require __DIR__ . "/../layouts/header.php";
?>
<section class="page-heading compact-heading">
    <div>
        <p class="eyebrow">Cuenta</p>
        <h1>Mi perfil</h1>
        <p>Actualizá tus datos personales y mantené tu acceso protegido.</p>
    </div>
    <a class="button button-secondary" href="index.php?ruta=<?= htmlspecialchars($volverRuta, ENT_QUOTES, "UTF-8") ?>">Volver al panel</a>
</section>
<?php if ($error !== ""): ?><div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div><?php endif; ?>
<?php if ($mensaje !== ""): ?><div class="alert alert-success" role="status"><?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?></div><?php endif; ?>
<form class="profile-grid" action="index.php?ruta=perfil" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>">
    <section class="content-card form-card">
        <p class="eyebrow">Información personal</p>
        <h2>Datos de cuenta</h2>
        <dl class="profile-summary">
            <div>
                <dt>Usuario</dt>
                <dd><?= htmlspecialchars($datos["usuario"], ENT_QUOTES, "UTF-8") ?></dd>
            </div>
            <div>
                <dt>Rol</dt>
                <dd><?= htmlspecialchars($datos["tipo_usuario"], ENT_QUOTES, "UTF-8") ?></dd>
            </div>
        </dl>
        <div class="two-fields">
            <div class="field-group"><label for="nombre">Nombre</label><input type="text" id="nombre" name="nombre" maxlength="100" value="<?= htmlspecialchars($datos["nombre"], ENT_QUOTES, "UTF-8") ?>" required></div>
            <div class="field-group"><label for="apellido">Apellido</label><input type="text" id="apellido" name="apellido" maxlength="100" value="<?= htmlspecialchars($datos["apellido"], ENT_QUOTES, "UTF-8") ?>" required></div>
        </div>
    </section>
    <section class="content-card form-card">
        <p class="eyebrow">Seguridad</p>
        <h2>Cambiar contraseña</h2>
        <p class="muted">Completá estos campos sólo si querés cambiarla.</p>
        <div class="field-group"><label for="clave_actual">Contraseña actual</label><input type="password" id="clave_actual" name="clave_actual" autocomplete="current-password"></div>
        <div class="field-group"><label for="nueva_clave">Nueva contraseña</label><input type="password" id="nueva_clave" name="nueva_clave" minlength="8" autocomplete="new-password"><small>Mínimo 8 caracteres.</small></div>
    </section>
    <div class="form-actions"><button class="button button-primary" type="submit">Guardar cambios</button></div>
</form>
<?php require __DIR__ . "/../layouts/footer.php"; ?>
