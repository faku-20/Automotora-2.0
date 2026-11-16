<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: /public/index.php");
    exit;
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../../models/Auto.php';

$auto = new Auto();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tokenSesion = $_SESSION["csrf_token"];
    $tokenRecibido = $_POST["csrf_token"] ?? "";

    if (
        !is_string($tokenRecibido) ||
        !hash_equals($tokenSesion, $tokenRecibido)
    ) {
        exit("Token CSRF inválido");
    }

    $campos = [
        "marca",
        "modelo",
        "anio",
        "color",
        "kilometros",
        "precio"
    ];

    foreach ($campos as $campo) {
        if (
            !isset($_POST[$campo]) ||
            !is_string($_POST[$campo])
        ) {
            exit("Datos inválidos en el campo: $campo");
        }
    }

    $marca = trim($_POST["marca"]);
    $modelo = trim($_POST["modelo"]);
    $anio = trim($_POST["anio"]);
    $color = trim($_POST["color"]);
    $kilometros = trim($_POST["kilometros"]);
    $precio = trim($_POST["precio"]);

    $anioActual = (int) date("Y");

    if (
        $marca === "" ||
        $modelo === "" ||
        $anio === "" ||
        $color === "" ||
        $kilometros === "" ||
        $precio === ""
    ) {
        $error = "Todos los campos son obligatorios.";
    } elseif (
        filter_var($anio, FILTER_VALIDATE_INT) === false ||
        (int) $anio < 1886 ||
        (int) $anio > $anioActual
    ) {
        $error = "El año debe estar entre 1886 y $anioActual.";
    } elseif (
        filter_var($kilometros, FILTER_VALIDATE_INT) === false ||
        (int) $kilometros < 0
    ) {
        $error = "Los kilómetros deben ser un entero mayor o igual a cero.";
    } elseif (
        !is_numeric($precio) ||
        !is_finite((float) $precio) ||
        (float) $precio <= 0
    ) {
        $error = "El precio debe ser un número mayor a cero.";
    } else {
        $resultado = $auto->cargarAuto(
            $marca,
            $modelo,
            (int) $anio,
            $color,
            (int) $kilometros,
            (float) $precio
        );

        if ($resultado) {
            unset($_SESSION["autos"]);

            $_SESSION["mensaje"] =
                "El automóvil fue cargado correctamente.";

            header("Location: listarAutos.php");
            exit;
        }

        $error = "No se pudo cargar el automóvil.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <h2>Cargar automóvil</h2>
    <?php if ($error !== ""): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" required>

        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" required>

        <label for="anio">Año:</label>
        <input type="number" id="anio" name="anio" required>

        <label for="color">Color:</label>
        <input type="text" id="color" name="color" required>

        <label for="kilometros">Kilómetros:</label>
        <input type="number" id="kilometros" name="kilometros" min="0" required>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" min="0" step="0.01" required>

        <button type="submit">Cargar automóvil</button>
    </form>

</body>

</html>