<?php
include('../includes/conexion.php');
session_start();

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['id']) || $_SESSION['role_id'] != 2) {
    header('Location: ../index.php');
    exit();
}

// Verificar que se recibió el ID por POST (desde el formulario del modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']);

    // Eliminar la imagen de perfil si existe
    $result = mysqli_query($link, "SELECT foto_perfil FROM usuarios WHERE id = $id");
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (!empty($user['foto_perfil']) && file_exists($user['foto_perfil'])) {
            unlink($user['foto_perfil']);
        }
    }

    // Eliminar el usuario
    $query = "DELETE FROM usuarios WHERE id = $id";
    if (mysqli_query($link, $query)) {
        header("Location: user-list.php?deleted=true");
        exit();
    } else {
        echo "Error al eliminar usuario: " . mysqli_error($link);
    }

} else {
    echo "ID inválido.";
}

mysqli_close($link);
?>
