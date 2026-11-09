
<?php
// este archivo se encarga de procesar y hacer una calculadora de financiación.

require_once __DIR__ . '/../../models/Auto.php';

$auto = new Auto();

// se captura el ID del URL.
$id = (int) $_GET["id"];

// busca los demás datos del auto en la BD.
$vehiculo = $auto->obtenerAutoPorId($id);

// Verificar que el vehículo exista
if (!$vehiculo) {
    echo "El automóvil seleccionado no existe.";
    exit;
}

$error = "";
$saldo = 0;
$valorCuota = 0;
$simulacionRealizada = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $entrega = $_POST["entrega"] ?? "";
    $cuotas = $_POST["cuotas"] ?? "";

    // se asegura que $entrega sea un valor valido y que $cuotas sea un numero natural.
    if (!is_numeric($entrega) ||
        !ctype_digit((string)$cuotas) ||
        $entrega < 0 ||
        $entrega > $vehiculo["precio"] ||
        (int)$cuotas <= 0) {

        $error = "Verificá la entrega inicial y la cantidad de cuotas.";

    } else { // calulo puro de las cuotas y saldo restante.

        $entrega = (float)$entrega;
        $cuotas = (int)$cuotas;

        // Calcular saldo restante
        $saldo = $vehiculo["precio"] - $entrega;

        // Calcular valor de cada cuota
        $valorCuota = $saldo / $cuotas;

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

    <p>Año: <?= htmlspecialchars((string)$vehiculo["anio"]) ?></p>

    <p>Precio: $<?= number_format($vehiculo["precio"], 2, ',', '.') ?></p>

    <hr>

    <h2>Datos de financiación</h2>

    <!-- en los inputs se valida que sean valores correctos -->
    <form method="POST">

        <label for="entrega">Entrega inicial:</label>

        <input
            type="number"
            name="entrega"
            id="entrega"
            min="0"
            max="<?= htmlspecialchars((string)$vehiculo["precio"]) ?>"
            step="0.01"
            required
        >

        <br><br>

        <label for="cuotas">Cantidad de cuotas:</label>

        <input
            type="number"
            name="cuotas"
            id="cuotas"
            min="1"
            required
        >

        <br><br>

        <button type="submit">Calcular simulación</button>

    </form>

    <?php if ($error !== "") { ?>

        <p><?= htmlspecialchars($error) ?></p>

    <?php } ?>

    <?php if ($simulacionRealizada) { ?>

        <hr>

        // desgloce con el plan de pagos.
        <h2>Resumen de la simulación</h2>

        <p>
            Precio del automóvil:
            $<?= number_format($vehiculo["precio"], 2, ',', '.') ?>
        </p>

        <p>
            Entrega inicial:
            $<?= number_format($entrega, 2, ',', '.') ?>
        </p>

        <p>
            Saldo restante:
            $<?= number_format($saldo, 2, ',', '.') ?>
        </p>

        <p>
            Cantidad de cuotas: <?= $cuotas ?>
        </p>

        <h3>
            Valor de cada cuota:
            $<?= number_format($valorCuota, 2, ',', '.') ?>
        </h3>

        <p>
            Simulación sin intereses.
        </p>

    <?php } ?>

    <br>

    <a href="verCatalogo.php">Volver al catálogo</a>

</body>
</html>
