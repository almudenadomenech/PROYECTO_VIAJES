<?php

// Verificar si las constantes ya están definidas antes de definirlas
if (!defined('DB_SERVER')) {
    define('DB_SERVER', 'localhost:3306');
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', 'admin');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'login');
}

// Establecer la conexión
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar si la conexión fue exitosa
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
