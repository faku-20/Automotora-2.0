<?php

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Modelo responsable del envío de solicitudes de contacto por correo.
 * Mailtrap recibe estos mensajes en un inbox de pruebas; no contacta a un vendedor real.
 */
class Contacto
{
    public function enviarSolicitud(array $datos, array $simulacion): void
    {
        $autoload = __DIR__ . "/../vendor/autoload.php";
        if (!is_file($autoload)) {
            throw new RuntimeException(
                "No se encontraron las dependencias. Ejecutá composer install en la raíz del proyecto."
            );
        }

        require_once $autoload;

        $config = parse_ini_file(__DIR__ . "/../.env");
        if ($config === false) {
            throw new RuntimeException("No se pudo leer el archivo .env.");
        }

        $requeridas = [
            "MAIL_HOST",
            "MAIL_PORT",
            "MAIL_USERNAME",
            "MAIL_PASSWORD",
            "MAIL_FROM_ADDRESS",
            "MAIL_FROM_NAME",
            "MAIL_TO_ADDRESS",
        ];

        foreach ($requeridas as $clave) {
            if (!isset($config[$clave]) || trim((string) $config[$clave]) === "") {
                throw new RuntimeException("Falta configurar " . $clave . " en el archivo .env.");
            }
        }

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = (string) $config["MAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Username = (string) $config["MAIL_USERNAME"];
        $mail->Password = (string) $config["MAIL_PASSWORD"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int) $config["MAIL_PORT"];
        $mail->CharSet = "UTF-8";

        $mail->setFrom(
            (string) $config["MAIL_FROM_ADDRESS"],
            (string) $config["MAIL_FROM_NAME"]
        );
        $mail->addAddress((string) $config["MAIL_TO_ADDRESS"]);
        $mail->addReplyTo($datos["email"], $datos["nombre"]);

        $auto = $simulacion["auto"];
        $metodoPago = $simulacion["metodo_pago"] === "tarjeta"
            ? "Tarjeta de crédito"
            : "Préstamo de la automotora";

        $escapar = static fn(string $valor): string =>
            htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");

        $mail->isHTML(true);
        $mail->Subject = "Solicitud de compra - " . $auto["marca"] . " " . $auto["modelo"];
        $mail->Body = "
            <h2>Nueva solicitud de contacto</h2>
            <p><strong>Comprador:</strong> " . $escapar($datos["nombre"]) . "</p>
            <p><strong>Correo:</strong> " . $escapar($datos["email"]) . "</p>
            <p><strong>Teléfono:</strong> " . $escapar($datos["telefono"] !== "" ? $datos["telefono"] : "No indicado") . "</p>
            <p><strong>Mensaje:</strong><br>" . nl2br($escapar($datos["mensaje"])) . "</p>
            <hr>
            <h3>Vehículo de interés</h3>
            <p>" . $escapar($auto["marca"] . " " . $auto["modelo"] . " (" . $auto["anio"] . ")") . "</p>
            <p><strong>Precio:</strong> $" . number_format((float) $auto["precio"], 2, ",", ".") . "</p>
            <h3>Resumen de simulación</h3>
            <p><strong>Método de pago:</strong> " . $escapar($metodoPago) . "</p>
            <p><strong>Entrega inicial:</strong> $" . number_format((float) $simulacion["entrega"], 2, ",", ".") . "</p>
            <p><strong>Saldo financiado:</strong> $" . number_format((float) $simulacion["saldo"], 2, ",", ".") . "</p>
            <p><strong>Cuotas:</strong> " . (int) $simulacion["cuotas"] . " de $" . number_format((float) $simulacion["valor_cuota"], 2, ",", ".") . "</p>
            <p><strong>Total estimado:</strong> $" . number_format((float) $simulacion["total_compra"], 2, ",", ".") . "</p>
            <p><em>Mensaje generado desde el entorno de pruebas de Automotora.</em></p>
        ";

        $mail->AltBody =
            "Nueva solicitud de contacto\n"
            . "Comprador: " . $datos["nombre"] . "\n"
            . "Correo: " . $datos["email"] . "\n"
            . "Teléfono: " . ($datos["telefono"] !== "" ? $datos["telefono"] : "No indicado") . "\n"
            . "Mensaje: " . $datos["mensaje"] . "\n"
            . "Vehículo: " . $auto["marca"] . " " . $auto["modelo"] . " (" . $auto["anio"] . ")\n"
            . "Método de pago: " . $metodoPago . "\n"
            . "Entrega: $" . number_format((float) $simulacion["entrega"], 2, ",", ".") . "\n"
            . "Cuotas: " . (int) $simulacion["cuotas"] . " de $" . number_format((float) $simulacion["valor_cuota"], 2, ",", ".") . "\n";

        $mail->send();
    }
}
