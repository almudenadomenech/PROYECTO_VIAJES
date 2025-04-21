<?php
session_start();
include('../includes/conexion.php'); // Conexión a la base de datos

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id']; // ID del usuario logueado

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $usuario = mysqli_real_escape_string($link, $_POST['usuario']);
    $apellidos = mysqli_real_escape_string($link, $_POST['apellidos']);
    $direccion = mysqli_real_escape_string($link, $_POST['direccion']);
    $telefono = mysqli_real_escape_string($link, $_POST['telefono']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $foto_perfil = null; // Si no hay foto, no cambiarla

    // Verificar que no haya campos vacíos
    if (empty($usuario) || empty($apellidos) || empty($direccion) || empty($telefono) || empty($email)) {
        echo "Todos los campos son requeridos.";
        exit;
    }

    // Verificar si el email está siendo actualizado y si ya existe en la base de datos
    if ($email) {
        $email_check_query = "SELECT id FROM usuarios WHERE email = '$email' AND id != '$user_id'";
        $email_check_result = mysqli_query($link, $email_check_query);
        if (mysqli_num_rows($email_check_result) > 0) {
            echo "El correo electrónico ya está en uso por otro usuario.";
            exit;
        }
    }

    // Procesar la subida de la foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $foto_tmp = $_FILES['foto_perfil']['tmp_name'];
        $foto_nombre = basename($_FILES['foto_perfil']['name']);
        $foto_ruta = '../uploads/' . $foto_nombre;

        // Verificar que el archivo sea una imagen válida (por ejemplo, JPG, PNG)
        $ext = pathinfo($foto_nombre, PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array(strtolower($ext), $allowed_extensions)) {
            echo "Solo se permiten imágenes JPG, JPEG, PNG o GIF.";
            exit;
        }

        // Mover el archivo subido a la carpeta "uploads"
        if (move_uploaded_file($foto_tmp, $foto_ruta)) {
            $foto_perfil = $foto_ruta;
        } else {
            echo "Error al subir la foto.";
            exit;
        }
    }

    // Construir la consulta de actualización
    $query = "UPDATE usuarios SET 
                usuario = '$usuario', 
                apellidos = '$apellidos', 
                direccion = '$direccion', 
                telefono = '$telefono', 
                email = '$email'";

    // Si se ha subido una foto, agregarla a la consulta
    if ($foto_perfil) {
        $query .= ", foto_perfil = '$foto_perfil'";
    }

    $query .= " WHERE id = '$user_id'"; // Filtrar por el ID del usuario

    // Ejecutar la consulta
    if (mysqli_query($link, $query)) {
        header("Location: perfil.php"); // Redirigir al perfil después de actualizar
        exit;
    } else {
        echo "Error al guardar los datos: " . mysqli_error($link);
    }
} else {
    echo "Solicitud no válida.";
}
?>
