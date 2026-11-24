<?php

require_once __DIR__ . "/Utilidades.php";
require_once __DIR__ . "/../models/Auto.php";
require_once __DIR__ . "/../models/Financiacion.php";

class AdministradorController
{
    public function panel(): void
    {
        requiereRol("administrador");
        $csrfLogout = tokenCsrf("csrf_logout");
        require __DIR__ . "/../views/administrador/principal.php";
    }

    public function listarAutos(): void
    {
        requiereRol("administrador");
        $csrfLogout = tokenCsrf("csrf_logout");
        $auto = new Auto();

        // App conserva la colección utilizada por el foreach de la vista
        // y se sincroniza con MariaDB cada vez que se abre el listado.
        App::guardar(App::AUTOS, $auto->listarAutos());
        $csrfToken = tokenCsrf("csrf_autos");
        $mensaje = App::obtener(App::MENSAJE, "");
        App::eliminar(App::MENSAJE);

        require __DIR__ . "/../views/administrador/listarAutos.php";
    }

    public function cargarAuto(): void
    {
        requiereRol("administrador");
        $csrfLogout = tokenCsrf("csrf_logout");
        $auto = new Auto();
        $error = "";
        $csrfToken = tokenCsrf("csrf_autos");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!csrfValido("csrf_autos", $_POST["csrf_token"] ?? null)) {
                $error = "Solicitud no válida.";
            } else {
                $datos = $this->validarDatosAuto($_POST);
                if (is_string($datos)) {
                    $error = $datos;
                } elseif ($auto->cargarAuto(...$datos)) {
                    App::eliminar(App::AUTOS);
                    App::guardar(App::MENSAJE, "El automóvil fue cargado correctamente.");
                    redirigir("autos");
                } else {
                    $error = "No se pudo cargar el automóvil.";
                }
            }
        }

        require __DIR__ . "/../views/administrador/cargarAuto.php";
    }

    public function modificarAuto(): void
    {
        requiereRol("administrador");
        $csrfLogout = tokenCsrf("csrf_logout");
        $id = idPositivo($_GET["id"] ?? null);
        if ($id === null) {
            http_response_code(400);
            exit("El id del auto es inválido.");
        }

        $auto = new Auto();
        $datos = $auto->buscarAutoXID($id);
        if (!$datos) {
            http_response_code(404);
            exit("El auto no existe.");
        }

        $error = "";
        $csrfToken = tokenCsrf("csrf_autos");
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!csrfValido("csrf_autos", $_POST["csrf_token"] ?? null)) {
                $error = "Solicitud no válida.";
            } else {
                $nuevosDatos = $this->validarDatosAuto($_POST);
                if (is_string($nuevosDatos)) {
                    $error = $nuevosDatos;
                } elseif ($auto->modificarAuto($id, ...$nuevosDatos)) {
                    App::eliminar(App::AUTOS);
                    App::guardar(App::MENSAJE, "El automóvil fue modificado correctamente.");
                    redirigir("autos");
                } else {
                    $error = "No se pudo modificar el automóvil.";
                }
            }
        }

        require __DIR__ . "/../views/administrador/modificarAuto.php";
    }

    public function eliminarAuto(): void
    {
        requiereRol("administrador");
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            exit("Método no permitido.");
        }
        if (!csrfValido("csrf_autos", $_POST["csrf_token"] ?? null)) {
            http_response_code(403);
            exit("Solicitud no válida.");
        }

        $id = idPositivo($_POST["id"] ?? null);
        if ($id === null) {
            http_response_code(400);
            exit("El id del auto es inválido.");
        }

        $auto = new Auto();
        if (!$auto->buscarAutoXID($id)) {
            http_response_code(404);
            exit("El auto no existe.");
        }
        if (!$auto->eliminarAuto($id)) {
            http_response_code(500);
            exit("No se pudo eliminar el auto.");
        }

        App::eliminar(App::AUTOS);
        App::guardar(App::MENSAJE, "El automóvil fue eliminado correctamente.");
        redirigir("autos");
    }

    public function configurarFinanciacion(): void
    {
        requiereRol("administrador");
        $csrfLogout = tokenCsrf("csrf_logout");
        $financiacion = new Financiacion();
        $mensaje = "";
        $error = "";
        $csrfToken = tokenCsrf("csrf_financiacion");

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $tasa = $_POST["tasa"] ?? null;
            if (!csrfValido("csrf_financiacion", $_POST["csrf_token"] ?? null)) {
                $error = "Solicitud no válida.";
            } elseif (
                !is_string($tasa) || !is_numeric($tasa) || !is_finite((float) $tasa)
                || (float) $tasa < 0 || (float) $tasa > 100
            ) {
                $error = "Ingresá una tasa entre 0 y 100.";
            } elseif ($financiacion->modificarTasa((float) $tasa)) {
                $mensaje = "Tasa actualizada correctamente.";
            } else {
                $error = "No se pudo actualizar la tasa.";
            }
        }

        $tasaActual = $financiacion->obtenerTasa();
        require __DIR__ . "/../views/administrador/configurarFinanciacion.php";
    }

    /** @return array{string,string,int,string,int,float}|string */
    private function validarDatosAuto(array $entrada): array|string
    {
        $campos = ["marca", "modelo", "anio", "color", "kilometros", "precio"];
        foreach ($campos as $campo) {
            if (!isset($entrada[$campo]) || !is_string($entrada[$campo])) {
                return "Datos inválidos en el campo: " . $campo . ".";
            }
        }

        $marca = trim($entrada["marca"]);
        $modelo = trim($entrada["modelo"]);
        $anio = trim($entrada["anio"]);
        $color = trim($entrada["color"]);
        $kilometros = trim($entrada["kilometros"]);
        $precio = trim($entrada["precio"]);
        $anioActual = (int) date("Y");

        if ($marca === "" || $modelo === "" || $anio === "" || $color === "" || $kilometros === "" || $precio === "") {
            return "Todos los campos son obligatorios.";
        }
        if (filter_var($anio, FILTER_VALIDATE_INT) === false || (int) $anio < 1886 || (int) $anio > $anioActual) {
            return "El año debe estar entre 1886 y " . $anioActual . ".";
        }
        if (filter_var($kilometros, FILTER_VALIDATE_INT) === false || (int) $kilometros < 0) {
            return "Los kilómetros deben ser un entero mayor o igual a cero.";
        }
        if (!is_numeric($precio) || !is_finite((float) $precio) || (float) $precio <= 0) {
            return "El precio debe ser un número mayor a cero.";
        }

        return [$marca, $modelo, (int) $anio, $color, (int) $kilometros, (float) $precio];
    }
}
