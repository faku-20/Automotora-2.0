<?php
/**
 * Variables provistas por ContactoController.
 * @var bool $contactoEnviado
 * @var array|null $simulacion
 * @var string $csrfToken
 * @var string $error
 * @var array{nombre:string,email:string,telefono:string,mensaje:string} $valores
 */
$tituloPagina = "Contactar a la automotora";
$seccionActual = "simular-compra";
require __DIR__ . "/../layouts/header.php";
?>
<section class="page-heading">
    <div>
        <a class="back-link" href="index.php?ruta=catalogo">Volver al catálogo</a>
        <p class="eyebrow">Solicitud de compra</p>
        <h1>Contactar al vendedor</h1>
        <p>Dejanos tus datos y tu consulta. La solicitud se enviará al entorno de pruebas de la automotora.</p>
    </div>
</section>

<?php if ($contactoEnviado): ?>
    <section class="content-card form-card contact-card" role="status">
        <div class="eyebrow">Solicitud registrada</div>
        <h2>¡Tu solicitud fue enviada correctamente!</h2>
        <p>La automotora recibió el mensaje en el entorno de pruebas de Mailtrap.</p>
        <a class="button button-primary" href="index.php?ruta=catalogo">Volver al catálogo</a>
    </section>
<?php else: ?>
    <div class="contact-layout">
        <section class="content-card contact-summary">
            <p class="eyebrow">Tu simulación</p>
            <h2><?= htmlspecialchars($simulacion["auto"]["marca"] . " " . $simulacion["auto"]["modelo"], ENT_QUOTES, "UTF-8") ?></h2>
            <p><?= (int) $simulacion["auto"]["anio"] ?> · $<?= number_format((float) $simulacion["auto"]["precio"], 2, ',', '.') ?></p>
            <dl class="result-list">
                <div>
                    <dt>Método de pago</dt>
                    <dd><?= $simulacion["metodo_pago"] === "tarjeta" ? "Tarjeta de crédito" : "Préstamo de la automotora" ?></dd>
                </div>
                <div>
                    <dt>Entrega inicial</dt>
                    <dd>$<?= number_format((float) $simulacion["entrega"], 2, ',', '.') ?></dd>
                </div>
                <div>
                    <dt>Cuotas</dt>
                    <dd><?= (int) $simulacion["cuotas"] ?> de $<?= number_format((float) $simulacion["valor_cuota"], 2, ',', '.') ?></dd>
                </div>
                <div>
                    <dt>Total estimado</dt>
                    <dd>$<?= number_format((float) $simulacion["total_compra"], 2, ',', '.') ?></dd>
                </div>
            </dl>
            <p class="result-note">La información de esta simulación se incluirá en el correo.</p>
        </section>

        <section class="content-card form-card contact-card">
            <p class="eyebrow">Tus datos</p>
            <h2>Enviá tu consulta</h2>
            <?php if ($error !== ""): ?>
                <div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
            <?php endif; ?>
            <form class="form-stack" action="index.php?ruta=contacto-enviar" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>">
                <div class="field-group">
                    <label for="nombre">Nombre completo *</label>
                    <input type="text" id="nombre" name="nombre" maxlength="120" value="<?= htmlspecialchars($valores["nombre"], ENT_QUOTES, "UTF-8") ?>" autocomplete="name" required>
                </div>
                <div class="field-group">
                    <label for="email">Correo electrónico *</label>
                    <input type="email" id="email" name="email" maxlength="254" value="<?= htmlspecialchars($valores["email"], ENT_QUOTES, "UTF-8") ?>" autocomplete="email" required>
                </div>
                <div class="field-group">
                    <label for="telefono">Teléfono (opcional)</label>
                    <input type="tel" id="telefono" name="telefono" maxlength="30" value="<?= htmlspecialchars($valores["telefono"], ENT_QUOTES, "UTF-8") ?>" autocomplete="tel">
                </div>
                <div class="field-group">
                    <label for="mensaje">Mensaje *</label>
                    <textarea id="mensaje" name="mensaje" rows="5" maxlength="2000" required><?= htmlspecialchars($valores["mensaje"], ENT_QUOTES, "UTF-8") ?></textarea>
                    <small>Contanos si querés coordinar una visita o avanzar con la compra.</small>
                </div>
                <div class="form-actions">
                    <button class="button button-primary" type="submit">Enviar solicitud</button>
                    <a class="button button-secondary" href="index.php?ruta=catalogo">Cancelar</a>
                </div>
            </form>
        </section>
    </div>
<?php endif; ?>

<?php require __DIR__ . "/../layouts/footer.php"; ?>
