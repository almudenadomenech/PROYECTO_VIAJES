<?php
session_start();
// Conectar a la base de datos
include('../includes/conexion.php');

// Verificar si la sesión está iniciada y si el usuario es administrador
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['role_id'] == 2) {
    $isAdmin = true;
} else {
    $isAdmin = false;
}

// Consultar los paquetes desde la base de datos
$query = "SELECT id, location, description, image FROM paquetes";

$result = mysqli_query($link, $query);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta a la base de datos: " . mysqli_error($link));
}

// Si no hay paquetes, mostrar un mensaje
if (mysqli_num_rows($result) == 0) {
    echo "<p>No se encontraron paquetes.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paquetes</title>

    <!-- swiper css link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php
    include('../includes/navbar.php');
    ?>

    <div class="heading" style="background:url(../images/header-2.jpg) no-repeat">
        <h1>Paquetes</h1>
    </div>

    <!-- Seccion paquetes inicio -->
    <section class="packages">

        <h1 class="heading-title">Principales destinos</h1>

        <div class="box-container">
            <?php
            // Iterar sobre los resultados de la base de datos
            while ($paquete = mysqli_fetch_assoc($result)) :
                // Verificar si la imagen existe, si no, poner una predeterminada
                $image = !empty($paquete['image']) ? "../images/" . $paquete['image'] : "../images/viaje-default.jpg";
            ?>
                <div class="box">
                <div class="image">
    <a href="paquetes-detail.php?id=<?= $paquete['id'] ?>">
        <img src="<?= $image ?>" alt="<?= htmlspecialchars($paquete['location']) ?>">
    </a>
</div>

                    <div class="content">
                        <h3><?= htmlspecialchars($paquete['location']) ?></h3>
                        <p><?= htmlspecialchars($paquete['description']) ?></p>
                        <?php if ($isAdmin): ?>
                            <div class="admin-btns">
                                <a href="paquetes-form.php?id=<?= $paquete['id'] ?>" class="btn btn-editar">Editar</a>
                                <a href="eliminar-paquete.php?id=<?= $paquete['id'] ?>" class="btn btn-eliminar">Eliminar</a>
                            </div>
                        <?php else: ?>
                            <a href="paquetes-detail.php?id=<?= $paquete['id'] ?>" class="btn">Ver detalles</a>
                        <?php endif; ?>



                    </div>
                </div>
            <?php endwhile; ?>

        </div>

        <div class="load-more-packages"><span class="btn" id="load-more-btn">Leer más</span></div>
    </section>
    <!-- Seccion paquetes fin -->

    <!-- seccion footer -->
    <?php
    include('../includes/footer.php');
    ?>

    <!-- Swiper debe ir primero -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Luego tu script que lo usa -->
    <script src="../js/script.js"></script>

</body>

</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($link);
?>