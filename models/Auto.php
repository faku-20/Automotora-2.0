<?php
require_once __DIR__ . '/Conexion.php';

class Auto
{
    //preguntar si no lleva el atributo
    private $conexion;
    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }
    public function listarAutos()
    {
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

    public function cargarAuto($marca, $modelo, $anio, $color, $kilometros, $precio)
    {
        $sql = "INSERT INTO autos (marca, modelo, anio, color, kilometros, precio) VALUES (?, ?, ?, ?, ?, ?)";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bind_param("ssisid", $marca, $modelo, $anio, $color, $kilometros, $precio);
        $resultado = $consulta->execute();
        $consulta->close();
        return $resultado;
    }

    public function buscarAutoXID($id)
    {
        $sql = "SELECT * FROM autos WHERE id = ?";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $datos = $resultado->fetch_assoc();
        $consulta->close();
        return $datos;
    }

    public function modificarAuto($id, $marca, $modelo, $anio, $color, $kilometros, $precio)
    {
        $sql = "UPDATE autos
        SET marca = ?, modelo = ?, anio = ?, color = ?, kilometros = ?, precio = ? WHERE id = ?";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bind_param("ssisidi", $marca, $modelo, $anio, $color, $kilometros, $precio, $id);
        $resultado = $consulta->execute();
        $consulta->close();
        return $resultado;
    }
}
