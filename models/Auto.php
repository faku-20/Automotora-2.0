<?php
require_once __DIR__ . '/Conexion.php';

class Auto {
    //preguntar si no lleva el atributo
    private $conexion;
    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }
    public function listarAutos(){
        $sql = "SELECT * FROM autos";

        // utiliza la conexión que creamos anteriormente para preparar la consulta anterior.
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        //obtengo todos los resultados con get_result
        $resultado = $consulta->get_result();
        // Recupero todas las filas como un arreglo de arreglos asociativos con fetch_all
        $datos = $resultado->fetch_all(MYSQLI_ASSOC);

        $consulta->close();

        return $datos;
    }

    // siguiendo el patrón MVC, este metodo usa consultas preparadas
    public function obtenerAutoPorId($id) {
        // define la consulta SQL
        $sql = "SELECT * FROM autos WHERE id = ?";
        // se envía la estructura de la culsulta al MySQLi antes de recibir el dato real.
        $consulta = $this->conexion->prepare($sql);
        // vincula el valor real a ser enviado a la consulta preparada
        $consulta->bind_param("i", $id);
        // ejecuta la consulta
        $consulta->execute();
        // recupaera el resultado de la consulta
        $resultado = $consulta->get_result();
        // pasa la tupla del MySQLi a un arreglo de PHP
        $auto = $resultado->fetch_assoc();
        // cierra la consulta de MySQLi
        $consulta->close();
        return $auto;
    }

    public function cargarAuto($marca, $modelo, $anio, $color, $kilometros, $precio){
        $sql = "INSERT INTO autos (marca, modelo, anio, color, kilometros, precio) VALUES (?, ?, ?, ?, ?, ?)";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bind_param("ssisid", $marca, $modelo, $anio, $color, $kilometros, $precio);
        $resultado = $consulta->execute();
        $consulta->close();
        return $resultado;
    }


}

?>