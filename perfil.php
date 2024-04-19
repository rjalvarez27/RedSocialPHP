<?php

$info = "";
$alert1 = "";

session_start();
if (!empty($_SESSION["id"])) {
    $id = $_SESSION["id"];
    include("base.php");
    $stmt = $conn->prepare("SELECT id_user, nombre, email, clave , rol FROM usuarios WHERE id_user = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($id_user, $nombre, $email, $clave, $rol);
    $stmt->fetch();
    $idS = $id_user;
    $nombreU = $nombre;
    $correoU = $email;
    $claveU = $clave;
    $rolU = $rol;
    if ($rol == "admin") {
        $conn->close();
        header("Location: admin.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("base.php");
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $clave = $_POST["clave"];
    
    if ($nombre != "") {
        $regxN = preg_match("/^[A-ZÑa-zñáéíóúÁÉÍÓÚ'° ]+$/", $nombre);
        if ($regxN == 1) {
            $sqlN = ("UPDATE usuarios SET nombre ='$nombre' WHERE id_user ='$id'");
            $resultN = $conn->query($sqlN);
            if ($resultN) {
                echo "Nombre cambiado con exito";
                header("Location: perfil.php");
            } else {
                echo "Error de conexion con el servidor";
            }
        } else {
            echo "Nombre introducido no es correcto";
        }
    }
    if ($email != "") {
        $regxE = preg_match("/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/", $email);
        if ($regxE == 1) {
            $sqlE = ("UPDATE usuarios SET email ='$email' WHERE id_user ='$id'");
            $resultE = $conn->query($sqlE);
            if ($resultE) {
                echo "Correo cambiado con exito";
                header("Location: perfil.php");
            } else {
                echo "Error de conexion con el servidor";
            }
        } else {
            echo "Correo introducido no es correcto";
        }
    }
    if ($clave != "" && password_verify($clave, $claveU) == 1) {
        include("base.php");
        $sqlC = ("UPDATE usuarios SET rol ='admin' WHERE id_user ='$id'");
        $resultC = $conn->query($sqlC);
        if ($resultC) {
            $conn->close();
            header("Location: admin.php");
        } else {
            $alert1 = "Clave Incorrecta";
        }
    }else {
        $info = "Uno o todos los campos estan vacios";
    }


}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style\perfil.css">
    <title>Perfil de Usuario</title>
</head>
<header>
    <h1>Perfil de <?php echo $nombreU ?></h1>
</header>
<main>
    <div class="container">
        <h2>Datos del Usuario</h2>
        <div class="forml">
            <p>Nombre:<?php echo $nombreU ?></p>
            <p>Correo:<?php echo $correoU ?></p>
            <p>Rol:<?php echo $rolU ?></p>
        </div>
        <div class="forml2">
            <h2 class="title">Cambiar Datos del perfil</h1>
                <form action="perfil.php" method="post" autocomplete="off">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" placeholder="Nombre de usuario" name="nombre" />
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="Email" name="email" />

                    <p>Si es administrador ingrese la clave por favor</p>
                    <input type="password" id="clave" placeholder="Clave" name="clave" />
                    <button class="btn" type="submit">Cambiar Datos </button>
                </form>
                <?php echo $alert1; ?>
                <?php echo $info; ?>
        </div>
        <div class="forml3">
            <p>Si quiere Cambiar su Clave de usuario<br></p>
            <button class="btn" onclick="location='recovery.php'">Cambiar Clave</button>
            <p>Si quiere Eliminar su Cuenta<br></p>
            <button class="btn" type="button" onclick="location='eliminatePerfil.php'"> Eliminar su Cuenta</button>
        </div>
        <div class="toRight">
            <a class="btn" href="dashboard.php"><i class='bx bxs-home'> Pagina Principal</i></a>
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
