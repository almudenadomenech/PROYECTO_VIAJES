<?php include('../includes/navbar.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>

    <!-- swiper css link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<!-- Encabezado -->
<section class="heading-user-list" style="background:url(../images/user-list.jpg) no-repeat">
    <h1>Lista de Usuarios Registrados</h1>
</section>
<?php


    // Obtener el rol del usuario
    $role_id = $_SESSION['role_id'];
    $usuario_id = $_SESSION['id'];

    // Incluir archivo de conexión a la base de datos
    require_once "../includes/conexion.php";

    // Eliminar la reserva si se ha enviado el ID
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Consulta SQL para eliminar la reserva
        $sql = "DELETE FROM booking WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Reserva eliminada con éxito.";
            } else {
                $mensaje = "Error al eliminar la reserva.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $mensaje = "Error en la consulta.";
        }
    }

    // Consulta para obtener las reservas
    if ($role_id == 2) {
        // Si es administrador, obtener todas las reservas
        $sql = "SELECT b.id, b.nombre, b.email, b.location, b.numero_personas, b.fecha_inicio, b.fecha_fin
                FROM booking b
                INNER JOIN usuarios u ON b.usuario_id = u.id";
    } else {
        // Si es usuario, obtener solo sus reservas
        $sql = "SELECT b.id, b.nombre, b.email, b.location, b.numero_personas, b.fecha_inicio, b.fecha_fin
                FROM booking b
                WHERE b.usuario_id = $usuario_id";
    }

    $result = mysqli_query($link, $sql);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        die("Error al obtener las reservas: " . mysqli_error($link));
    }
?>



<div class="container">
    <h1>Listado de Reservas</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Destino</th>
                <th>Personas</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar cada reserva
            while ($row = mysqli_fetch_assoc($result)) {
                
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['numero_personas']}</td>
                        <td>{$row['fecha_inicio']}</td>
                        <td>{$row['fecha_fin']}</td>
                        
                        <td>
                            <!-- Botón de eliminación -->
                            <button class='btn-eliminar' onclick='openModal({$row['id']})'>Eliminar</button>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>

<!-- Modal de confirmación -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>¿Estás seguro de que deseas eliminar esta reserva?</h3>
        <form id="deleteForm" method="POST">
            <input type="hidden" name="id" id="bookingId">
            <button type="submit" class="btn-confirm">Sí, Eliminar</button>
            <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
        </form>
    </div>
</div>

<!-- Modal de éxito -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeSuccessModal()">&times;</span>
        <h3>La reserva se ha eliminado con éxito.</h3>
    </div>
</div>



<script>
    // Función para abrir el modal de confirmación
    function openModal(id) {
        document.getElementById("bookingId").value = id;
        document.getElementById("confirmModal").style.display = "block";
    }

    // Función para cerrar el modal de confirmación
    function closeModal() {
        document.getElementById("confirmModal").style.display = "none";
    }

    // Función para cerrar el modal de éxito
    function closeSuccessModal() {
        document.getElementById("successModal").style.display = "none";
    }

    // Verificar si la reserva se eliminó con éxito
    <?php if (isset($mensaje) && $mensaje == "Reserva eliminada con éxito."): ?>
        document.getElementById("successModal").style.display = "block";
    <?php endif; ?>


</script>
<!-- swiper js link  -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="../js/script.js"></script>

</body>
</html>
