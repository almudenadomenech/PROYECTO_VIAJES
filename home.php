<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

  <!-- swiper css link -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

<!-- font awesone cdn link  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="css/style.css">

   
</head>
<body>

<?php
    include('includes/navbar.php');
?>

<!-- seccion home apertura -->
 <section class="home">

 <div class="swiper home-slider">

 <div class="swiper-wrapper">

        <div class="swiper-slide slide" style="background:url(images/home-slide-1.jpg) no-repeat">
            <div class="content">
                <span>explorar, descubrir, viajar</span>
                <h3>Viajes alrededor del mundo</h3>
                <a href="paquetes/paquetes.php" class="btn">Descubre más</a>
            </div>

        </div>

        <div class="swiper-slide slide" style="background:url(images/home-slide-2.jpg) no-repeat">
            <div class="content">
                <span>explorar, descubrir, viajar</span>
                <h3>Descubre nuevos lugares</h3>
                <a href="paquetes/paquetes.php" class="btn">Descubre más</a>
            </div>

        </div>

        <div class="swiper-slide slide" style="background:url(images/home-slide-3.jpg) no-repeat">
            <div class="content">
                <span>explorar, descubrir, viajar</span>
                <h3>Haz que tu viaje valga la pena</h3>
                <a href="paquetes/paquetes.php" class="btn">Descubre más</a>
            </div>

        </div>
    </div>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
 </div>

 </section>
<!-- seccion home apertura cierre-->


<!-- seccion servicios  apertura-->
 <section class="services">

 <h1 class="heading-title">Nuestros servicios</h1>

 <div class="box-container">

    <div class="box">
        <img src="images/icon-1.png" alt="">
        <h3>Aventuras</h3>
    </div>

    <div class="box">
        <img src="images/icon-2.png" alt="">
        <h3>Tour guiados</h3>
    </div>

    <div class="box">
        <img src="images/icon-3.png" alt="">
        <h3>Trekking</h3>
    </div>

    <div class="box">
        <img src="images/icon-4.png" alt="">
        <h3>Camp fire</h3>
    </div>

    <div class="box">
        <img src="images/icon-5.png" alt="">
        <h3>Off road</h3>
    </div>

    <div class="box">
        <img src="images/icon-6.png" alt="">
        <h3>Camping</h3>
    </div>


 </div>
 </section>
<!-- seccion servicios  cierre-->


<!-- Home about section apertura-->

<section class="home-about">

    <div class="image">
        <img src="images/about-img.jpg" alt="">
    </div>

    <div class="content">
        <h3>Conocenos</h3>
        <p>En Melek Viajes, nos apasiona hacer de cada viaje una experiencia inolvidable. Somos un equipo de expertos en turismo comprometidos en ofrecerte los mejores destinos, aventuras personalizadas y un servicio excepcional. Ya sea que busques relajarte en una playa paradisíaca, explorar culturas fascinantes o disfrutar de una escapada única, estamos aquí para ayudarte a hacer realidad tus sueños de viaje. Únete a nosotros y descubre el mundo de una manera diferente. ¡Tu próxima aventura comienza con nosotros!</p>
        <a href="paginas/AcercaDe.php" class="btn" >Leer más</a>
    </div>

</section>
<!-- Home about section cierre-->


<!-- Seccion paquetes del home apertura-->

<section class="home-packages">

    <h1 class="heading-title">Nuestros paquetes </h1>

    <div class="box-container">

        <div class="box">
            <div class="image">
                <img src="images/img-1.jpg" alt="">
            </div>
        
        <div class="content">
            <h3>Cultura y Tradición</h3>
            <p>Experiencia Auténtica. Visitas a mercados, museos y barrios históricos para conocer el alma de cada destino.</p>
            
        </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="images/img-2.jpg" alt="">
            </div>
        
        <div class="content">
            <h3>Playas de Ensuelo</h3>
            <p>Explora arrecifes, nada con delfines o prueba el surf en las mejores olas o Relájate en arenas blancas y aguas cristalinas con todo incluido.</p>
           
        </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="images/img-3.jpg" alt="">
            </div>
        
        <div class="content">
            <h3>Senderismo y Naturaleza</h3>
            <p>Explora rutas impresionantes, cascadas escondidas y paisajes de ensueño. Disfruta de cabañas acogedoras, chimeneas y vistas panorámicas.</p>
           
        </div>
        </div>

    </div>

    <div class="load-more">
        <a href="paquetes/paquetes.php" class="btn">Ver paquetes</a>
    </div>

</section>
<!-- Seccion paquetes del home cierre-->


<!--Oferta inicio apertura -->

<section class="home-offer">

    <div class="content">
        <h3>Hasta un 50% de descuento </h3>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eius temporibus </p>
        <a href="booking/booking.php" class="btn">Reserva ahora</a>
    </div>

</section>
<!--Oferta inicio cierre -->


<!-- seccion footer apertura -->
<?php
    include('includes/footer.php');
?>
<!-- seccion footer cierre -->


<!-- swiper js link  -->

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>

<!-- Ahora, carga tu script personalizado después -->
<script src="js/script.js" defer></script>

</body>
</html>