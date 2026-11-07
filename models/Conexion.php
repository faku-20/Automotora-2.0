<?php

class Conexion {

    public function conectar() {

        // Leer las credenciales del archivo .env
        $config = parse_ini_file(__DIR__ . '/../.env');

        if ($config === false) {
            throw new Exception("No se pudo leer el archivo .env");
        }

        // Obtener los datos de conexión
        $host = $config["DB_HOST"];
        $usuario = $config["DB_USER"];
        $password = $config["DB_PASSWORD"];
        $baseDatos = $config["DB_NAME"];

        // Establecer la conexión con MySQL
        $conexion = new mysqli($host, $usuario, $password, $baseDatos);

        return $conexion;
    }
}
