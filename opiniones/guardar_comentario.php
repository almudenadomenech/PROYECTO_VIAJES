<?php
include('../includes/conexion.php');

// Asegúrate de que el usuario esté logueado
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login.php');
    exit;
}

// Recoge los datos del formulario
$package_id = $_POST['package_id'];
$user_id = $_POST['user_id'];
$rating = $_POST['rating'];
$comment = mysqli_real_escape_string($conn, $_POST['comment']);

// Inserta el comentario en la base de datos
$query = "INSERT INTO comentarios (package_id, user_id, rating, comment) VALUES ('$package_id', '$user_id', '$rating', '$comment')";
if (mysqli_query($conn, $query)) {
    // Redirige al paquete donde se dejó el comentario
    header("Location: paquete.php?id=$package_id");
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
