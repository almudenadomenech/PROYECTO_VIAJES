<?php
// Incluir archivos de cabecera
include('../includes/navbar.php');
include('../includes/conexion.php');

// Verificar si el usuario está logueado y si es un administrador
if (!isset($_SESSION['id']) || $_SESSION['role_id'] != 2) {
    header('Location: ../index.php');
    exit();
}

// Obtener el ID del usuario desde la URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Consulta para obtener la información completa del usuario
    $query = "SELECT * FROM usuarios WHERE id = $userId";
    $result = mysqli_query($link, $query);

    if (!$result) {
        die("Error en la consulta: " . mysqli_error($link));
    }

    // Verificar si se encontró al usuario
    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        die("Usuario no encontrado.");
    }
} else {
    die("ID de usuario no proporcionado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- Encabezado -->
<section class="heading-user-list" style="background:url(../images/user-list.jpg) no-repeat">
    <h1>Detalles del Usuario</h1>
</section>

<!-- Información del usuario -->
<section class="user-details">
    <div class="user-card">
        <div class="foto">
            <?php if (!empty($user['foto_perfil'])): ?>
                <img src="<?= $user['foto_perfil']; ?>" alt="Foto de perfil" class="foto-perfil">
            <?php else: ?>
                <img src="../images/usuario-default.png" alt="Avatar predeterminado" class="foto-perfil">
            <?php endif; ?>
        </div>

        <h2>Nombre de Usuario: <?php echo htmlspecialchars($user['usuario']). ' '; echo htmlspecialchars($user['apellidos']);?></h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>     
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($user['telefono']); ?></p>
        <p><strong>Dirección:</strong> <?= htmlspecialchars($user['direccion']); ?></p>
       
       
    </div>


</section>
    <!-- Botón para volver a la lista de usuarios -->
    <div class="volver-container">
    <a href="user-list.php" class="btn btn-volver">← Volver a la Lista de Usuarios</a>
</div>
<!-- Footer -->
<?php
include('../includes/footer.php');
?>

<!-- Cerrar la conexión -->
<?php
mysqli_close($link);
?>

</body>
</html>
