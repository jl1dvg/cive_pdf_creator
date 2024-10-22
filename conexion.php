<?php
// Datos de configuración de la base de datos
$host = 'db5016222976.hosting-data.io';  // Puede ser 'localhost' o la IP/URL de tu servidor de base de datos
$db = 'dbs13202800';  // Reemplaza con el nombre de tu base de datos
$user = 'dbu365135';  // Reemplaza con tu usuario de base de datos
$pass = 'JorgeAMI2018';  // Reemplaza con tu contraseña de base de datos

// Crear la conexión
$mysqli = new mysqli($host, $user, $pass, $db);

// Verificar la conexión
if ($mysqli->connect_error) {
    die("Error en la conexión a la base de datos: " . $mysqli->connect_error);
}
?>
