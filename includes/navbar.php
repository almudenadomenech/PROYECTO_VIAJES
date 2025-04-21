<?php
// Iniciar sesión si aún no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Variables iniciales
$foto_default = "../images/usuario-default.png";
$foto_perfil = $foto_default;

// Solo si hay sesión iniciada y se guardó la foto en la sesión (más seguro)
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (!empty($_SESSION['foto_perfil'])) {
        $foto_perfil = $_SESSION['foto_perfil'];
    }
}
?>

<section class="header">
    <a href="/home.php" class="logo">
        <img src="/images/Logo.png" alt="Logo">
    </a>

    <nav class="navbar">
        <a href="../home.php">Home</a>
        <a href="../paginas/acercaDe.php">Acerca de</a>
        <a href="../paquetes/paquetes.php">Paquetes</a>
        <a href="../booking/booking.php">Booking</a>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <div class="profile-dropdown">
                <a href="javascript:void(0)" class="profile-photo" onclick="toggleDropdown(event)">
                    <img src="<?= $foto_perfil ?>" alt="Foto de perfil" style="width: 40px; height: 40px; border-radius: 50%; margin-left: 10px;">
                </a>
                <div class="dropdown-content" id="dropdown-menu">
                    <a href="../usuarios/perfil.php">Mi perfil</a>
                    <?php if ($_SESSION['role_id'] != 2): ?>
                        <a href="../booking/booking-list.php">Mis reservas</a>
                    <?php else: ?>
                        <a href="../usuarios/user-list.php">Lista de usuarios</a>
                        <a href="../booking/booking-list.php">Ver reservas</a>
                        <a href="../paquetes/paquetes-form.php">Añadir paquetes</a>
                    <?php endif; ?>
                    <a href="../conexiones/cerrar_sesion.php">Cerrar sesión</a>
                </div>
            </div>
        <?php else: ?>
            <a href="../paginas/login.php">Login</a>
            <a href="../paginas/register.php">Register</a>
        <?php endif; ?>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>
</section>

<script src="../js/script.js"></script>
