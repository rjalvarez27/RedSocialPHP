<?php
$info1 = "";
$aler1 = "";

session_start();
if (!empty($_SESSION["id"])) {
    $id = $_SESSION["id"];
    $id_publicacion= $_SESSION["id_publicacion"];
} else {
    header("Location: login.php");
    exit();
}

if (isset($_POST["btn"])) {
   $comentario = $_POST["comentario"];
   if($comentario  == ""){
    $info1 = "Datos vacios";
   }else{
    include("base.php");
    $stmt = $conn->prepare("INSERT INTO comentarios (id_user, id_publicacion, comentarios) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id, $id_publicacion, $comentario);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    if ($id && $id_publicacion && $comentario) {
        $_SESSION["id_publicacion"] = "";
        header("Location: publicaciones.php");
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
    <link rel="stylesheet" href="style\addComentario.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Agregar Comentarios</title>
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
            <form action="addComentario.php" method="POST">
                <p>Ingrese comentarios para publicacion</p>
                <input type="text" name="comentario" id="comentario"><br>
                <p> <?php echo $info1 . $aler1; ?></p>
                <input class="btn" type="submit" value="publicar" name="btn"><br>
            </form>
        </div>
        <div>

        </div>
    </main>
</body>

</html>