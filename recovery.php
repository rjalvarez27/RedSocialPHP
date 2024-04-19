<?PHP

use PHPMailer\PHPMailer\PHPMailer;
//variables
$security = mt_rand(1000000, 2000000);
$correo = "";
$nombre = "";
$info1 = "";
$info2 = "";
//Solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //Entrada
  include("base.php");
  $email = $_POST["email"];
  //Comprobacion del email
  $comprobante = ("SELECT nombre, email FROM usuarios WHERE email ='$email'");
  $resultadoCorreo = $conn->query($comprobante);
  if ($resultadoCorreo->num_rows > 0) {
    while ($row = $resultadoCorreo->fetch_assoc()) {
      $correo = $row["email"];
      $nombre = $row["nombre"];
    }
  }
  //Datos
  if ($email == "") {
    $info1 = "Campo del correo vacio";
  } else if ($email != $correo) {
    $info2 = "Correo electronico no registrado en nuestra base de datos ";
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
    $mail->Subject = 'Clave de Recuperacion';
    $mail->Body = "Hola $nombre, tu clave de recuperacion es: $security, <br> Si usted no a pedido cambio de Clave por favor contacte al administrador lo mas rapido posible";
    $mail->send();
    $conn->close();
    setcookie("secret", "$security", time() + 300, "/");
    setcookie("correo", "$correo", time() + 300, "/");
    header("Location: recovery2.php");
    exit();
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style\recovery.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title>Recuperacion de Clave o nueva clave</title>
</head>
<body>
  <main>
    <div class="container">
      <h1>Recuperacion de Clave de Acceso o Nueva clave</h1>
      <p>Introduzca su Correo electronico:</p>
      <div class="forml">  
        <form  action="recovery.php" method="post">
          <input type="email" id="email" placeholder="Email" name="email" autocomplete="off" />
          <p>Tiene 5 min antes de que el codigo expire</p>
          <button type="submit">Aceptar </button>
          
        </form>
        <?php echo "$info1 <br>
                  $info2 <br>"; ?>
      </div>
      <div class= "toRight" >
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