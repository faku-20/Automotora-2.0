<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Conexion.php';

/**
 * Regenera exclusivamente los datos de demostración de Automotora.
 * Uso: php scripts/reinicializarDatosDemo.php
 */

$config = parse_ini_file(__DIR__ . '/../.env');
if ($config === false) {
    throw new RuntimeException('No se pudo leer el archivo .env.');
}

$host = $config['DB_HOST'] ?? '';
$baseDatos = $config['DB_NAME'] ?? '';
if (!in_array($host, ['localhost', '127.0.0.1', '::1'], true) || $baseDatos !== 'automotora') {
    throw new RuntimeException('La reinicialización sólo admite la base local automotora.');
}

$usuarios = [
    ['admin01', 'AdminDemo2026!', 'Alma', 'Rivas', 'administrador'],
    ['admin02', 'AdminDemo2026!', 'Bruno', 'Lagos', 'administrador'],
    ['admin03', 'AdminDemo2026!', 'Clara', 'Nadal', 'administrador'],
    ['admin04', 'AdminDemo2026!', 'Dario', 'Mora', 'administrador'],
    ['admin05', 'AdminDemo2026!', 'Elena', 'Vidal', 'administrador'],
    ['admin06', 'AdminDemo2026!', 'Fabian', 'Soler', 'administrador'],
    ['admin07', 'AdminDemo2026!', 'Greta', 'Acosta', 'administrador'],
    ['admin08', 'AdminDemo2026!', 'Hugo', 'Paz', 'administrador'],
    ['admin09', 'AdminDemo2026!', 'Ines', 'Costa', 'administrador'],
    ['admin10', 'AdminDemo2026!', 'Joaquin', 'Rey', 'administrador'],
    ['comprador01', 'CompraDemo2026!', 'Kiara', 'Vega', 'comprador'],
    ['comprador02', 'CompraDemo2026!', 'Lautaro', 'Gil', 'comprador'],
    ['comprador03', 'CompraDemo2026!', 'Mara', 'Cano', 'comprador'],
    ['comprador04', 'CompraDemo2026!', 'Nereo', 'Luna', 'comprador'],
    ['comprador05', 'CompraDemo2026!', 'Olivia', 'Rios', 'comprador'],
    ['comprador06', 'CompraDemo2026!', 'Pablo', 'Farias', 'comprador'],
    ['comprador07', 'CompraDemo2026!', 'Rocio', 'Pardo', 'comprador'],
    ['comprador08', 'CompraDemo2026!', 'Simon', 'Vera', 'comprador'],
    ['comprador09', 'CompraDemo2026!', 'Tania', 'Sosa', 'comprador'],
    ['comprador10', 'CompraDemo2026!', 'Ulises', 'Neri', 'comprador'],
];

$autos = [
    ['Toyota', 'Corolla XEI', 2022, 'Blanco perlado', 32000, 28900.00],
    ['Volkswagen', 'T-Cross', 2023, 'Gris plata', 21000, 31500.00],
    ['Chevrolet', 'Onix Premier', 2022, 'Azul oscuro', 28500, 22400.00],
    ['Hyundai', 'Creta', 2024, 'Negro', 9800, 36400.00],
    ['Nissan', 'Kicks', 2021, 'Rojo', 41700, 24800.00],
    ['Peugeot', '208 Allure', 2023, 'Gris grafito', 15600, 26700.00],
    ['Renault', 'Duster', 2020, 'Blanco', 58700, 21900.00],
    ['Fiat', 'Cronos Precision', 2024, 'Plateado', 7400, 25500.00],
    ['Honda', 'HR-V', 2022, 'Bordó', 26300, 34200.00],
    ['Ford', 'Territory', 2023, 'Azul marino', 19400, 41200.00],
    ['Suzuki', 'Vitara', 2021, 'Verde oliva', 38800, 27600.00],
    ['Jeep', 'Renegade Sport', 2022, 'Negro', 30100, 33700.00],
    ['Kia', 'Seltos', 2024, 'Blanco', 6200, 38900.00],
    ['Mazda', 'CX-30', 2023, 'Rojo intenso', 12700, 40500.00],
    ['Subaru', 'Crosstrek', 2024, 'Gris oscuro', 4900, 43800.00],
];

try {
    $conexion = (new Conexion())->conectar();
    $conexion->begin_transaction();

    // No hay claves foráneas en el esquema actual; sólo se limpian las tablas del proyecto.
    $conexion->query('DELETE FROM usuarios');
    $conexion->query('DELETE FROM autos');
    $conexion->query('DELETE FROM configuracion_financiacion');

    $insertarUsuario = $conexion->prepare(
        'INSERT INTO usuarios (id, usuario, password, nombre, apellido, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?)'
    );
    foreach ($usuarios as $indice => [$usuario, $clave, $nombre, $apellido, $tipoUsuario]) {
        $id = $indice + 1;
        $hash = password_hash($clave, PASSWORD_DEFAULT);
        $insertarUsuario->bind_param('isssss', $id, $usuario, $hash, $nombre, $apellido, $tipoUsuario);
        $insertarUsuario->execute();
    }
    $insertarUsuario->close();

    $insertarAuto = $conexion->prepare(
        'INSERT INTO autos (id, marca, modelo, anio, color, kilometros, precio) VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    foreach ($autos as $indice => [$marca, $modelo, $anio, $color, $kilometros, $precio]) {
        $id = $indice + 1;
        $insertarAuto->bind_param('issisid', $id, $marca, $modelo, $anio, $color, $kilometros, $precio);
        $insertarAuto->execute();
    }
    $insertarAuto->close();

    $tasa = 2.00;
    $insertarTasa = $conexion->prepare(
        'INSERT INTO configuracion_financiacion (id, tasa_interes_mensual) VALUES (1, ?)'
    );
    $insertarTasa->bind_param('d', $tasa);
    $insertarTasa->execute();
    $insertarTasa->close();

    $conexion->commit();
    $conexion->close();

    echo "Datos de demostración regenerados: 10 administradores, 10 compradores, 15 vehículos y tasa mensual del 2 %.\n";
} catch (Throwable $e) {
    if (isset($conexion) && $conexion instanceof mysqli) {
        $conexion->rollback();
        $conexion->close();
    }

    fwrite(STDERR, "No se modificaron los datos: " . $e->getMessage() . PHP_EOL);
    exit(1);
}
