<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Automotora Pérez</title>
</head>
<body>
    <h1>Automotora Pérez</h1>
     <?php
      echo "Inicio de sesion" . "<br>";
    ?>

    <form action="" method="post">
  <label for="Usuario">Usuario:</label>
  <input type="text" id="Usuario" name="usuario">

  <label for="contraseña">Contraseña:</label>
<input type="password" id="contraseña" name="contraseña" minlength="8">


  
  <button type="submit">Enviar</button>
</form>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  $usuario = $_POST["usuario"];
  $contraseña = $_POST["contraseña"];
  if (!empty($usuario) && !empty($contraseña)){ 
    echo "Bienvenido: ". $usuario;
  }else{
    echo "Error: debes ingresar todos los datos";
  }
  
}
?>
</body>
</html>