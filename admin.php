<?php
$id_publicacion = 0;
$inf  = "";
$alert = "";
$alert2 = "";
session_start();
if (!empty($_SESSION["id"])) {
    $id = $_SESSION["id"];
    include("base.php");
    $stmt = $conn->prepare("SELECT id_user, nombre, email, rol  FROM usuarios WHERE id_user = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($id_user, $nombre, $email, $rol);
    $stmt->fetch();
    if ($rol != "admin") {
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
include("base.php");
$stmt = ("SELECT usuarios.id_user, usuarios.rol,publicaciones.id_publicacion, publicaciones.name, publicaciones.publicacion, publicaciones.fechaCreacion, comentarios.comentarios FROM usuarios INNER JOIN publicaciones INNER JOIN comentarios ON  publicaciones.id_user = usuarios.id_user AND publicaciones.id_publicacion = comentarios.id_publicacion WHERE usuarios.rol = 'user'");
$result = $conn->query($stmt);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_POST["id_user"] ?? "";

    include("base.php");
    if ($id_user == "") {
        $inf = "Debes seleccionar un usuario";
    } else if ($id_user && $rol != "admin") {
        $stmt = $conn->prepare("DELETE FROM comentarios WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM publicaciones WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $stmt->close();

        $conn->close();
        $alert = "Usuario eliminado con exito";
    } else {
       $alert2 = "Error al eliminar usuario";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style\admin.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Administrador</title>
</head>

<body>
    <header>
        <div class="part1">
            <img src="IMG\Logo.png" alt="logo" class="img">
        </div>
        <div class="part2">
            <a href="dashboard.php"><i class='bx bxs-exit'>Pagina Principal</i></a>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="content">
                <div class="inf1">
                    <p>Recuerde que por ser Administrador no podra actualizar sus datos, para hacerlo tiene que conactarse con el area de sistemas</p>
                    <h3>Administrador: <?php echo $nombre ?></h1>
                        <h3>Correo: <?php echo $email ?></h1>
                </div>
                <div class="inf2">
                    <h3>Perfiles</h3>
                    <p>En este apartado podra ver todos los perfiles de los usuarios</p>
                    <?php
                    include("base.php");
                    $rol = 'user';
                    $userDB = $conn->prepare("SELECT * FROM usuarios WHERE rol = ? ");
                    $userDB->bind_param("s", $rol);
                    $userDB->execute();
                    $result1 = $userDB->get_result()
                    ?>
                    <?php if ($result1->num_rows > 0) { ?>
                        <?php while ($row = $result1->fetch_assoc()) { ?>
                            <div class="user">
                                <p class="">ID: <?php echo $row["id_user"] ?></p>
                                <p class="">Nombre: <?php echo $row["nombre"] ?></p>
                                <p class=""> Email:<?php echo $row["email"] ?></p>
                            </div>
                        <?php } ?>
                    <?php } else {
                        echo "No hay usuarios registrados";
                    } ?>
                </div>
                <div class="inf3">
                    <form action="admin.php" method="POST">
                        <input type="text" name="id_user" id="_id_user">
                        <button type="submit" class="btn btn-danger">Eliminar usuario</button">
                    </form>
                </div>
             <?php echo $inf; ?>
            <?php echo $alert; ?>
           <?php echo $alert2; ?>
            </div>
            <div class="content2">
                <h2>Publicaciones</h3>
                    <p>En este apartado podra ver todas las publicaciones y los comentarios de los usuarios </p>
                    <div>
                        <?php if ($result1->num_rows > 0) { ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <div class="card">
                                <div  class="card-top">
                                    <h1 class="card-title"><?php echo $row["name"] ?></h1>
                                    <center><img src="<?php echo $row["publicacion"] ?>" class="" alt="..."></center>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-comments"> Comentarios: </h5>
                                    <p class="card-comments""><?php echo $row["comentarios"] ?></p>
                                    <h5 class="card-date"><?php echo $row["fechaCreacion"] ?></h5>
                                </div>
                                <div class="card-footer">
                                <button class="btn2"><a  class="btn3" href='borrarPublicacion.php?id_publicacion=<?php echo $row["id_publicacion"] ?> '>Borrar publicacion</a></button>
                                </div>
                            </div>
                            <?php } ?>
                        <?php } else {
                            echo "No hay publicaciones ";
                        } ?>
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
