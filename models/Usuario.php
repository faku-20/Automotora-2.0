
<?php

require_once __DIR__ . '/Conexion.php';

class Usuario {

    private $conexion;

    // Constructor
    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    // Buscar un usuario por su nombre de usuario
    public function buscarUsuario($usuario) {

        $sql = "SELECT * FROM usuarios WHERE usuario = ?";

        $consulta = $this->conexion->prepare($sql);
        $consulta->bind_param("s", $usuario);
        $consulta->execute();

        $resultado = $consulta->get_result();
        $datos = $resultado->fetch_assoc();

        $consulta->close();

        return $datos;
    }

    // Comprobar usuario y contraseña
    public function iniciarSesion($usuario, $clave) {

        $datos = $this->buscarUsuario($usuario);

        if ($datos && password_verify($clave, $datos["password"])) {

            unset($datos["password"]);

            return $datos;
        }

        return false;
    }

    // Modificar los datos del perfil
    public function modificarPerfil($id, $nombre, $apellido, $nuevaClave = "") {

        if ($nuevaClave != "") {

            $password = password_hash($nuevaClave, PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios
                    SET nombre = ?, apellido = ?, password = ?
                    WHERE id = ?";

            $consulta = $this->conexion->prepare($sql);
            $consulta->bind_param("sssi", $nombre, $apellido, $password, $id);

        } else {

            $sql = "UPDATE usuarios
                    SET nombre = ?, apellido = ?
                    WHERE id = ?";

            $consulta = $this->conexion->prepare($sql);
            $consulta->bind_param("ssi", $nombre, $apellido, $id);
        }

        $resultado = $consulta->execute();
        $consulta->close();

        return $resultado;
    }
}
