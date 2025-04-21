<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)[
    header("location:home.php")]
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <!-- swiper css link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- font awesone cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
     <!-- seccion navbar apertura -->
     <?php

include('navbar.php');
?>
<!-- seccion navbar cierre -->

    <div class="ctn-wellcome">
        <img src="" alt="" class="logo-wellcome">
        <h1 class="title-wellcome">Bienvenido!</h1>
        <a href="cerrar_sesion.php" class="close-sesion">Cerrar sesión</a>
    </div>


   

    <!-- seccion footer apertura -->
    <?php
    include('footer.php');
    ?>
    <!-- seccion footer cierre -->

    <!-- swiper js link  -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script src="js/script.js"></script>
</body>

</html>