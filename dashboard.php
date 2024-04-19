<?php
session_start();
if (!empty($_SESSION["id"])) {
    $id = $_SESSION["id"];
    include("base.php");
    $stmt = $conn->prepare("SELECT id_user, nombre, email  FROM usuarios WHERE id_user = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($id_user, $nombre, $email);
    $stmt->fetch();
    if ($email) {
        $user = $nombre;
    } else {
        $inf1 = "Error de conexion a la base de datos";
    }
} else {
    header("Location: login.php");
    exit();
}
include("base.php");
$stmt = ("SELECT usuarios.nombre, publicaciones.name, publicaciones.publicacion, publicaciones.fechaCreacion, comentarios.comentarios FROM usuarios INNER JOIN publicaciones INNER JOIN comentarios ON usuarios.id_user = publicaciones.id_user AND publicaciones.id_publicacion =  comentarios.id_publicacion " );  
$result = $conn->query($stmt);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style\dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Photogram PHP</title>
</head>

<body>
    <header>
        <div class="part1">
            <img src="IMG\Logo.png" alt="logo" class="img">
        </div>
        <div class="part2">
            <a href="addPublicaciones.php"><i class='bx bx-add-to-queue'> Crear Publicaciones</i></a>
            <a href="publicaciones.php"><i class='bx bx-home-alt-2'> Publicaciones </i></a>
            <a href="perfil.php"><i class='bx bx-user'> Perfil</i></a>
            <a href="closeSession.php"><i class='bx bxs-exit'> Exit</i></a>
        </div>
        <div class="part3">
            <h1><?php echo "Bienvenido $user" ?></h1>
        </div>
    </header>
    <main>
        <div class="container">
            <h1>Publicaciones</h1>
            <section class="card-container">
                <?php if ($result->num_rows > 0) { ?>
                    <?php foreach ($result as $row) { ?>
                        <div class="card">
                            <h1 class="card-title"><?php echo $row["name"] ?></h1>
                            <center><img src="<?php echo $row["publicacion"] ?>" class="" alt="..."></center>
                            <div class="card-body">
                                <h3><i class='bx bxs-user-circle' ></i> <?php echo $row["nombre"] ?></h2>
                                <h6 class="card-titleC">comentarios:</h5>
                                <p class="card-text"><?php echo $row["comentarios"] ?></p>
                               <h6 class="card-date"><?php echo $row["fechaCreacion"] ?></h1> 
                            </div>
                        </div>
                    <?php } ?>
                <?php } else {
                    echo "No hay publicaciones";
                } ?>
            </section>
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