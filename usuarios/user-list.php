<?php
 include('../includes/navbar.php');
include('../includes/conexion.php'); // Conexión a la base de datos



// Verificar si el usuario está logueado y si es un administrador
if (!isset($_SESSION['id']) || $_SESSION['role_id'] != 2) { // Cambié 'rol' por 'role_id' para ser consistente
    // Si no es un administrador, redirige a la página de inicio o acceso denegado
    header('Location: ../index.php');
    exit();
}

// Consulta para obtener todos los usuarios
$query = "SELECT * FROM usuarios"; 
$result = mysqli_query($link, $query);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($link));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <!-- swiper css link -->
    <linkrel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>

     <!-- font awesone cdn link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="stylesheet" href="../css/style.css">
</head>
<body>


<!-- Encabezado -->
<section class="heading-user-list" style="background:url(../images/user-list.jpg) no-repeat">
    <h1>Lista de Usuarios Registrados</h1>
</section>

<!-- Tabla de usuarios -->
<section class="usuarios-list">
    <h1 class="heading-title">Lista de Usuarios</h1>

    <div class="usuarios-container">
        <?php while ($user = mysqli_fetch_assoc($result)): ?>
            <div class="usuario-card">
                <div class="foto">
                    <?php if (!empty($user['foto_perfil'])): ?>
                        <img src="<?= $user['foto_perfil']; ?>" alt="Foto de perfil" class="foto-perfil">
                    <?php else: ?>
                        <img src="../images/usuario-default.png" alt="Avatar predeterminado" class="foto-perfil">
                    <?php endif; ?>
                </div>
                <h2><?= htmlspecialchars($user['usuario']); ?></h2>
                <div class="acciones">
                    <a href="user-detail.php?id=<?= $user['id']; ?>" class="btn btn-detalles">Detalles</a>
                    <a href="eliminar_usuario.php?id=<?= $user['id']; ?>" class="btn btn-eliminar" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>


<!-- swiper js link  -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

     <script src="../js/script.js"></script>
</body>
</html>
  <!-- seccion footer   -->
  <?php
    include('../includes/footer.php');
?>

<?php
// Cerrar la conexión
mysqli_close($link);
?>
