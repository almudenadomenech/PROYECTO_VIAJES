<?php
session_start();
include('../includes/navbar.php');
include('../includes/conexion.php'); // Asegurar conexión con la base de datos

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../paginas/login.php");
    exit;
}

// Obtener ID del usuario logueado
$user_id = $_SESSION['id'];

// Generar código de descuento si no existe aún en la sesión
if (!isset($_SESSION['discount_code'])) {
    $randomString = strtoupper(substr(md5(time() . rand()), 0, 6)); // Código de 6 caracteres aleatorios
    $_SESSION['discount_code'] = 'DESCUENTO_' . $randomString;
}
$discount_code = $_SESSION['discount_code']; // Código disponible para usar

// Consultar los datos del usuario en la base de datos
$query = "SELECT usuario, email, telefono, direccion FROM usuarios WHERE id = $user_id";
$result = mysqli_query($link, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    $user_data = ['usuario' => '', 'email' => '', 'telefono' => '', 'direccion' => ''];
}

// Consultar los paquetes disponibles
$packages_query = "SELECT id, nombre FROM paquetes";
$packages_result = mysqli_query($link, $packages_query);

// Consultar duraciones de paquetes
$duraciones_query = "SELECT id, paquete_id, duracion, precio FROM duraciones_paquete";
$duraciones_result = mysqli_query($link, $duraciones_query);

// CONSULTA PARA OBTENER EL NOMBRE Y LA UBICACIÓN DEL PAQUETE SELECCIONADO
$package_name = "No se seleccionó ningún paquete";
$package_location = "";

if (isset($_GET['id'])) {
    $package_id = $_GET['id'];

    $package_query = "SELECT nombre, location FROM paquetes WHERE id = '$package_id'";
    $package_result = mysqli_query($link, $package_query);
    if ($package_result && mysqli_num_rows($package_result) > 0) {
        $package_data = mysqli_fetch_assoc($package_result);
        $package_name = $package_data['nombre'];
        $package_location = $package_data['location'];
    }
}

// Verificar si el formulario fue enviado
if (isset($_POST['send'])) {
    // Obtener los datos del formulario
    $name = $_POST['name'];
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    $address = mysqli_real_escape_string($link, $_POST['address']);
    $guest = mysqli_real_escape_string($link, $_POST['guest']);
    $arrivals = mysqli_real_escape_string($link, $_POST['arrivals']);
    $leaving = mysqli_real_escape_string($link, $_POST['leaving']);
    $duracion_paquete_id = $_POST['duracion_paquete_id']; // Obtener el ID de la duración seleccionada
    $location = mysqli_real_escape_string($link, $_POST['location']);


    // Consultar la duración y el precio del paquete seleccionado
    $duracion_query = "SELECT duracion, precio FROM duraciones_paquete WHERE id = '$duracion_paquete_id'";
    $duracion_result = mysqli_query($link, $duracion_query);
    
    if ($duracion_result && mysqli_num_rows($duracion_result) > 0) {
        $duracion_data = mysqli_fetch_assoc($duracion_result);
        $duracion = $duracion_data['duracion'];
        $precio = $duracion_data['precio'];
    } else {
        $duracion = 0;
        $precio = 0;
    }
    // Si el precio con descuento existe y es válido, usarlo
if (isset($_POST['precio_con_descuento']) && is_numeric($_POST['precio_con_descuento']) && $_POST['precio_con_descuento'] > 0) {
    $precio = mysqli_real_escape_string($link, $_POST['precio_con_descuento']);
}

    // Validar que la fecha de fin sea posterior a la de inicio
    if (strtotime($leaving) <= strtotime($arrivals)) {
        $reservation_success = false;
        $message = "La fecha de fin debe ser posterior a la fecha de inicio.";
    } else {
        // Consulta SQL para insertar la reserva con la duración y el precio seleccionados
        $sql = "INSERT INTO booking (nombre, email, telefono, direccion, numero_personas, fecha_inicio, fecha_fin, usuario_id, duracion, precio, location)
        VALUES ('$name', '$email', '$phone', '$address', '$guest', '$arrivals', '$leaving', '$user_id', '$duracion', '$precio', '$location')";


        // Ejecutar la consulta
        if ($link->query($sql) === TRUE) {
            $reservation_success = true;
            $message = "Reserva realizada correctamente.";
        } else {
            $reservation_success = false;
            $message = "Error al realizar la reserva: " . $link->error;
        }
    }
} else {
    $reservation_success = false;
    $message = "";
}
?>


<!-- swiper css link -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

<!-- font awesome cdn link -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="../css/style.css">

<div class="heading" style="background:url(../images/user-list.jpg) no-repeat">
    <h1><?= htmlspecialchars($package_location ?: 'Reserva ahora') ?></h1>
</div>


<!-- Sección de formulario de reserva -->
<section class="booking">
    <h1 class="heading-title">Reserva tu viaje</h1>

    <?php if (!$reservation_success && !empty($message)): ?>
        <div class="error-message" style="color: red; text-align: center; margin-bottom: 20px;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form action="booking.php" method="post" class="booking-form">
        <div class="flex">
            <!-- Nombre -->
            <div class="inputBox">
                <span>Nombre:</span>
                <input type="text" placeholder="Introduce tu nombre" name="name" value="<?= htmlspecialchars($user_data['usuario']) ?>" required>
            </div>

            <!-- Email -->
            <div class="inputBox">
                <span>Email:</span>
                <input type="email" placeholder="Introduce tu email" name="email" value="<?= htmlspecialchars($user_data['email']) ?>" required>
            </div>

            <!-- Teléfono -->
            <div class="inputBox">
                <span>Teléfono:</span>
                <input type="number" placeholder="Introduce tu teléfono" name="phone" value="<?= htmlspecialchars($user_data['telefono']) ?>" required>
            </div>

            <!-- Dirección -->
            <div class="inputBox">
                <span>Dirección:</span>
                <input type="text" placeholder="Introduce tu dirección" name="address" value="<?= htmlspecialchars($user_data['direccion']) ?>" required>
            </div>

            <!-- Número de personas -->
            <div class="inputBox">
                <span>Número de personas:</span>
                <input type="number" placeholder="Introduce número de personas" name="guest" required>
            </div>
            
            <!-- Selección de paquete (ya mostrado) -->
            <div class="inputBox">
                <span>Paquete seleccionado:</span>
                <?php
                // Obtener el paquete seleccionado
                if (isset($_GET['id'])) {
                    $package_id = $_GET['id'];

                    // Consultar el nombre del paquete
                    $package_query = "SELECT nombre FROM paquetes WHERE id = '$package_id'";
                    $package_result = mysqli_query($link, $package_query);
                    if ($package_result && mysqli_num_rows($package_result) > 0) {
                        $package_data = mysqli_fetch_assoc($package_result);
                        $package_name = $package_data['nombre'];
                    } else {
                        $package_name = "Paquete no encontrado";
                    }
                } else {
                    $package_name = "No se seleccionó ningún paquete";
                }
                ?>
                <input type="text" value="<?= htmlspecialchars($package_name) ?>" disabled>
            </div>
            
            <input type="hidden" name="location" value="<?= htmlspecialchars($package_location) ?>">


            <!-- Selección de duración y precio -->
            <div class="inputBox">
                <span>Duración y precio por persona:</span>
                <select name="duracion_paquete_id" id="duracion_paquete_id" required>
                    <option value="">Selecciona Paquete y precio</option>
                    <?php
                    if (isset($package_id)) {
                        $query = "
                            SELECT dp.id, dp.duracion, dp.precio 
                            FROM duraciones_paquete dp
                            WHERE dp.paquete_id = '$package_id'";

                        $result = mysqli_query($link, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['id']}' data-duration='{$row['duracion']}' data-precio='{$row['precio']}'>Paquete: {$row['duracion']} días - €" . number_format($row['precio'], 2) . "</option>";

                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Fecha de inicio -->
            <div class="inputBox">
                <span>Fecha inicio:</span>
                <input type="date" id="arrivals" name="arrivals" required>
            </div>

            <!-- Fecha de fin -->
            <div class="inputBox">
                <span>Fecha fin:</span>
                <input type="date" id="leaving" name="leaving" required readonly>
            </div>                

            
        </div>

        <input type="hidden" name="precio_con_descuento" id="precio_con_descuento" value="">
       
        <!-- Sección de Pago y Descuento -->
<h2 class="payment-title">Sección de pago</h2>

<section class="payment-section">

  <div class="payment-left">
    
    <div class="inputBox">
      <span>Número de tarjeta:</span>
      <input type="text" id="card-number" placeholder="1234 5678 9012 3456" maxlength="19">
    </div>

    <div class="inputBox">
      <span>Fecha de expiración:</span>
      <input type="text" id="expiry-date" placeholder="MM/AA" maxlength="5">
    </div>

    <div class="inputBox">
      <span>CVV:</span>
      <input type="password" id="cvv" placeholder="123" maxlength="3">
    </div>

    <div class="inputBox">
      <span>Código de descuento:</span>
      <input type="text" id="discount-code" placeholder="Introduce tu código de descuento" value="<?= htmlspecialchars($discount_code) ?>" data-valid-code="<?= htmlspecialchars($discount_code) ?>">
    </div>

    <div class="inputBox">
      <button class="btn" type="button" id="apply-discount" onclick="applyDiscount()">Aplicar descuento</button>
    </div>
  </div>

  <div class="payment-right">
    <div class="inputBox">
      <span>Precio original:</span>
      <input type="text" id="total-price" value="€0.00" disabled>
    </div>

    <div class="inputBox">
      <span>Descuento aplicado:</span>
      <input type="text" id="discount-amount" value="€0.00" disabled>
    </div>

    <div class="inputBox">
      <span>Precio final a pagar:</span>
      <input type="text" id="final-price" value="€0.00" disabled>
    </div>

    <div class="button-container">
    <button class="btn" type="button" id="pay-button" onclick="simulatePayment()">Pagar ahora</button>
<input type="hidden" name="send" value="1">

    </div>
  </div>
</section>



<!-- Footer -->
<?php include('../includes/footer.php'); ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="../js/script.js"></script>
<script>

// Función para cerrar el modal correctamente
function closeModal() {
    document.getElementById('modal-overlay').style.display = 'none';
    document.getElementById('modal-message').style.display = 'none';
}


// Mostrar el modal si la reserva es exitosa
window.addEventListener('load', function() {
    const successFlag = document.body.getAttribute('data-success');
    if (successFlag === 'true') {
        document.getElementById('reservationModal').style.display = 'block';
    }
});
</script>
<?php if ($reservation_success): ?>
<script>
window.addEventListener('load', function () {
    document.getElementById('modal-overlay')?.classList.add('show');
    document.getElementById('modal-message')?.classList.add('show');
});
</script>
<?php endif; ?>

<script>
document.querySelector('.booking-form').addEventListener('submit', function (e) {
    const form = e.target;
    const requiredFields = form.querySelectorAll('[required]');

    for (let field of requiredFields) {
        if (!field.value.trim()) {
            e.preventDefault(); // Detiene el envío del formulario
            field.focus(); // Lleva al usuario al campo vacío
            field.style.border = '2px solid red'; // Marca el campo
            alert('Por favor completa el campo: ' + field.previousElementSibling.textContent.trim());
            return false; // Sale de la función
        } else {
            field.style.border = ''; // Limpia el estilo si está correcto
        }
    }
});
</script>
<!-- Overlay oscuro -->
<div id="modal-overlay" onclick="closeModal()" style="display: none;"></div>

<!-- Contenido del modal -->
<div id="modal-message" style="display: none;">
    <span class="close-button" onclick="closeModal()">&times;</span>
    <h2>¡Reserva realizada con éxito!</h2>
    <p>Tu reserva ha sido realizada con éxito. ¡Gracias por elegirnos!</p>
    <button onclick="submitAfterConfirmation()" class="btn-confirm">Cerrar</button>

</div>

</body>
</html>