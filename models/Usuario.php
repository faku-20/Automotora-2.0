
<?php

require_once __DIR__ . '/Conexion.php';

class Usuario {

    private $conexion;

    // Constructor
    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
    }

    // Busca un usuario por su nombre de usuario en la base de datos si esta registrado

    public function buscarUsuario($usuario) {
        
    //preparamos la consulta a la bd, y la guarda en la variable $sql
        $sql = "SELECT * FROM usuarios WHERE usuario = ?";

        // utiliza la conexión que creamos anteriormente para preparar la consulta anterior.
        $consulta = $this->conexion->prepare($sql);
        //utilizamos s como parametro ya q $usuario contiene el tipo de usuario q es un string
        $consulta->bind_param("s", $usuario);
        $consulta->execute();

        //obtengo todos los resultados con get_result, y luego con fetch_assoc los recupero en un array asociativo

        $resultado = $consulta->get_result();
        $datos = $resultado->fetch_assoc();

        $consulta->close();

        return $datos;
    }

    // Comprobar si el usuario y contraseña existen en la bd
    public function iniciarSesion($usuario, $clave) {

        $datos = $this->buscarUsuario($usuario);

        if ($datos && password_verify($clave, $datos["password"])) {

            unset($datos["password"]);

            return $datos;
        }

        return false;
    }

    // Modifica los datos del perfil del usuario
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
