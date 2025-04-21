<?php
// INICIALIZAR SESION
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('location: ../home.php');
    exit;
}

require_once "../includes/conexion.php"; // Asegúrate de que este archivo define $link

$email = $password = "";
$email_error = $password_error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (empty(trim($_POST['email']))) {
        $email_error = "Por favor, introduzca el email";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST['password']))) {
        $password_error = "Por favor, introduzca una contraseña";
    } else {
        $password = trim($_POST["password"]);
    }

    // VALIDAR CREDENCIALES
    if (empty($email_error) && empty($password_error)) { // CORREGIDO
        $sql = "SELECT id, usuario, email, contraseña, role_id, foto_perfil FROM usuarios WHERE email = ?";
// Añadir role_id a la consulta

        if ($stmt = mysqli_prepare($link, $sql)) { // VERIFICAR QUE $stmt SE PREPARE CORRECTAMENTE
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $usuario, $email, $hashed_password, $role_id, $foto_perfil);
                    // Añadir role_id a la recuperación de datos
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            // ALMACENAR LOS DATOS EN VARIABLES DE SESION
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["role_id"] = $role_id; // Almacenar role_id en la sesión
                            $_SESSION["foto_perfil"] = $foto_perfil;

                            header("location: ../home.php");
                            exit;
                        } else {
                            $password_error = "La contraseña que has introducido no es válida";
                        }
                    }
                } else {
                    $email_error = "No se ha encontrado ninguna cuenta con ese email";
                }
            } else {
                echo "Error en la ejecución de la consulta.";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Error al preparar la consulta.";
        }
    }

    mysqli_close($link);
}
?>
