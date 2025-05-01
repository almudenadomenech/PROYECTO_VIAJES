<?php
include('../includes/conexion.php');
include('../includes/navbar.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['role_id'] != 2) {
    header('Location: ../login.php');
    exit;
}

$id = '';
$nombre = '';
$description = '';
$image = '';
$location = '';
$availability = '';
$category = '';
$transporte = 0;
$alojamiento = 0;
$comidas = 0;
$guia = 0;
$excursiones = 0;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM paquetes WHERE id = $id";
    $result = mysqli_query($link, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $p = mysqli_fetch_assoc($result);
        extract($p);
    }

    $duraciones = [];
    $duraciones_query = "SELECT duracion, precio FROM duraciones_paquete WHERE paquete_id = $id";
    $duraciones_result = mysqli_query($link, $duraciones_query);
    while ($row = mysqli_fetch_assoc($duraciones_result)) {
        $duraciones[] = $row;
    }

    $imagenes_galeria = [];
    $galeria_query = "SELECT ruta_imagen FROM imagenes_paquete WHERE paquete_id = $id";
    $galeria_result = mysqli_query($link, $galeria_query);
    while ($row = mysqli_fetch_assoc($galeria_result)) {
        $imagenes_galeria[] = $row['ruta_imagen'];
    }
} else {
    $duraciones = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $id = $_POST['id'] ?? '';
    $nombre = mysqli_real_escape_string($link, $_POST['nombre']);
    $description = mysqli_real_escape_string($link, $_POST['description']);
    $location = mysqli_real_escape_string($link, $_POST['location']);
    $category = mysqli_real_escape_string($link, $_POST['category']);
    $contenido_paquete = mysqli_real_escape_string($link, $_POST['contenido_paquete']);
    $availability = isset($_POST['availability']) ? 1 : 0;
    $transporte = isset($_POST['transporte']) ? 1 : 0;
    $alojamiento = isset($_POST['alojamiento']) ? 1 : 0;
    $comidas = isset($_POST['comidas']) ? 1 : 0;
    $guia = isset($_POST['guia']) ? 1 : 0;
    $excursiones = isset($_POST['excursiones']) ? 1 : 0;


  // Imagen principal (asigna 'viaje-default.png' si no se sube ninguna)
if (!empty($_FILES['image']['name'])) {
    $img_name = basename($_FILES['image']['name']);
    $target = "../images/" . $img_name;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $image = $img_name;
    } else {
        $image = 'viaje-default.png'; // fallback si falla la subida
    }
} elseif (empty($id)) {
    // Si es un nuevo paquete y no se ha subido imagen, usar la default
    $image = 'viaje-default.png';
}


    // Inserción o actualización del paquete con imagen principal
    if (!empty($id)) {
        $sql = "UPDATE paquetes SET nombre='$nombre', description='$description',
        image='$image', location='$location', availability='$availability',
        category='$category', contenido_paquete='$contenido_paquete',
        transporte=$transporte, alojamiento=$alojamiento, comidas=$comidas,
        guia=$guia, excursiones=$excursiones WHERE id=$id";
    } else {
        $sql = "INSERT INTO paquetes (nombre, description, image, location, availability, category, contenido_paquete, transporte, alojamiento, comidas, guia, excursiones)
        VALUES ('$nombre', '$description', '$image', '$location', '$availability', '$category', '$contenido_paquete', $transporte, $alojamiento, $comidas, $guia, $excursiones)";
    }

    // Ejecutar inserción o actualización
    if (mysqli_query($link, $sql)) {
        if (empty($id)) {
            $id = mysqli_insert_id($link);
        }
    // ✅ GUARDAR DURACIONES Y PRECIOS
    // Borrar duraciones anteriores si es edición
    if (!empty($_POST['id'])) {
        mysqli_query($link, "DELETE FROM duraciones_paquete WHERE paquete_id = $id");
    }

    // Insertar nuevas duraciones
    for ($i = 1; $i <= 3; $i++) {
        $duracion = isset($_POST["duracion_$i"]) ? (int)$_POST["duracion_$i"] : 0;
        $precio = isset($_POST["precio_$i"]) ? (float)$_POST["precio_$i"] : 0;

        if ($duracion > 0 && $precio > 0) {
            $insert_duracion = "INSERT INTO duraciones_paquete (paquete_id, duracion, precio) VALUES ($id, $duracion, $precio)";
            mysqli_query($link, $insert_duracion);
        }
    }

        // ✅ Galería de imágenes
        if (!empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['name'] as $index => $fileName) {
                $tmpName = $_FILES['gallery_images']['tmp_name'][$index];
                $imgName = basename($fileName);
                $targetPath = "../images/" . $imgName;
                move_uploaded_file($tmpName, $targetPath);

                $insert_gallery = "INSERT INTO imagenes_paquete (paquete_id, ruta_imagen) VALUES ($id, '$imgName')";
                mysqli_query($link, $insert_gallery);
            }
        }

        header('Location: paquetes.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($link);
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Editar Paquete' : 'Nuevo Paquete' ?></title>
    <!-- swiper css link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="heading" style="background:url(../images/header-2.jpg) no-repeat">
        <h1><?= $id ? 'Editar Paquete' : 'Nuevo Paquete' ?></h1>
    </div>

    <section class="form-admin-container">
        <form method="post" class="form-admin" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

            <!-- Fila 1: Nombre, Ubicación, Categoría -->
            <div class="inputBox">
                <span>Nombre</span>
                <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
            </div>

            <div class="inputBox">
                <span>Ubicación</span>
                <input type="text" name="location" value="<?= htmlspecialchars($location) ?>" required>
            </div>

            <div class="inputBox">
                <span>Categoría</span>
                <select name="category" required>
                    <option value="">Selecciona una categoría</option>
                    <option value="Playa" <?= $category == 'Playa' ? 'selected' : '' ?>>Playa</option>
                    <option value="Montaña" <?= $category == 'Montaña' ? 'selected' : '' ?>>Montaña</option>
                    <option value="Aventura" <?= $category == 'Aventura' ? 'selected' : '' ?>>Aventura</option>
                    <option value="Cultural" <?= $category == 'Cultural' ? 'selected' : '' ?>>Cultural</option>
                </select>
            </div>

            <!-- Contenedor de paquetes -->
            <!-- Duraciones y precios en filas separadas -->

            <div class="inputBox-paquetes">
                <span class="paquete-title">Paquete 1</span>
                <span>Días</span>
                <input type="number" name="duracion_1" value="<?= htmlspecialchars($duraciones[0]['duracion'] ?? 7) ?>">
                <span>Precio</span>
                <input type="number" step="0.01" name="precio_1" value="<?= htmlspecialchars($duraciones[0]['precio'] ?? '') ?>">
            </div>

            <div class="inputBox-paquetes">
                <span class="paquete-title">Paquete 2</span>
                <span>Dias</span>
                <input type="number" name="duracion_2" value="<?= htmlspecialchars($duraciones[1]['duracion'] ?? 10) ?>">
                <span>Precio</span>
                <input type="number" step="0.01" name="precio_2" value="<?= htmlspecialchars($duraciones[1]['precio'] ?? '') ?>">
            </div>

            <div class="inputBox-paquetes">
                <span class="paquete-title">Paquete 3</span>
                <span>Días</span>
                <input type="number" name="duracion_3" value="<?= htmlspecialchars($duraciones[2]['duracion'] ?? 15) ?>">
                <span>Precio</span>
                <input type="number" step="0.01" name="precio_3" value="<?= htmlspecialchars($duraciones[2]['precio'] ?? '') ?>">
            </div>

            <!-- Descripción ocupa 2 columnas y 2 filas -->
            <div class="inputBox descripcion">
                <span>Descripción</span>
                <textarea name="description"><?= htmlspecialchars($description) ?></textarea>
            </div>


            <!-- Imagen principal -->
            <div class="inputBox">
                <span>Imagen Principal</span>
                <input type="file" name="image" accept="image/*" onchange="previewMainImage(event)">
                <div id="main-image-preview">
                    <?php if (!empty($image)): ?>
                        <img src="../images/<?= htmlspecialchars($image) ?>" style="width: 150px; height: auto;">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Galería de imágenes -->
            <div class="inputBox">
                <span>Galería de Imágenes</span>
                <input type="file" name="gallery_images[]" accept="image/*" multiple onchange="previewGalleryImages(event)">
                <div id="gallery-preview"></div>
                <?php if (!empty($imagenes_galeria)): ?>
                    <div id="existing-gallery">
                        <span>Imágenes Actuales:</span><br>
                        <?php foreach ($imagenes_galeria as $img): ?>
                            <img src="../images/<?= htmlspecialchars($img) ?>" style="width: 100px; margin: 5px; border: 1px solid #ccc;">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>




            <!-- Incluye -->
            <div class="inputBox" style="grid-column: span 3;">
                <span>Incluye</span>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <label class="form-check-label" for="transporte">Transporte</label>
                                    <input class="form-check-input" type="checkbox" name="transporte" id="transporte" <?= $transporte ? 'checked' : '' ?>>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <label class="form-check-label" for="alojamiento"> Alojamiento </label>
                                    <input class="form-check-input" type="checkbox" name="alojamiento" id="alojamiento" <?= $alojamiento ? 'checked' : '' ?>>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <label class="form-check-label" for="comidas"> Comidas </label>
                                    <input class="form-check-input" type="checkbox" name="comidas" id="comidas" <?= $comidas ? 'checked' : '' ?>>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <label class="form-check-label" for="guia">Guía</label>
                                    <input class="form-check-input" type="checkbox" name="guia" id="guia" <?= $guia ? 'checked' : '' ?>>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <label class="form-check-label" for="excursiones"> Excursiones</label>
                                    <input class="form-check-input" type="checkbox" name="excursiones" id="excursiones" <?= $excursiones ? 'checked' : '' ?>>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Descripción del contenido del paquete -->
            <div class="inputBox" style="grid-column: span 3;">
                <span>Descripción del Contenido del Paquete</span>
                <textarea name="contenido_paquete"><?= htmlspecialchars($contenido_paquete ?? '') ?></textarea>
            </div>

            <!-- Disponibilidad en formato de tabla -->
            <div class="inputBox" style="grid-column: span 3;">
                <span>Disponibilidad</span>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <div class="form-check" style="text-align: left;">
                                    <label class="form-check-label" for="availability">Disponible</label>
                                    <input class="form-check-input" type="checkbox" name="availability" id="availability" <?= $availability ? 'checked' : '' ?>>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn"><?= $id ? 'Actualizar' : 'Guardar' ?></button>
        </form>
    </section>

    <script>
        function previewMainImage(event) {
            const container = document.getElementById('main-image-preview');
            container.innerHTML = '';
            const img = document.createElement('img');
            img.src = URL.createObjectURL(event.target.files[0]);
            img.style.width = '150px';
            container.appendChild(img);
        }

        function previewGalleryImages(event) {
            const container = document.getElementById('gallery-preview');
            container.innerHTML = '';
            const files = event.target.files;

            for (let i = 0; i < files.length; i++) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(files[i]);
                img.style.width = '100px';
                img.style.margin = '5px';
                container.appendChild(img);
            }
        }
    </script>


    <?php include('../includes/footer.php'); ?>
</body>

</html>