<?php
session_start();

// Conectar a la base de datos
include('../includes/conexion.php');

// Verificar si el usuario está autenticado y es administrador
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['role_id'] == 2) {
    // Verificar si se ha pasado un id en la URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];

        // Eliminar el paquete de la base de datos
        $query = "DELETE FROM paquetes WHERE id = ?";
        $stmt = mysqli_prepare($link, $query);

        if ($stmt) {
            // Vincular el parámetro (id) a la consulta
            mysqli_stmt_bind_param($stmt, "i", $id);

            // Ejecutar la consulta
            if (mysqli_stmt_execute($stmt)) {
                // Redirigir de vuelta a la página de paquetes después de eliminar
                header('Location: paquetes.php');
                exit;
            } else {
                echo "Error al eliminar el paquete: " . mysqli_error($link);
            }
            // Cerrar la declaración
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($link);
        }
    } else {
        echo "ID de paquete no válido.";
    }
} else {
    // Si el usuario no es admin, redirigir al login o alguna otra página
    header('Location: ../login.php');
    exit;
}

// Cerrar la conexión
mysqli_close($link);
?>
