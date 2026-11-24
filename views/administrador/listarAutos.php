<?php
/**
 * Variables provistas por AdministradorController::listarAutos() antes del require.
 * Se documentan porque el IDE analiza esta vista aislada, aunque PHP comparte el alcance.
 * @var string $csrfLogout
 * @var string $csrfToken
 * @var string $mensaje
 */
$tituloPagina = "Vehículos";
$seccionActual = "autos";
$autos = App::obtener(App::AUTOS, []);
require __DIR__ . "/../layouts/header.php";
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">Inventario</p>
        <h1>Vehículos</h1>
        <p>Gestioná los automóviles disponibles en la automotora.</p>
    </div>
    <a class="button button-primary" href="index.php?ruta=auto-cargar">Cargar automóvil</a>
</section>
<?php if ($mensaje !== ""): ?><div class="alert alert-success" role="status"><?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?></div><?php endif; ?>
<section class="content-card table-card">
    <div class="table-caption"><span><?= count($autos) ?> vehículo<?= count($autos) === 1 ? "" : "s" ?> en inventario</span><span>Datos sincronizados con MariaDB</span></div>
    <?php if (empty($autos)): ?>
        <div class="empty-state">
            <h2>No hay vehículos cargados</h2>
            <p>Comenzá agregando el primer automóvil al inventario.</p><a class="button button-primary" href="index.php?ruta=auto-cargar">Cargar automóvil</a>
        </div>
    <?php else: ?>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Color</th>
                        <th>Kilómetros</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($autos as $autito): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) $autito["marca"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars((string) $autito["modelo"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars((string) $autito["anio"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars((string) $autito["color"], ENT_QUOTES, "UTF-8") ?></td>
                            <td><?= htmlspecialchars((string) $autito["kilometros"], ENT_QUOTES, "UTF-8") ?> km</td>
                            <td class="price-cell">$<?= htmlspecialchars((string) $autito["precio"], ENT_QUOTES, "UTF-8") ?></td>
                            <td>
                                <div class="table-actions"><a class="button button-small button-secondary" href="index.php?ruta=auto-modificar&amp;id=<?= (int) $autito["id"] ?>">Modificar</a>
                                    <form action="index.php?ruta=auto-eliminar" method="POST" data-confirm-delete><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>"><input type="hidden" name="id" value="<?= (int) $autito["id"] ?>"><button class="button button-small button-danger" type="submit">Eliminar</button></form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . "/../layouts/footer.php"; ?>
