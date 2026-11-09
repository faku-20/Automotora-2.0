<?php

require_once __DIR__ . '/../models/Conexion.php';

// 1. Crear un arreglo con los usuarios que vamos a registrar

$usuarios = [

    // Administradores
    ["usuario" => "admin01", "clave" => "Admin123!", "nombre" => "Carlos", "apellido" => "Perez", "tipo" => "administrador"],
    ["usuario" => "admin02", "clave" => "Admin123!", "nombre" => "Maria", "apellido" => "Rodriguez", "tipo" => "administrador"],
    ["usuario" => "admin03", "clave" => "Admin123!", "nombre" => "Juan", "apellido" => "Gonzalez", "tipo" => "administrador"],
    ["usuario" => "admin04", "clave" => "Admin123!", "nombre" => "Lucia", "apellido" => "Fernandez", "tipo" => "administrador"],
    ["usuario" => "admin05", "clave" => "Admin123!", "nombre" => "Diego", "apellido" => "Martinez", "tipo" => "administrador"],
    ["usuario" => "admin06", "clave" => "Admin123!", "nombre" => "Sofia", "apellido" => "Lopez", "tipo" => "administrador"],
    ["usuario" => "admin07", "clave" => "Admin123!", "nombre" => "Martin", "apellido" => "Silva", "tipo" => "administrador"],
    ["usuario" => "admin08", "clave" => "Admin123!", "nombre" => "Valentina", "apellido" => "Suarez", "tipo" => "administrador"],
    ["usuario" => "admin09", "clave" => "Admin123!", "nombre" => "Andres", "apellido" => "Romero", "tipo" => "administrador"],
    ["usuario" => "admin10", "clave" => "Admin123!", "nombre" => "Camila", "apellido" => "Torres", "tipo" => "administrador"],

    // Compradores
    ["usuario" => "comprador01", "clave" => "Compra123!", "nombre" => "Federico", "apellido" => "Garcia", "tipo" => "comprador"],
    ["usuario" => "comprador02", "clave" => "Compra123!", "nombre" => "Ana", "apellido" => "Mendez", "tipo" => "comprador"],
    ["usuario" => "comprador03", "clave" => "Compra123!", "nombre" => "Nicolas", "apellido" => "Pereira", "tipo" => "comprador"],
    ["usuario" => "comprador04", "clave" => "Compra123!", "nombre" => "Florencia", "apellido" => "Diaz", "tipo" => "comprador"],
    ["usuario" => "comprador05", "clave" => "Compra123!", "nombre" => "Santiago", "apellido" => "Viera", "tipo" => "comprador"],
    ["usuario" => "comprador06", "clave" => "Compra123!", "nombre" => "Agustina", "apellido" => "Castro", "tipo" => "comprador"],
    ["usuario" => "comprador07", "clave" => "Compra123!", "nombre" => "Facundo", "apellido" => "Mendez", "tipo" => "comprador"],
    ["usuario" => "comprador08", "clave" => "Compra123!", "nombre" => "Julieta", "apellido" => "Cabrera", "tipo" => "comprador"],
    ["usuario" => "comprador09", "clave" => "Compra123!", "nombre" => "Bruno", "apellido" => "Olivera", "tipo" => "comprador"],
    ["usuario" => "comprador10", "clave" => "Compra123!", "nombre" => "Victoria", "apellido" => "Morales", "tipo" => "comprador"]

];

try {

    // 2. Conectar con la base de datos
    $db = new Conexion();
    $conexion = $db->conectar();

    // 3. Preparar las consultas
    $buscar = $conexion->prepare(
        "SELECT id FROM usuarios WHERE usuario = ?"
    );

    $insertar = $conexion->prepare(
        "INSERT INTO usuarios
        (usuario, password, nombre, apellido, tipo_usuario)
        VALUES (?, ?, ?, ?, ?)"
    );

    $insertados = 0;
    $existentes = 0;

    // 4. Recorrer el arreglo de usuarios
    foreach ($usuarios as $u) {

        $usuario = $u["usuario"];

        // Comprobar si el usuario ya existe
        $buscar->bind_param("s", $usuario);
        $buscar->execute();
        $buscar->store_result();

        if ($buscar->num_rows > 0) {

            $existentes++;

        } else {

            // Generar el hash de la contraseña
            $passwordHash = password_hash(
                $u["clave"],
                PASSWORD_DEFAULT
            );

            $nombre = $u["nombre"];
            $apellido = $u["apellido"];
            $tipo = $u["tipo"];

            // Insertar el usuario
            $insertar->bind_param(
                "sssss",
                $usuario,
                $passwordHash,
                $nombre,
                $apellido,
                $tipo
            );

            $insertar->execute();

            $insertados++;
        }

        $buscar->free_result();
    }

    // 5. Mostrar el resultado
    echo "Proceso finalizado correctamente.<br>";
    echo "Usuarios insertados: " . $insertados . "<br>";
    echo "Usuarios que ya existían: " . $existentes . "<br>";

    $buscar->close();
    $insertar->close();
    $conexion->close();

} catch (Throwable $e) {

    echo "Error al cargar los usuarios: " . $e->getMessage();

}

?>
