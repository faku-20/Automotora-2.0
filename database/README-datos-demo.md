# Datos de demostración

El archivo `automotora.sql` contiene un esquema completo y datos ficticios para la defensa.

Credenciales compartidas:

- Administradores `admin01` a `admin10`: `AdminDemo2026!`
- Compradores `comprador01` a `comprador10`: `CompraDemo2026!`

Para regenerar los datos en la base local configurada en `.env`, ejecutar desde la raíz del proyecto:

```bash
php scripts/reinicializarDatosDemo.php
```

El script se detiene si la conexión no apunta a `localhost`, `127.0.0.1` o `::1`, o si el nombre de la base no es `automotora`.
