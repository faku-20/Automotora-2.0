<?php
/**
 * Variable provista por AdministradorController::panel() para el formulario de cierre de sesión.
 * La anotación aclara al IDE el alcance compartido entre el controlador y esta vista requerida.
 * @var string $csrfLogout
 */
$tituloPagina = "Panel de administración";
$seccionActual = "admin";
require __DIR__ . "/../layouts/header.php";
?>
<section class="hero-panel" aria-labelledby="admin-title">
    <p class="eyebrow">Administración</p>
    <h1 id="admin-title">Gestión clara para cada vehículo.</h1>
    <p>Centralizá el inventario y la financiación de la automotora desde un mismo lugar.</p>
    <a class="button button-primary" href="index.php?ruta=auto-cargar">Cargar automóvil</a>
</section>
<section class="dashboard-grid" aria-label="Accesos de administración">
    <a class="action-card" href="index.php?ruta=autos"><span class="card-index">01</span>
        <h2>Vehículos</h2>
        <p>Consultá, modificá o eliminá el inventario actual.</p><span class="card-link">Ver inventario</span>
    </a>
    <a class="action-card" href="index.php?ruta=auto-cargar"><span class="card-index">02</span>
        <h2>Cargar automóvil</h2>
        <p>Incorporá un vehículo con sus datos esenciales.</p><span class="card-link">Nueva carga</span>
    </a>
    <a class="action-card" href="index.php?ruta=financiacion"><span class="card-index">03</span>
        <h2>Financiación</h2>
        <p>Definí la tasa mensual para los préstamos.</p><span class="card-link">Configurar tasa</span>
    </a>
    <a class="action-card" href="index.php?ruta=perfil"><span class="card-index">04</span>
        <h2>Mi perfil</h2>
        <p>Revisá tus datos personales y seguridad.</p><span class="card-link">Administrar cuenta</span>
    </a>
</section>
<?php require __DIR__ . "/../layouts/footer.php"; ?>
