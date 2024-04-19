<?php

$info1 = "";
$info2 = "";
$info3 = "";

// Comprobacion id del usuario
session_start();
if (!empty($_SESSION["id"])) {
  header("Location: dashboard.php");
  exit();
}
//Solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // base de datos 
  include("base.php");
  // obtener los datos del formulario 
  $correo = $_POST["correo"];
  $password = $_POST["password"];
  // Inyeccion SQL
  $stmt = $conn->prepare("SELECT id_user, email, clave FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $correo);
  $stmt->execute();
  $stmt->bind_result($id_user, $email, $clave); // valor que contienen en la base de datos 
  $stmt->fetch();

  if ($correo == "") {
    $info1 = "Correo se encuentra vacio";
  }
  if ($password == "") {
    $info2 = "Clave se encuentra vacio";
  } else if (password_verify($password, $clave) == 1 && $correo == $email ) {
    session_start();
    $_SESSION["id"] = $id_user;
    $conn->close();
    header("Location: dashboard.php");
    exit();
  } else {
     $info3 = "contrasena invalida";
  }
}
?>;
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style\login.css">
  <title>Login</title>
</head>

<body>
  <header>
    <img src="IMG/Logo.png" alt="logo" class="img">
  </header>
  <main>
    <div class="container"  action="login.php" method="post">
      <div class="form-container">
        <form id="signin-form" action="login.php" method="post">
          <h1>Inicio de sesión</h1>
          <span>Proporciona tus datos</span>
          <input type="email" id="correo" name="correo" placeholder="Email" />
          <input type="password" id="password" name="password" placeholder="Clave" />
          <a href="recovery.php">Olvidaste tu clave?</a>
          <button type="submit">Iniciar</button>
          <?php echo "$info1 <br>.
                    $info2 <br>.
                    $info3 <br> " ?>
        </form>
      </div>
      <div class="overlay-container">
        <h1>Nuevo usuario?</h1>
        <p>Registrate para empezar a disfrutar de la Mejor Red Social</p>
        <button type="button" onclick="location='register.php'">Registro</button>
      </div>
    </div>
  </main>
  <footer>
    <div>
      Español(España) 2024 Copyright:
      <a href="">© Rjalvarez C.A </a>
    </div>
  </footer>
</body>

</html>