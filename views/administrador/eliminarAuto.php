<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//protegemos de q si no hay una sesion activa no se pueda entrar
if (($_SESSION["tipo_usuario"] ?? null) !== "administrador") {
    header("Location: ../../public/index.php");
    exit;
}

/*Si recibimos POST, el programa continúa.
Si alguien intenta entrar directamente desde el navegador mediante GET, se detiene.
*/
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Método no permitido");
}

$tokenSesion = $_SESSION["csrf_token"] ?? "";
$tokenRecibido = $_POST["csrf_token"] ?? "";

if (
    !is_string($tokenRecibido) ||
    !is_string($tokenSesion) ||
    $tokenSesion === "" ||
    !hash_equals($tokenSesion, $tokenRecibido)
) {
    exit("Token CSRF inválido");
}

//comprobamos q el id del auto sea un numero entero y positivo
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    exit("El id del auto es invalido");
}

require_once __DIR__ .  '/../../models/Auto.php';
$auto = new Auto();
$autoEncontrado = $auto->buscarAutoXID($id);
if (!$autoEncontrado) {
    exit("El auto no existe");
}
$resultado = $auto->eliminarAuto($id);

//unset elimina la copia del arreglo almacenada en la sesión.
if ($resultado) {
    unset($_SESSION["autos"]);
    header("Location: listarAutos.php");
    exit;
} else {
    exit("Error al eliminar el auto");
}
