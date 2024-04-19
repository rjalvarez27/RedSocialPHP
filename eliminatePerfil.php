<?php

$inf = "";
$inf2 = "";

session_start();
if (!empty($_SESSION["id"])) {
    $id = $_SESSION["id"];
    include("base.php");
    $stmt = $conn->prepare("SELECT id_user, email, clave  FROM usuarios WHERE id_user = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($id_user, $email, $clave);
    $stmt->fetch();
    $idU = $id_user;
    $correoU = $email;
    $claveU = $clave;
} else {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $clave = $_POST["clave"];
   
    if ($email == $correoU && password_verify($clave, $claveU) == 1) {

        include("base.php");
        $stmt = $conn->prepare('DELETE FROM comentarios WHERE id_user = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare('DELETE FROM publicaciones WHERE id_user = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare('DELETE FROM usuarios WHERE id_user = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        
        session_unset();
        session_destroy();
        header("Location: login.php");


    } else {
        $inf2 = "La clave o el correo son invalidas";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style\eliminatePerfil.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Eliminar Cuenta</title>
</head>

<body>
    <header>
        <h1>Eliminar Cuenta</h1>
    </header>
    <main>
        <div class="container">
            <form action="eliminatePerfil.php" method="post" autocomplete="off">
                <span>Proporciona tus datos:</span>
                <input type="email" id="email" name="email" placeholder="Email" />
                <input type="password" id="clave" name="clave" placeholder="Clave" />
                <button class="btn" type="submit">Aceptar</button>
            </form>
            <div class="toRight">
            <button class="btn1" type="button" onclick="location='dashboard.php'">Pagina Principal</button>
            </div>
        </div>
    </main>
</body>

</html>