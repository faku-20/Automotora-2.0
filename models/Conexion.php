<?php

class Conexion {

    private $host = 'localhost'; //lugar donde va a estar la bd
    private $usuario = 'automotora_app'; // Usuario de la bd 
    private $password = 'automotora123'; // Contraseña del usuario en la bd
    private $baseDatos = 'automotora'; // Nombre de la base de datos para este ejemplo

    public function conectar() {
        $conexion = new mysqli ($this->host, $this->usuario, $this->password, $this->baseDatos);
        return $conexion;
    }
}

/* Verificar que la conexión se realizo
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    } else {
        echo "Conexión exitosa a la base de datos.";
    }
  */  

?>