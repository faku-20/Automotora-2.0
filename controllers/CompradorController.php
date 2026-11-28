<?php

require_once __DIR__ . "/Utilidades.php";
require_once __DIR__ . "/../models/Auto.php";
require_once __DIR__ . "/../models/Financiacion.php";

class CompradorController
{
    public function panel(): void
    {
        requiereRol("comprador");
        $csrfLogout = tokenCsrf("csrf_logout");
        require __DIR__ . "/../views/comprador/principal.php";
    }

    public function catalogo(): void
    {
        requiereRol("comprador");
        $csrfLogout = tokenCsrf("csrf_logout");
        $auto = new Auto();
        $autos = $auto->listarAutos();
        require __DIR__ . "/../views/comprador/listarAutos.php";
    }

    public function simularCompra(): void
    {
        requiereRol("comprador");
        $csrfLogout = tokenCsrf("csrf_logout");
        $id = idPositivo($_GET["id"] ?? null);
        if ($id === null) {
            redirigir("catalogo");
        }

        $auto = new Auto();
        $vehiculo = $auto->buscarAutoXID($id);
        if (!$vehiculo) {
            http_response_code(404);
            exit("El automóvil seleccionado no existe.");
        }

        $financiacion = new Financiacion();
        $tasaMensual = $financiacion->obtenerTasa();
        if ($tasaMensual === null) {
            http_response_code(500);
            exit("No se pudo obtener la configuración de financiación.");
        }

        $precio = (float) $vehiculo["precio"];
        $error = "";
        $simulacionRealizada = false;
        $metodoPago = $_POST["metodo_pago"] ?? "tarjeta";
        $entregaIngresada = $_POST["entrega"] ?? "";
        $cuotasIngresadas = $_POST["cuotas"] ?? "";
        $entrega = 0.0;
        $cuotas = 0;
        $saldo = 0.0;
        $valorCuota = 0.0;
        $totalFinanciado = 0.0;
        $totalIntereses = 0.0;
        $totalCompra = 0.0;
        $csrfToken = tokenCsrf("csrf_simulacion");
        // Cada nueva visita o intento de simulación invalida una simulación anterior.
        App::eliminar(App::SIMULACION_CONTACTO);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!csrfValido("csrf_simulacion", $_POST["csrf_token"] ?? null)) {
                $error = "Solicitud no válida.";
            } elseif (!is_string($metodoPago) || !in_array($metodoPago, ["tarjeta", "prestamo"], true)) {
                $error = "Seleccioná un método de pago válido.";
            } elseif (
                !is_scalar($entregaIngresada) || !is_numeric($entregaIngresada)
                || !is_finite((float) $entregaIngresada) || (float) $entregaIngresada < 0
                || (float) $entregaIngresada > $precio
            ) {
                $error = "Ingresá una entrega inicial válida.";
            } elseif (
                !is_scalar($cuotasIngresadas) || !ctype_digit((string) $cuotasIngresadas)
                || (int) $cuotasIngresadas < 1 || (int) $cuotasIngresadas > ($metodoPago === "tarjeta" ? 12 : 100)
            ) {
                $error = "La cantidad de cuotas no es válida para el método seleccionado.";
            } else {
                $entrega = (float) $entregaIngresada;
                $cuotas = (int) $cuotasIngresadas;
                $saldo = $precio - $entrega;

                if ($metodoPago === "tarjeta") {
                    // Tarjeta: hasta 12 cuotas sin intereses.
                    $valorCuota = $saldo / $cuotas;
                } else {
                    // Préstamo: sistema de amortización francés.
                    $tasa = $tasaMensual / 100;
                    $valorCuota = $tasa == 0
                        ? $saldo / $cuotas
                        : ($saldo * $tasa) / (1 - pow(1 + $tasa, -$cuotas));
                }

                $totalFinanciado = $valorCuota * $cuotas;
                $totalIntereses = max(0, $totalFinanciado - $saldo);
                $totalCompra = $entrega + $totalFinanciado;
                $simulacionRealizada = true;

                // Guardamos en sesión los datos de esta simulación para usarlos
                // al abrir el formulario de contacto, sin confiar en datos del navegador.
                App::guardar(App::SIMULACION_CONTACTO, [
                    "auto" => [
                        "id" => (int) $vehiculo["id"],
                        "marca" => (string) $vehiculo["marca"],
                        "modelo" => (string) $vehiculo["modelo"],
                        "anio" => (int) $vehiculo["anio"],
                        "precio" => $precio,
                    ],
                    "metodo_pago" => $metodoPago,
                    "entrega" => $entrega,
                    "cuotas" => $cuotas,
                    "saldo" => $saldo,
                    "valor_cuota" => $valorCuota,
                    "total_intereses" => $totalIntereses,
                    "total_compra" => $totalCompra,
                ]);
            }
        }

        require __DIR__ . "/../views/comprador/simularCompra.php";
    }
}
