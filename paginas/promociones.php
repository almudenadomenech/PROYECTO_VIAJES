<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones</title>

    <!-- swiper css link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('../includes/navbar.php'); ?>

<!-- Sección Promociones -->

<section class="heading-promociones" style="background:url(../images/user-list.jpg) no-repeat">
    <h1 style="font-size: 45px;">Promociones</h1>
</section>

<section class="promociones">
    <div class="promociones-container">
        <div class="promo">
            <img src="../images/promociones.jpg" alt="Promoción de registro" class="promo-img">
            <h3>¡Regístrate y obtén un 15% de descuento!</h3>
            <p>Al registrarte en nuestra página, recibirás un código exclusivo del 15% de descuento que podrás usar al reservar cualquier paquete vacacional.</p>
        </div>
    </div>
</section>

<?php include('../includes/footer.php'); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<script src="js/script.js" defer></script>

</body>
</html>
