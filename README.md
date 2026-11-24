# Automotora-2.0

Aplicación de gestión de automotora desarrollada con PHP puro, MySQLi, HTML y JavaScript. Usa sesiones, roles, consultas preparadas, `password_hash()`, `password_verify()` y tokens CSRF.

## Ejecución

Mantener el archivo `.env` existente con la configuración local de MariaDB y ejecutar desde la raíz del proyecto:

```bash
php -S localhost:3000 -t public
```

Abrir <http://localhost:3000>. No se debe acceder directamente a archivos de `controllers`, `models` o `views`.

## Estructura

```
public/       Único directorio web: index.php, css/ y js/
controllers/  Router y casos de uso (usuarios, administrador, comprador y perfil)
models/       Acceso a MariaDB mediante MySQLi
views/        HTML de cada pantalla
database/     Script de estructura/datos de referencia
scripts/      Utilidades de desarrollo
app/          Estado compartido de la aplicación (sesión y sus claves)
```

`public/index.php` es el único punto de entrada HTTP. El parámetro `ruta` se valida contra una lista cerrada; las escrituras sólo aceptan POST.

## Rutas

| Ruta | Método | Uso |
| --- | --- | --- |
| `index.php` / `?ruta=inicio` | GET | Inicio de sesión |
| `?ruta=login` / `?ruta=logout` | POST | Autenticación y cierre de sesión |
| `?ruta=admin` / `?ruta=comprador` | GET | Panel según rol |
| `?ruta=autos` | GET | Listado de administración |
| `?ruta=auto-cargar` | GET, POST | Alta de automóviles |
| `?ruta=auto-modificar&id=N` | GET, POST | Edición de automóvil |
| `?ruta=auto-eliminar` | POST | Baja de automóvil |
| `?ruta=financiacion` | GET, POST | Configuración de tasa |
| `?ruta=catalogo` | GET | Catálogo del comprador |
| `?ruta=simular-compra&id=N` | GET, POST | Simulación de financiación |
| `?ruta=perfil` | GET, POST | Perfil y cambio opcional de contraseña |

El listado de administración sincroniza `$_SESSION["autos"]` desde MariaDB y la vista lo recorre con `foreach`.
