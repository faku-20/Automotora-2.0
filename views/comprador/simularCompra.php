
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo pueden acceder los compradores
if (($_SESSION["tipo_usuario"] ?? null) !== "comprador") {
    header("Location: /public/index.php");
    exit;
}

require_once __DIR__ . '/../../models/Auto.php';
require_once __DIR__ . '/../../models/Financiacion.php';

// Buscar el automóvil seleccionado
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    echo "Debés seleccionar un automóvil válido.";
    exit;
}

$auto = new Auto();
$vehiculo = $auto->buscarAutoXID($id);

if (!$vehiculo) {
    echo "El automóvil seleccionado no existe.";
    exit;
}

// Consultar la tasa configurada por el administrador
$financiacion = new Financiacion();
$tasaMensual = $financiacion->obtenerTasa();

if ($tasaMensual === null) {
    echo "No se pudo obtener la configuración de financiación.";
    exit;
}

$precio = (float) $vehiculo["precio"];

$error = "";
$simulacionRealizada = false;

$metodoPago = $_POST["metodo_pago"] ?? "tarjeta";
$entregaIngresada = $_POST["entrega"] ?? "";
$cuotasIngresadas = $_POST["cuotas"] ?? "";

$entrega = 0;
$cuotas = 0;
$saldo = 0;
$valorCuota = 0;
$totalFinanciado = 0;
$totalIntereses = 0;
$totalCompra = 0;

if (empty($_SESSION["csrf_simulacion"])) {
    $_SESSION["csrf_simulacion"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (
        !is_string($token) ||
        !hash_equals($_SESSION["csrf_simulacion"], $token)
    ) {
        $error = "Solicitud no válida.";

    } elseif (
        !is_string($metodoPago) ||
        !in_array($metodoPago, ["tarjeta", "prestamo"], true)
    ) {
        $error = "Seleccioná un método de pago válido.";

    } elseif (
        !is_scalar($entregaIngresada) ||
        !is_numeric($entregaIngresada) ||
        !is_finite((float) $entregaIngresada) ||
        (float) $entregaIngresada < 0 ||
        (float) $entregaIngresada > $precio
    ) {
        $error = "Ingresá una entrega inicial válida.";

    } elseif (
        !is_scalar($cuotasIngresadas) ||
        !ctype_digit((string) $cuotasIngresadas) ||
        (float) $cuotasIngresadas < 1 ||
        (float) $cuotasIngresadas >
            ($metodoPago === "tarjeta" ? 12 : 100)
    ) {
        $error = "La cantidad de cuotas no es válida para el método seleccionado.";

    } else {
        $entrega = (float) $entregaIngresada;
        $cuotas = (int) $cuotasIngresadas;

        $saldo = $precio - $entrega;

        if ($metodoPago === "tarjeta") {
            // Hasta 12 cuotas sin intereses
            $valorCuota = $saldo / $cuotas;

        } else {
            // Préstamo: sistema de amortización francés
            $tasa = $tasaMensual / 100;

            if ($tasa == 0) {
                $valorCuota = $saldo / $cuotas;
            } else {
                $factor = pow(1 + $tasa, -$cuotas);

                $valorCuota = ($saldo * $tasa) / (1 - $factor);
            }
        }

        $totalFinanciado = $valorCuota * $cuotas;
        $totalIntereses = max(0, $totalFinanciado - $saldo);
        $totalCompra = $entrega + $totalFinanciado;

        $simulacionRealizada = true;
    }
}
?>

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

    <form method="POST">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION["csrf_simulacion"]) ?>"
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

    <a href="/public/verCatalogo.php">Volver al catálogo</a>

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
