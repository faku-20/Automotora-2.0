<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo los administradores pueden modificar automóviles
if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: ../../public/index.php");
    exit;
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../../models/Auto.php';

//comprobamos q el id del auto sea un numero entero y positivo
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    exit("El id del auto es invalido");
}
$auto = new Auto();
$datos = $auto->buscarAutoXID($id);

if (!$datos) {
    exit("El auto no existe");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tokenSesion = $_SESSION["csrf_token"] ?? "";
    $tokenRecibido = $_POST["csrf_token"] ?? "";

    if (
        !is_string($tokenRecibido) ||
        !is_string($tokenSesion) ||
        $tokenSesion === "" ||
        !hash_equals($tokenSesion, $tokenRecibido)
    ) {
        exit("Token CSRF inválido");
    }

    $campos = ["marca", "modelo", "anio", "color", "kilometros", "precio"];
    foreach ($campos as $campo) {
        if (isset($_POST[$campo]) && !is_string($_POST[$campo])) {
            exit("El campo $campo contiene un valor inválido");
        }
    }
    $anioActual = (int) date("Y");

    $marca = trim($_POST["marca"] ?? "");
    $modelo = trim($_POST["modelo"] ?? "");
    $anio = $_POST["anio"] ?? "";
    $color = trim($_POST["color"] ?? "");
    $kilometros = $_POST["kilometros"] ?? "";
    $precio = $_POST["precio"] ?? "";
    if ($marca === "" || $modelo === "" || $color === "" || $anio === "" || $kilometros === "" || $precio === "") {
        echo "Todos los campos son obligatorios";
    } elseif (!is_numeric($kilometros) || !is_numeric($precio)) {
        echo "Los kilometros y el precio deben ser números";
    } elseif ($kilometros < 0 || $precio <= 0) {
        echo "Los kilómetros no pueden ser menores a 0 y el precio debe ser mayor a 0";
    } elseif (filter_var($anio, FILTER_VALIDATE_INT) === false) {
        echo "El año debe ser un numero entero";
    } elseif ($anio < 1886 || $anio > $anioActual) {
        echo "El año debe estar entre 1886 y $anioActual";
    } elseif (filter_var($kilometros, FILTER_VALIDATE_INT) === false) {
        echo "Los kilometros deben ser un número entero";
    } else {
        $resultado = $auto->modificarAuto($id, $marca, $modelo, $anio, $color, $kilometros, $precio);

        if ($resultado) {

            if (isset($_SESSION["autos"]) && is_array($_SESSION["autos"])) {
                foreach ($_SESSION["autos"] as &$autito) {
                    if ((int) $autito["id"] === $id) {
                        $autito["marca"] = $marca;
                        $autito["modelo"] = $modelo;
                        $autito["anio"] = (int) $anio;
                        $autito["color"] = $color;
                        $autito["kilometros"] = (int) $kilometros;
                        $autito["precio"] = (float) $precio;
                        break;
                    }
                }

                unset($autito);
            }

            $_SESSION["mensaje"] = "El automóvil fue modificado correctamente";

            header("Location: listarAutos.php");
            exit;
        } else {
            echo "Error al modificar el auto";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar automóvil - Automotora</title>
</head>

<body>
    <form action="" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" value="<?= htmlspecialchars($datos['marca']) ?>" required>
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" value="<?= htmlspecialchars($datos['modelo']) ?>" required>
        <label for="anio">Año:</label>
        <input type="number" id="anio" name="anio" value="<?= htmlspecialchars($datos['anio']) ?>" required>
        <label for="color">Color:</label>
        <input type="text" id="color" name="color" value="<?= htmlspecialchars($datos['color']) ?>" required>
        <label for="kilometros">Kilometros:</label>
        <input type="number" id="kilometros" name="kilometros" value="<?= htmlspecialchars($datos['kilometros']) ?>" required>
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" min="0" value="<?= htmlspecialchars($datos['precio']) ?>" required>

        <button type="submit">Guardar cambios</button>
    </form>
</body>

</html>