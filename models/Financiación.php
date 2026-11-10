<?php

require_once __DIR__ . '/Conexion.php';

class Financiacion
{
    private $conexion;

    public function __construct()
    {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    // Obtener la tasa mensual configurada
    public function obtenerTasa()
    {
        $sql = "SELECT tasa_interes_mensual
                FROM configuracion_financiacion
                WHERE id = 1";

        $resultado = $this->conexion->query($sql);

        if ($resultado && $fila = $resultado->fetch_assoc()) {
            return (float) $fila["tasa_interes_mensual"];
        }

        return null;
    }

    // Modificar la tasa mensual
    public function modificarTasa($tasa)
    {
        if (!is_numeric($tasa) || $tasa < 0 || $tasa > 100) {
            return false;
        }

        $sql = "UPDATE configuracion_financiacion
                SET tasa_interes_mensual = ?
                WHERE id = 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("d", $tasa);

        return $stmt->execute();
    }
}