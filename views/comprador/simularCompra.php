<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Simular compra</title>
</head>
<body>

    <h1>Simulación de compra</h1>

    <h2>Automóvil seleccionado</h2>

    <p>Marca: <?= htmlspecialchars($vehiculo["marca"]) ?></p>
    <p>Modelo: <?= htmlspecialchars($vehiculo["modelo"]) ?></p>
    <p>Año: <?= htmlspecialchars((string) $vehiculo["anio"]) ?></p>
    <p>Precio: $<?= number_format($precio, 2, ',', '.') ?></p>

    <hr>

    <h2>Datos de financiación</h2>

    <form action="index.php?ruta=simular-compra&amp;id=<?= (int) $id ?>" method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>"
        >

        <label for="metodo_pago">Método de pago:</label>

        <select name="metodo_pago" id="metodo_pago" required>
            <option value="tarjeta"
                <?= $metodoPago === "tarjeta" ? "selected" : "" ?>>
                Tarjeta de crédito (hasta 12 cuotas sin recargo)
            </option>

            <option value="prestamo"
                <?= $metodoPago === "prestamo" ? "selected" : "" ?>>
                Préstamo de la automotora (hasta 100 cuotas)
            </option>
        </select>

        <br><br>

        <label for="entrega">Entrega inicial:</label>

        <input
            type="number"
            name="entrega"
            id="entrega"
            min="0"
            max="<?= htmlspecialchars((string) $precio) ?>"
            step="0.01"
            value="<?= htmlspecialchars(
                is_scalar($entregaIngresada)
                    ? (string) $entregaIngresada
                    : ""
            ) ?>"
            required
        >

        <br><br>

        <label for="cuotas">Cantidad de cuotas:</label>

        <input
            type="number"
            name="cuotas"
            id="cuotas"
            min="1"
            max="<?= $metodoPago === "tarjeta" ? 12 : 100 ?>"
            value="<?= htmlspecialchars(
                is_scalar($cuotasIngresadas)
                    ? (string) $cuotasIngresadas
                    : ""
            ) ?>"
            required
        >

        <p id="informacion_financiacion"></p>

        <button type="submit">Calcular simulación</button>

    </form>

    <?php if ($error !== ""): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($simulacionRealizada): ?>

        <hr>

        <h2>Resumen de la simulación</h2>

        <p>
            Método de pago:
            <?= $metodoPago === "tarjeta"
                ? "Tarjeta de crédito"
                : "Préstamo de la automotora" ?>
        </p>

        <p>
            Precio del automóvil:
            $<?= number_format($precio, 2, ',', '.') ?>
        </p>

        <p>
            Entrega inicial:
            $<?= number_format($entrega, 2, ',', '.') ?>
        </p>

        <p>
            Saldo financiado:
            $<?= number_format($saldo, 2, ',', '.') ?>
        </p>

        <p>Cantidad de cuotas: <?= $cuotas ?></p>

        <p>
            Tasa de interés mensual:
            <?= $metodoPago === "tarjeta"
                ? "0"
                : number_format($tasaMensual, 2, ',', '.') ?>%
        </p>

        <h3>
            Valor de cada cuota:
            $<?= number_format($valorCuota, 2, ',', '.') ?>
        </h3>

        <p>
            Total de intereses:
            $<?= number_format($totalIntereses, 2, ',', '.') ?>
        </p>

        <p>
            Total financiado:
            $<?= number_format($totalFinanciado, 2, ',', '.') ?>
        </p>

        <p>
            Total de la compra incluyendo la entrega:
            $<?= number_format($totalCompra, 2, ',', '.') ?>
        </p>

        <p>
            Esta es una simulación orientativa, no una oferta de crédito.
        </p>

    <?php endif; ?>

    <br>

    <a href="index.php?ruta=catalogo">Volver al catálogo</a>

    <script>
        const metodoPago = document.getElementById("metodo_pago");
        const cuotasInput = document.getElementById("cuotas");
        const informacion = document.getElementById("informacion_financiacion");

        function actualizarMetodoPago() {
            const esTarjeta = metodoPago.value === "tarjeta";
            const maximo = esTarjeta ? 12 : 100;

            cuotasInput.max = maximo;

            if (cuotasInput.value !== "" &&
                Number(cuotasInput.value) > maximo) {
                cuotasInput.value = maximo;
            }

            informacion.textContent = esTarjeta
                ? "Hasta 12 cuotas sin recargo."
                : "Hasta 100 cuotas con interés mensual configurable.";
        }

        metodoPago.addEventListener("change", actualizarMetodoPago);
        actualizarMetodoPago();
    </script>

</body>
</html>
