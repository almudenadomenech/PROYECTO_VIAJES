<?php


// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Si no está logueado, redirigir a la página de login
    header("Location: ../paginas/login.php");
    exit;
}

$connection = mysqli_connect('localhost', 'root', 'admin', 'login');

// Obtener el user_id de la sesión
$user_id = $_SESSION['id'];

if (isset($_POST['send'])) {
    // Recoger los datos del formulario
    $nombre = $_POST['name'];
    $email = $_POST['email'];
    $telefono = $_POST['phone'];
    $direccion = $_POST['address'];
    $donde_viajar = $_POST['location'];
    $num_personas = $_POST['guest'];
    $fecha_inicio = $_POST['arrivals'];
    $fecha_fin = $_POST['leaving'];

    // Preparar la consulta SQL con parámetros
    $sql = "INSERT INTO booking (nombre, email, telefono, direccion, location, numero_personas, fecha_inicio, fecha_fin, usuario_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($connection, $sql)) {
        // Vincular los parámetros a la declaración preparada
        mysqli_stmt_bind_param($stmt, "sssssssss", $nombre, $email, $telefono, $direccion, $donde_viajar, $num_personas, $fecha_inicio, $fecha_fin, $user_id);

        // Ejecutar la declaración
        if (mysqli_stmt_execute($stmt)) {
            // Redirigir a la página de reservas si se ejecuta correctamente
            header("Location: booking.php");
            exit;
        } else {
            echo "Error al realizar la reserva.";
        }

        // Cerrar la declaración
        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la consulta.";
    }
}

mysqli_close($connection);
?>
