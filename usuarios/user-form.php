<?php
include('../includes/navbar.php');
include('../includes/conexion.php'); // Conexión a la base de datos

// Iniciar sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../paginas/login.php");
    exit;
}

// Obtener datos del usuario logueado
$user_id = $_SESSION['id'];
$query = "SELECT * FROM usuarios WHERE id = '$user_id'";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result); // Cargar datos del usuario
} else {
    echo "No se encontró el usuario.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="heading" style="background:url(../images/user-list.jpg) no-repeat">
        <h1 style="font-size: 45px;">Formulario de Usuario</h1>
    </div>

    <section class="booking">
        <h1 class="heading-title">Actualizar Perfil</h1>

        <form action="guardar_datos.php" method="POST" class="booking-form" enctype="multipart/form-data">
            <div class="flex">
                <div class="inputBox">
                    <span>Usuario:</span>
                    <input type="text" name="usuario" value="<?= htmlspecialchars($user['usuario']) ?>" required>
                </div>
                <div class="inputBox">
                    <span>Apellidos:</span>
                    <input type="text" name="apellidos" value="<?= htmlspecialchars($user['apellidos']) ?>" required>
                </div>
                <div class="inputBox">
                    <span>Dirección:</span>
                    <input type="text" name="direccion" value="<?= htmlspecialchars($user['direccion']) ?>" required>
                </div>
                <div class="inputBox">
                    <span>Teléfono:</span>
                    <input type="text" name="telefono" value="<?= htmlspecialchars($user['telefono']) ?>" required>
                </div>
                <div class="inputBox">
                    <span>Email:</span>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <!-- <div class="inputBox">
                    <span>Foto de Perfil:</span>
                    <input type="file" name="foto_perfil">
                    
                    <?php if ($user['foto_perfil']) { ?>
                        <br><img src="<?php echo htmlspecialchars($user['foto_perfil']); ?>" alt="Foto de perfil" style="width: 100px; height: 100px; border-radius: 50%;">
                    <?php } ?>
                </div> -->
            </div>

            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">

            <div class="button-container">
                <input type="submit" value="Actualizar" class="btn">
                <a href="avatar.php" class="btn">Subir Foto</a>
            </div>

        </form>
    </section>

    <?php include('../includes/footer.php'); ?>
</body>

</html>