<?php
session_start();
if (!empty($_SESSION['id'])) {
    $id_user = $_SESSION['id'];
    $publication = $_COOKIE["id_publicacion"];

    include("base.php");
    $stmt = $conn->prepare('SELECT id_user, rol FROM usuarios WHERE id_user = ?');
    $stmt->bind_param('i', $id_user);
    $stmt->execute();
    $stmt->bind_result($id_usuario, $rol);
    $stmt->fetch();
    $stmt->close();
} else {
    header("Location: login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombre"];
    $publicacion = $_FILES['publicacion'];
    $ruta = "IMG/" . $publicacion['name'];
    $comentario = $_POST["comentario"];

    if ($nombre == "" || $publicacion == "" || $comentario == "") {
        echo "Datos vacios";
    } else if (!((strpos($ruta, 'gif') || strpos($ruta, 'png') || strpos($ruta, 'jpg') || strpos($ruta, 'webp')))) {
        echo "Solo se permiten formatos .gif, .png, .jpg, .webp <br>";
    } else if ($id_user == $id_usuario || $rol == 'admin') {
        include("base.php");
        $stmt = $conn->prepare('UPDATE publicaciones SET name = ?, publicacion = ? WHERE id_publicacion = ?');
        $stmt->bind_param('ssi', $nombre, $ruta, $publication);
        $stmt->execute();
        $stmt->close();
        include("base.php");
        $stmt = $conn->prepare('UPDATE comentarios SET comentarios = ? WHERE id_publicacion = ?');
        $stmt->bind_param('si', $comentario, $publication);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        setcookie("id_publicacion", "", time() - 3600, "/");
        move_uploaded_file($publicacion['tmp_name'], $ruta);
        header("Location: publicaciones.php");
    } else {
        echo "No se pudo Actualziar";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style\editarPublicacion.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Editar Publicacion</title>
</head>

<body>
    <header>
        <div class="part1">
            <img src="IMG\Logo.png" alt="logo" class="img">
        </div>
        <div class="part2">
            <a href="addPublicaciones.php"><i class='bx bx-add-to-queue'> Crear Publicaciones</i></a>
            <a href="dashboard.php"><i class='bx bxs-exit'> Pagina Principal</i></a>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="content">
                <h1>Editar Publicacion</h1>
                <form action="editarPublicacion.php" method="POST" enctype="multipart/form-data">
                    <label for="nombre">Ingrese el titulo Nuevamente</label><br>
                    <input type="text" name="nombre" id="nombre"><br>
                    <label for="publicacion">Seleccione una imagen</label><br>
                    <input type="file" name="publicacion" id="publicacion"><br>
                    <label for="comentario">Ingrese un Comentario</label><br>
                    <input type="text" name="comentario" id="comentario"><br>
                    <button  type="submit"> Aceptar </button> <br>
                </form>
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