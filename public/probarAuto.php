<?php
require_once __DIR__ . '/../models/Auto.php';

$auto = new Auto();

$autos = $auto->listarAutos();

echo count($autos) . PHP_EOL;
foreach ($autos as $autitos) {
    echo $autitos["marca"] . " - " . $autitos["modelo"] . " -  $" . $autitos["precio"] . PHP_EOL;
}

?>