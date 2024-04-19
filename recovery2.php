<?PHP
$key = "";
$info1 = "";
$aler1 = "";
$aler2 = "";
$aler3 = "";
$serv = "";
// Cookie
if (isset($_COOKIE["secret"])) {
  $key = $_COOKIE["secret"];
  $correo = $_COOKIE["correo"];
} else {
  $serv = "No se pudo conectar con el servidor<br>";
}
//Solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Entradas
  $cookies = $_POST["cookies"];
  $password = $_POST["password"];
  $passwordC = $_POST["passwordC"];
  // Regext
  $regx = preg_match("/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/", $password);
  $regxC = preg_match("/^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/", $passwordC);

  if ($cookies == "" || $password == "" || $passwordC == "") {
    $info1 = "campos vacios por favor verifique <br>";
  } else if ($key == $cookies) {
    if ($regx == 1 &&  $regxC == 1) {
      include("base.php");
      $clave = password_hash("$passwordC", PASSWORD_DEFAULT);
      $sql = ("UPDATE usuarios SET clave ='$clave' WHERE email ='$correo'");
      $result = $conn->query($sql);
      if ($result) {
        $conn->close();
        setcookie("secret", "", time() - 3600, "/");
        setcookie("correo", "", time() - 3600, "/");
        header("Location: login.php");
        exit();
      } else {
        $aler1 = "No se puedo conectar a la base de datos";
      }
    } else {
      $aler2 = "No se pudo cambiar su contrasena";
    }
  } else {
    $aler3 = "Secret Key Invalida por favor verifique<br>";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style\recovery2.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title>Recuperacion de Clave o Nueva Clave</title>
</head>

<body>
  <header>
    <?php echo "$serv <br>" ?>
  </header>
  <main>
    <div class="container">
      <div>
        <p>Introduzca su clave que fue enviada al correo:</p>
      </div>
      <form class="forml" action="recovery2.php" method="post">
        <input type="text" id="cookies" placeholder="Introduzca el secret key " name="cookies" autocomplete="off" />
        <p>Nueva contraseña</p>
        <input type="text" id="password" placeholder="Introduzca la nueva contraseña" name="password" autocomplete="off" />
        <p>Confirme la nueva contraseña</p>
        <input type="text" id="passwordC" placeholder="Introduzca nuevamente la contraseña " name="passwordC" autocomplete="off" />
        <button type="submit">Aceptar</button>
        <?php echo "$info1 <br>" ?>
        <?php echo "$aler1 <br> $aler2 <br> $aler3 <br>" ?>
      </form>
      <div class="toRight">
        <a class="btn" href="login.php"><i class='bx bxs-home'> Login</i></a>
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