<?php
$id_publicacion = 0;
session_start();
if (!empty($_SESSION["id"])) {
    $id = $_SESSION["id"];
    include("base.php");
    $stmt = $conn->prepare("SELECT id_user, nombre, email  FROM usuarios WHERE id_user = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($id_user, $nombre, $email);
    $stmt->fetch();
} else {
    header("Location: login.php");
    exit();
}
include("base.php");
$stmt = ("SELECT  publicaciones.id_publicacion, publicaciones.name, publicaciones.publicacion, publicaciones.fechaCreacion, comentarios.comentarios FROM publicaciones INNER JOIN comentarios ON publicaciones.id_publicacion = comentarios.id_publicacion where publicaciones.id_user = $id");
$result = $conn->query($stmt);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style\publicaciones.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Publicaciones</title>
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
        <section>
            <div class="container">
                <?php if ($result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <div class="card">
                            <h1 class="card-title"><?php echo $row["name"] ?></h1>
                            </h1>
                            <center><img src="<?php echo $row["publicacion"] ?>" alt="img"></center>
                            <div class="card-body">
                                <h5 class="card-comments">comentarios</h5>
                                <p class="card-text"><?php echo $row["comentarios"] ?></p>
                                <h5 class="card-date"><?php echo $row["fechaCreacion"] ?>
                            </div>
                            <div class="card-footer">
                                <button class="btn1"><a  class="btn3" href='editarPublicacion.php'  <?php setcookie("id_publicacion", $row['id_publicacion'], time() + 3600, "/");  ?>> Editar </a></button>
                                <button class="btn2"><a class="btn3" href='borrarPublicacion.php?id_publicacion=<?php echo $row["id_publicacion"] ?> '> Borrar</a></button>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else {
                    echo "No hay publicaciones";
                } ?>
            </div>
        </section>
    </main>
    <footer>
        <div class="">
            Español(Venezuela) 2024 Copyright
            <a href="">© Rjalvarez C.A </a>
        </div>
    </footer>
</body>

</html>