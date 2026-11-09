<?php
require_once __DIR__ . '/../views/administrador/modificarAuto.php';

$auto = new Auto();

$resultado = $auto->modificarAuto(1, "Toyota", "Corolla", 2020, "Negro", 45000, 19500);

if ($resultado) {
    echo "Consulta de modificación ejecutada correctamente";
} else {
    echo "Error al modificar el automóvil";
}
