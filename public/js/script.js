document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("form[data-confirm-delete]").forEach((formulario) => {
        formulario.addEventListener("submit", (evento) => {
            if (!window.confirm("¿Seguro que querés eliminar este automóvil?")) {
                evento.preventDefault();
            }
        });
    });

    const metodoPago = document.getElementById("metodo_pago");
    const cuotasInput = document.getElementById("cuotas");
    const informacion = document.getElementById("informacion_financiacion");

    if (!metodoPago || !cuotasInput || !informacion) {
        return;
    }

    const actualizarMetodoPago = () => {
        const esTarjeta = metodoPago.value === "tarjeta";
        const maximo = esTarjeta ? 12 : 100;
        cuotasInput.max = String(maximo);

        if (cuotasInput.value !== "" && Number(cuotasInput.value) > maximo) {
            cuotasInput.value = String(maximo);
        }

        informacion.textContent = esTarjeta
            ? "Hasta 12 cuotas sin recargo."
            : "Hasta 100 cuotas con interés mensual configurable.";
    };

    metodoPago.addEventListener("change", actualizarMetodoPago);
    actualizarMetodoPago();
});
