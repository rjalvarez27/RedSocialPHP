<?php

use PHPMailer\PHPMailer\PHPMailer;

$add = "";
$info0 = "";
$info1 = "";
$info2 = "";
$info3 = "";
$alert1 = "";
$clave = "";

// comprobacion id del usuario
session_start();
if (!empty($_SESSION["id"])) {
  header("Location: dashboard.php");
  exit();
}

// Registro 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //base de datos
  include("base.php");
  // variables 
  $nombre = $_POST['nombre'] ?? "";
  $email = $_POST["email"] ?? "";
  $contrasena = $_POST["contrasena"] ?? "";
  // regext
  $regxN = preg_match("/^[A-ZÑa-zñáéíóúÁÉÍÓÚ'° ]+$/", $nombre);
  $regxE = preg_match("/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/", $email);
  $regxC = preg_match("/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/", $contrasena);
  // encriptado
  $clave = password_hash("$contrasena", PASSWORD_DEFAULT);
  //  comprobacion de correo 
  if ($comprobanteCorreo = ("SELECT email FROM usuarios WHERE email ='$email'"));
  $resultadoCorreo = $conn->query($comprobanteCorreo);
  $numeroCorreo = $resultadoCorreo->num_rows;

  if ($nombre == "") {
    $info1 = "Nombre se encuentra vacio";
  }
  if ($email == "") {
    $info2 = "Email se encuentra vacio";
  }
  if ($contrasena == "") {
    $info3 = "Clave se encuentra vacia";
  } else if ($numeroCorreo > 0) {
    $info0  = "Este Correo ya esta registrado por favor verifique";
  } else if ($regxN == 0 || $regxC == 0 || $regxE == 0) {
    $alert1 = "Datos introducidos invalidos por favor verifique";
  } else {
    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'rjalvarezprueba@gmail.com';
    $mail->Password = 'stllyyynwbuafxlv';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('rjalvarezprueba@gmail.com');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Bienvenido!';
    $mail->Body = "Hola $nombre. Gracias por registrarte";
    $mail->send();

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, clave) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $clave);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    if ($nombre && $email && $clave) {
      header("Location: login.php");
      exit();
    } else {
      $add = 'Error al registrar Usuario';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style\register.css" />
   <title>Register</title>
</head>

<body>
  <header>
    <img src="IMG/Logo.png" alt="logo" class="img">
  </header>
  <main>
    <div class="container">
      <div class="form">
        <form action="register.php" method="post" autocomplete="off">
          <h1 class="title">Crear usuario</h1>
          <span>Proporciona la información siguiente!</span>
          <input type="text" id="nombre" placeholder="Nombre de usuario" name="nombre" />
          <input type="email" id="email" placeholder="Email" name="email" />
          <input type="password" id="contrasena" placeholder="Clave" name="contrasena" />
          <button type="submit">Crear</button>
          <?php echo "$add <br>
                     $info0 <br>
                      $info1 <br>
                      $info2 <br>
                      $info3 <br>
                      $alert1 <br>"; ?>
        </form>
      </div>
      <div class="overlay">
        <h1 class="title">Ya tienes cuenta!</h1>
        <p>Inicia sesión para empezar a montar tus Fotos Favoritas </p>
        <button id="signIn" type="button" onclick="location='login.php'">Iniciar</button>
      </div>
    </div>
  </main>
  <footer>
    <div class="">
      Español(Venezuela) 2024 Copyright
      <a href="">© Rjalvarez C.A </a>
    </div>
  </footer>
</body>

</html>