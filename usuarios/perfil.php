<?php
session_start();
include('../includes/conexion.php'); // Conexión a la base de datos

// Verificar si el usuario está logueado
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirigir al login si no está autenticado
    exit;
}

$user_id = $_SESSION['id']; // ID del usuario logueado

// Obtener los datos del usuario
$query = "SELECT * FROM usuarios WHERE id = '$user_id'";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result); // Cargar los datos del usuario
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
    <title>Perfil del Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php include('../includes/navbar.php'); ?> 

    <div class="heading" style="background:url(../images/user-list.jpg) no-repeat">
        <h1 style="font-size: 45px;">Perfil de Usuario</h1>
    </div>

    <section class="profile-section"> 
        

        <div class="profile-flex"> 
            <!-- Foto de perfil a la izquierda -->
            <div class="profile-image"> 
    <?php if ($user['foto_perfil']): ?>
        <img src="<?php echo htmlspecialchars($user['foto_perfil']); ?>" alt="Foto de perfil">
    <?php else: ?>
        <img src="../images/usuario-default.png" alt="Foto de perfil predeterminada">
    <?php endif; ?>
</div>


            <!-- Datos del usuario en una tarjeta a la derecha -->
            <div class="profile-card"> 
                <div class="profile-inputBox"> 
                    <h1 style="display: inline;"><?php echo htmlspecialchars($user['usuario']); ?></h1>
                    <h1 style="display: inline; margin-left: 10px;"><?php echo htmlspecialchars($user['apellidos']); ?></h1>
                </div>

                <div class="profile-inputBox"> 
                    <span>Dirección:</span>
                    <p><?php echo htmlspecialchars($user['direccion']); ?></p>
                </div>
                <div class="profile-inputBox"> 
                    <span>Teléfono:</span>
                    <p><?php echo htmlspecialchars($user['telefono']); ?></p>
                </div>
                <div class="profile-inputBox"> 
                    <span>Email:</span>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
        </div>

        <!-- Botón para redirigir al formulario de actualización -->
        <div style="text-align: center; margin-top: 1rem;">
            <a href="user-form.php" class="btn">Actualizar Datos</a>
        </div>
    </section>

    <?php include('../includes/footer.php'); ?> 
</body>

</html>
