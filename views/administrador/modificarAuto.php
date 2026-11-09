<?php
session_start();

// Solo los administradores pueden modificar automóviles
if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: ../../public/index.php");
    exit;
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
    $marca = trim($_POST["marca"]);
    $modelo = trim($_POST["modelo"]);
    $anio = $_POST["anio"];
    $color = trim($_POST["color"]);
    $kilometros = $_POST["kilometros"];
    $precio = $_POST["precio"];
    if (empty($marca) || empty($modelo) || empty($color)) {
        echo "La marca, el modelo y el color son obligatorios";
    } elseif (!is_numeric($kilometros) || !is_numeric($precio)) {
        echo "Los kilometros y el precio deben ser números";
    } elseif ($kilometros < 0 || $precio <= 0) {
        echo "Los kilometros y el precio no pueden ser menor a 0";
    } elseif (filter_var($anio, FILTER_VALIDATE_INT) === false) {
        echo "El año debe ser un numero entero";
    } elseif ($anio < 1886 || $anio > 2026) {
        echo "El año debe estar entre 1886 y 2026";
    } elseif (filter_var($kilometros, FILTER_VALIDATE_INT) === false) {
        echo "Los kilometros deben ser un número entero";
    } else {
        $resultado = $auto->modificarAuto($id, $marca, $modelo, $anio, $color, $kilometros, $precio);

        if ($resultado) {
            echo "Auto modificado correctamente";
            $datos = $auto->buscarAutoXID($id);
        } else {
            echo "Error al modificar el auto";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="POST">
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