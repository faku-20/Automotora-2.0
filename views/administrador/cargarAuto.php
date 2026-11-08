<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php
    require_once __DIR__ . '/../../models/Auto.php';

    $auto = new Auto();
    
    //trim elimina los espacios vacios al principio y al final de un texto

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $marca = trim($_POST["marca"]);
        $modelo = trim($_POST["modelo"]);
        $anio = $_POST["anio"];
        $color = trim($_POST["color"]);
        $kilometros = $_POST["kilometros"];
        $precio = $_POST["precio"];
        if (empty($marca) || empty($modelo) || empty($color)) {
            echo "La marca, el modelo y el color son obligatorios";
        } else {
            //ahora llamamos la metodo cargar auto
            $resultado = $auto->cargarAuto($marca, $modelo, $anio, $color, $kilometros, $precio);
            //si $resultado = true, muestra el mesjaje de exito, sino pasa al else
            if($resultado){
                echo "El auto fue cargado correctamente";
                
            }else {
                echo "Error al cargar el auto";
            }
        }
    }

    ?>
    <h2>Cargar automóvil</h2>


    <form action="" method="POST">
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