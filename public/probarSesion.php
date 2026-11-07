<?php
//inicio o recupero la sesion actual
session_start();

//comprueba si existe un usuario guardado en la sesion
if (isset($_SESSION["usuario"])) {
    //muestra el usuario autenticado. htmlspecialchars() evita que ciertos caracteres HTML se interpreten como código HTML.
    echo "Bienvenido: " . htmlspecialchars($_SESSION["usuario"]);
} else {
    echo "No hay ninguna sesión iniciada";
}
?>