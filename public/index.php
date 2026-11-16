<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$error = $_SESSION["error_login"] ?? "";
unset($_SESSION["error_login"]);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Automotora Pérez</title>
</head>

<body>
  <h1>Automotora Pérez</h1>
  <h2>Inicio de sesión</h2>

  <?php if ($error !== ""): ?>
    <p>
      <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
    </p>
  <?php endif; ?>

  <form action="probarUsuario.php" method="POST">
    <label for="usuario">Usuario:</label>
    <input
      type="text"
      id="usuario"
      name="usuario"
      required>

    <label for="clave">Contraseña:</label>
    <input
      type="password"
      id="clave"
      name="clave"
      required>

    <button type="submit">Iniciar sesión</button>
  </form>
</body>

</html>