<?php
/**
 * Variables provistas por CompradorController::simularCompra() antes del require.
 * Estas anotaciones describen el alcance compartido de PHP y sólo corrigen falsos positivos del IDE.
 * @var string $csrfLogout
 * @var int $id
 * @var string $csrfToken
 * @var string $error
 * @var bool $simulacionRealizada
 * @var string $metodoPago
 * @var scalar $entregaIngresada
 * @var scalar $cuotasIngresadas
 * @var float $precio
 * @var float $entrega
 * @var int $cuotas
 * @var float $saldo
 * @var float $valorCuota
 * @var float $totalIntereses
 * @var float $totalCompra
 * @var float $tasaMensual
 * @var array{id:int, marca:string, modelo:string, anio:int, color:string, kilometros:int, precio:float} $vehiculo
 */
$tituloPagina = "Simulador de compra";
$seccionActual = "simular-compra";
require __DIR__ . "/../layouts/header.php";
?>
<section class="simulator-heading"><a class="back-link" href="index.php?ruta=catalogo">Volver al catálogo</a>
    <p class="eyebrow">Financiación</p>
    <h1>Simulador de compra</h1>
    <p>Configurá una alternativa y conocé el detalle estimado de tu compra.</p>
</section>
<section class="simulator-grid">
    <article class="vehicle-sheet"><span class="vehicle-monogram large" aria-hidden="true"><?= htmlspecialchars(mb_strtoupper(mb_substr((string) $vehiculo["marca"], 0, 1)), ENT_QUOTES, "UTF-8") ?></span>
        <p class="eyebrow">Vehículo seleccionado</p>
        <h2><?= htmlspecialchars($vehiculo["marca"], ENT_QUOTES, "UTF-8") ?> <?= htmlspecialchars($vehiculo["modelo"], ENT_QUOTES, "UTF-8") ?></h2>
        <p class="vehicle-year"><?= htmlspecialchars((string) $vehiculo["anio"], ENT_QUOTES, "UTF-8") ?></p>
        <dl class="vehicle-facts">
            <div>
                <dt>Color</dt>
                <dd><?= htmlspecialchars($vehiculo["color"], ENT_QUOTES, "UTF-8") ?></dd>
            </div>
            <div>
                <dt>Kilometraje</dt>
                <dd><?= htmlspecialchars((string) $vehiculo["kilometros"], ENT_QUOTES, "UTF-8") ?> km</dd>
            </div>
            <div>
                <dt>Precio</dt>
                <dd>$<?= number_format($precio, 2, ',', '.') ?></dd>
            </div>
        </dl>
    </article>
    <section class="simulator-workspace">
        <div class="content-card form-card simulation-form">
            <p class="eyebrow">Financiación</p>
            <h2>Definí las condiciones</h2>
            <form class="form-grid simulator-form-grid" action="index.php?ruta=simular-compra&amp;id=<?= (int) $id ?>" method="POST"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>">
                <div class="field-group field-full"><label for="metodo_pago">Método de pago</label><select name="metodo_pago" id="metodo_pago" required>
                        <option value="tarjeta" <?= $metodoPago === "tarjeta" ? "selected" : "" ?>>Tarjeta de crédito (hasta 12 cuotas sin recargo)</option>
                        <option value="prestamo" <?= $metodoPago === "prestamo" ? "selected" : "" ?>>Préstamo de la automotora (hasta 100 cuotas)</option>
                    </select></div>
                <div class="field-group"><label for="entrega">Entrega inicial</label><input type="number" name="entrega" id="entrega" min="0" max="<?= htmlspecialchars((string) $precio, ENT_QUOTES, "UTF-8") ?>" step="0.01" value="<?= htmlspecialchars(is_scalar($entregaIngresada) ? (string) $entregaIngresada : "", ENT_QUOTES, "UTF-8") ?>" required></div>
                <div class="field-group"><label for="cuotas">Cantidad de cuotas</label><input type="number" name="cuotas" id="cuotas" min="1" max="<?= $metodoPago === "tarjeta" ? 12 : 100 ?>" value="<?= htmlspecialchars(is_scalar($cuotasIngresadas) ? (string) $cuotasIngresadas : "", ENT_QUOTES, "UTF-8") ?>" required></div>
                <p id="informacion_financiacion" class="form-help field-full"></p>
                <div class="form-actions field-full"><button class="button button-primary" type="submit">Calcular simulación</button></div>
            </form>
        </div>
        <?php if ($error !== ""): ?><div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div><?php endif; ?>
        <?php if ($simulacionRealizada): ?><section class="simulation-result" aria-live="polite">
                <p class="eyebrow">Resultado de la simulación</p>
                <div class="monthly-payment"><span>Valor de cada cuota</span><strong>$<?= number_format($valorCuota, 2, ',', '.') ?></strong></div>
                <dl class="result-list">
                    <div>
                        <dt>Método de pago</dt>
                        <dd><?= $metodoPago === "tarjeta" ? "Tarjeta de crédito" : "Préstamo de la automotora" ?></dd>
                    </div>
                    <div>
                        <dt>Entrega inicial</dt>
                        <dd>$<?= number_format($entrega, 2, ',', '.') ?></dd>
                    </div>
                    <div>
                        <dt>Saldo financiado</dt>
                        <dd>$<?= number_format($saldo, 2, ',', '.') ?></dd>
                    </div>
                    <div>
                        <dt>Cuotas / tasa mensual</dt>
                        <dd><?= $cuotas ?> / <?= $metodoPago === "tarjeta" ? "0" : number_format($tasaMensual, 2, ',', '.') ?>%</dd>
                    </div>
                    <div>
                        <dt>Total de intereses</dt>
                        <dd>$<?= number_format($totalIntereses, 2, ',', '.') ?></dd>
                    </div>
                    <div>
                        <dt>Total de la compra</dt>
                        <dd>$<?= number_format($totalCompra, 2, ',', '.') ?></dd>
                    </div>
                </dl>
                <p class="result-note">Esta es una simulación orientativa, no una oferta de crédito.</p>
            </section><?php endif; ?>
    </section>
</section>
<?php require __DIR__ . "/../layouts/footer.php"; ?>
