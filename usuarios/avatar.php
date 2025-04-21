<?php
include('../includes/conexion.php'); // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    $foto_tmp = $_FILES['foto_perfil']['tmp_name'];
    $foto_nombre = basename($_FILES['foto_perfil']['name']);
    $foto_ruta = '../uploads/' . $foto_nombre;

    if (move_uploaded_file($foto_tmp, $foto_ruta)) {
        $mensaje = "Foto subida correctamente.";
        $foto_url = $foto_ruta;

        // ID del usuario (debe estar disponible en la sesión)
        session_start();
        $user_id = $_SESSION['id'];

        // Guardar la ruta en la base de datos
        $query = "UPDATE usuarios SET foto_perfil = '$foto_url' WHERE id = $user_id";
        
        if (mysqli_query($link, $query)) {
            $mensaje .= " La foto se ha guardado en tu perfil.";
        } else {
            $mensaje .= " Error al guardar la ruta en la base de datos: " . mysqli_error($link);
        }
    } else {
        $mensaje = "Error al subir la foto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Foto</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<section class="booking">
    <h1 class="heading-title">Subir Foto de Perfil</h1>

    <form action="avatar.php" method="POST" enctype="multipart/form-data" class="booking-form">
        <div class="preview-container" style="text-align: center; margin-bottom: 1rem;">
            <!-- Mostrar previsualización de la foto -->
            <?php if (!empty($foto_url)): ?>
                <img src="<?= $foto_url; ?>" alt="Foto de perfil" style="max-width: 200px; border-radius: 50%;">
            <?php else: ?>
                <img id="preview" src="#" alt="Previsualización de la foto" style="display: none; max-width: 200px; border-radius: 50%;">
            <?php endif; ?>
        </div>

        <div class="inputBox" style="text-align: center;">
            <span style="font-size: 24px;">Selecciona tu foto:</span>
            <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" onchange="previewImage(event)">
        </div>

        <div style="text-align: center; margin-top: 1rem;">
            <input type="submit" value="Subir Foto" class="btn">
        </div>
        <div style="text-align: center; margin-top: 1rem;">
                <a href="user-form.php" class="btn">Volver al Formulario de Usuario</a>
            </div>
        
    </form>
</section>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };

            reader.readAsDataURL(file);
        } else {
            preview.src = "#";
            preview.style.display = "none";
        }
    }
</script>
</body>
</html>
