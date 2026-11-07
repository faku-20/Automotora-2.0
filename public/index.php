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

    <form action="probarUsuario.php" method="post">
      <label for="Usuario">Usuario:</label>
      <input type="text" id="Usuario" name="usuario" required>

      <label for="contraseña">Contraseña:</label>
      <input type="password" id="clave" name="clave" minlength="8" required>
  
      <button type="submit">Enviar</button>
    </form>
</body>
</html>