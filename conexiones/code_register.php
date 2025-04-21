<?php
// Incluir archivo de conexión a la base de datos
require_once "../includes/conexion.php";

// Definir variables e inicializar con valores vacios
$username = $email = $password = "";
$username_error = $email_error = $password_error = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){

    // VALIDANDO INPUT DE NOMBRE DE USUARIO
    if(empty(trim($_POST['username']))){
        $username_error = "Por favor, introduzca un nombre de usuario";
    }else{
        // prepara una declaración de selección
        $sql = "SELECT id FROM usuarios WHERE usuario = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = trim($_POST['username']);

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_error = "Este nombre de usuario ya existe";
                }else{
                    $username = trim($_POST['username']);
                }
            }else{
                echo "Ups!, Algo ha salido mal, inténtalo más tarde";
            }
        }
    }

    // VALIDANDO INPUT DE EMAIL
    if(empty(trim($_POST['email']))){
        $email_error = "Por favor, introduzca un email";
    }else{
        // prepara una declaración de selección
        $sql = "SELECT id FROM usuarios WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            $param_email = trim($_POST['email']);

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt)==1){
                    $email_error == "El email ya existe";
                }else{
                    $email = trim($_POST['email']);
                }
            }else{
                echo "Ups!, Algo ha salido mal, inténtalo más tarde";
            }
        }
    }

    // VALIDANDO INPUT CONTRASEÑA

    if(empty(trim($_POST['password']))){
        $password_error = "Por favor, introduzca una contraseña";
    }elseif(strlen(trim($_POST['password'])) < 4){
        $password_error = "La contraseña debe de tener al menos 4 caracteres";
    } else{
        $password = trim($_POST['password']);
    }
    
    //COMPROBANDO LOS ERRORES DE LAS VARIABLES DE ENTRADA ANTES DE INSERTAR LOS DATOS EN LA BASE DE DATOS
    if(empty($username_error) && empty($email_error) && empty($password_error)){

        $sql = "INSERT INTO usuarios (usuario, email, contraseña) VALUE (?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

            //ESTABLECIENDO PARÁMETROS
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // ENCRIPTANDO CONTRASEÑA

            if(mysqli_stmt_execute($stmt)){
                header('location:../paginas/login.php');
            }else{
                echo "Algo salió mal";
            }
        }
    }

    mysqli_close($link);
}



?>