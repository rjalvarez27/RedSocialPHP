<?php

$info1 = "";
$info2 = "";
$aler1 = ""; 

session_start();
if (!empty($_SESSION["id"])) {
    $id = $_SESSION["id"];
} else {
    header("Location: login.php");
    exit();
}
if (isset($_POST["btn"])) {
    $nombre = $_POST["nombre"];
    $publicacion = $_FILES['publicacion'];
    $ruta = "IMG/" . $publicacion['name'];

    if ($nombre == "" || $publicacion == "") {
        $info1 =  "Datos vacios";
    } else if (!((strpos($ruta, 'gif') || strpos($ruta, 'png') || strpos($ruta, 'jpg') || strpos($ruta, 'webp')))) {
        $info2 = "Solo se permiten formatos .gif, .png, .jpg, .webp <br>";
    } else {
        include("base.php");
        $stmt = $conn->prepare("INSERT INTO publicaciones (id_user, name , publicacion) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id, $nombre, $ruta);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        if ($id && $nombre && $ruta) {
            move_uploaded_file($publicacion['tmp_name'], $ruta);
            include("base.php");
            $tem = ("SELECT id_publicacion FROM publicaciones WHERE id_user = $id ");
            $result = $conn->query($tem);
            while ($row = $result->fetch_assoc()) {
                $id_temp = $row["id_publicacion"];
            }
            $_SESSION["id_publicacion"] = $id_temp;
            $conn->close();
            header("Location: addComentario.php");
            exit();
        } else {
            $aler1 = "Ocurrio un error en el servidor <br>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style\addPublicacion.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Agregar Pulicaciones</title>
</head>

<body>
    <header>
        <div class="part1">
            <img src="IMG\Logo.png" alt="logo" class="img"><br>
        </div>
        <div class="part2">
            <a href="dashboard.php"><i class='bx bxs-exit'> Pagina Principal</i></a><br>
            <a href="publicaciones.php"><i class='bx bx-user'> Publicaciones</i></a>
        </div>
        </div>
    </header>
    <main>
        <div class="container">
            <form action="addPublicaciones.php" method="POST" enctype="multipart/form-data">
                <label for="nombre">Ingrese el titulo</label><br>
                <input type="text" name="nombre" id="nombre"><br>
                <label for="publicacion">Seleccione una imagen</label><br>
                <input type="file" name="publicacion" id="publicacion"><br>
                <p> <?php echo $info1 . $info2 . $aler1; ?></p>
                <input class="btn" type="submit" value="Agregar Comentario" name="btn"><br>
            </form>
        </div>
    </main>
    <footer>
        <div>
            Español(Venezuela) 2024 Copyright
            <a href="">© Rjalvarez C.A </a>
        </div>
    </footer>
</body>

</html>