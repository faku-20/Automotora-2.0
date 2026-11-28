<?php

require_once __DIR__ . "/Utilidades.php";
require_once __DIR__ . "/../models/Contacto.php";

class ContactoController
{
    public function formulario(): void
    {
        requiereRol("comprador");

        $contactoEnviado = App::obtener("contacto_enviado", false) === true;
        App::eliminar("contacto_enviado");

        $simulacion = App::obtener(App::SIMULACION_CONTACTO);
        if (!$contactoEnviado && !is_array($simulacion)) {
            redirigir("catalogo");
        }

        $csrfToken = $contactoEnviado ? "" : tokenCsrf("csrf_contacto");
        $error = "";
        $valores = [
            "nombre" => (string) App::obtener(App::USUARIO_NOMBRE, ""),
            "email" => "",
            "telefono" => "",
            "mensaje" => "",
        ];

        require __DIR__ . "/../views/comprador/contactarVendedor.php";
    }

    public function enviar(): void
    {
        requiereRol("comprador");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            exit("Método no permitido.");
        }

        $simulacion = App::obtener(App::SIMULACION_CONTACTO);
        if (!is_array($simulacion)) {
            redirigir("catalogo");
        }

        $csrfToken = tokenCsrf("csrf_contacto");
        $valores = [
            "nombre" => trim((string) ($_POST["nombre"] ?? "")),
            "email" => trim((string) ($_POST["email"] ?? "")),
            "telefono" => trim((string) ($_POST["telefono"] ?? "")),
            "mensaje" => trim((string) ($_POST["mensaje"] ?? "")),
        ];
        $error = "";

        if (!csrfValido("csrf_contacto", $_POST["csrf_token"] ?? null)) {
            $error = "La solicitud venció o no es válida. Volvé a intentarlo.";
        } elseif (
            $valores["nombre"] === "" || mb_strlen($valores["nombre"]) > 120
            || $valores["email"] === "" || !filter_var($valores["email"], FILTER_VALIDATE_EMAIL)
            || strlen($valores["email"]) > 254
            || ($valores["telefono"] !== "" && mb_strlen($valores["telefono"]) > 30)
            || $valores["mensaje"] === "" || mb_strlen($valores["mensaje"]) < 5
            || mb_strlen($valores["mensaje"]) > 2000
        ) {
            $error = "Revisá los campos. Nombre, correo y mensaje son obligatorios; el mensaje debe tener entre 5 y 2000 caracteres.";
        } else {
            try {
                $contacto = new Contacto();
                $contacto->enviarSolicitud($valores, $simulacion);

                // PRG: evita reenviar el correo al actualizar la página.
                App::eliminar(App::SIMULACION_CONTACTO, "csrf_contacto");
                App::guardar("contacto_enviado", true);
                redirigir("contacto");
            } catch (Throwable $e) {
                // El detalle técnico queda en el log del servidor, nunca en pantalla.
                error_log("Error al enviar solicitud de contacto: " . $e->getMessage());
                $error = "No se pudo enviar el mensaje. Verificá la configuración de Mailtrap e intentá nuevamente.";
            }
        }

        require __DIR__ . "/../views/comprador/contactarVendedor.php";
    }
}
