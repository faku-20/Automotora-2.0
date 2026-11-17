<?php
/**
 * Variables provistas por UsuarioController::mostrarInicio() antes del require.
 * Se anotan para que el IDE reconozca el alcance compartido de PHP sin modificar el flujo de la vista. sino tira error
 * @var string $csrfToken
 * @var string $error
 */
$tituloPagina = "Iniciar sesión";
$seccionActual = "inicio";
require __DIR__ . "/../layouts/header.php";
?>
<section class="login-shell" aria-labelledby="login-title">
    <div class="login-intro">
        <p class="eyebrow">Automotora 2.0</p>
        <h1 id="login-title">Tu próxima decisión empieza acá.</h1>
        <p>Ingresá para gestionar el inventario o explorar las alternativas de financiación disponibles.</p>
    </div>
    <div class="auth-card">
        <p class="eyebrow">Acceso seguro</p>
        <h2>Iniciar sesión</h2>
        <p class="muted">Usá tus credenciales para continuar.</p>
        <?php if ($error !== ""): ?>
            <div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
        <?php endif; ?>
        <form class="form-stack" action="index.php?ruta=login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>">
            <div class="field-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" autocomplete="username" required>
            </div>
            <div class="field-group">
                <label for="clave">Contraseña</label>
                <input type="password" id="clave" name="clave" autocomplete="current-password" required>
            </div>
            <button class="button button-primary button-full" type="submit">Ingresar</button>
        </form>
    </div>
</section>
<?php require __DIR__ . "/../layouts/footer.php"; ?>
