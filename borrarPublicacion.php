<?php

session_start();
if (!empty($_SESSION['id'])) {
    $id_user = $_SESSION['id'];
    $id_publicacion = $_GET['id_publicacion'];

    include("base.php");
    $stmt = $conn->prepare('SELECT id_user FROM publicaciones WHERE id_publicacion = ?');
    $stmt->bind_param('i', $id_publicacion);
    $stmt->execute();
    $stmt->bind_result($id_usuario);
    $stmt->fetch();
    $stmt->close();
    
    $stmt = $conn->prepare('SELECT rol FROM usuarios WHERE id_user = ?');
    $stmt->bind_param('i', $id_user);
    $stmt->execute();
    $stmt->bind_result($rol);
    $stmt->fetch();
    $stmt->close();

    if ($id_user == $id_usuario || $rol == 'admin') {
        include("base.php");
        $stmt = $conn->prepare('DELETE FROM comentarios WHERE id_publicacion = ?');
        $stmt->bind_param('i', $id_publicacion);
        $stmt->execute();
        $stmt->close();
        
        $stmt = $conn->prepare('DELETE FROM publicaciones WHERE id_publicacion = ?');
        $stmt->bind_param('i', $id_publicacion);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: dashboard.php");
    } else {
        echo "Error del servidor al eliminar la publicacion";
    }
} else {
    header("Location: register.php");
    exit();
}
