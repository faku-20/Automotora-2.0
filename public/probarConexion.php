<?php

require_once __DIR__ . '/../models/Conexion.php';
$prueba = new Conexion();
$conexion = $prueba->conectar();
echo "Conexión realizada correctamente";

?>