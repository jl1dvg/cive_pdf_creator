<?php
session_start();

// Conectar a la base de datos
include('conexion.php');

// Verificar conexión
if ($mysqli->connect_error) {
    die('Error de conexión a la base de datos: ' . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $mysqli->real_escape_string($_POST['username']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Cifrar la contraseña

    // Verificar si el usuario o correo ya existe
    $check_user = $mysqli->query("SELECT id FROM users WHERE username='$username' OR email='$email' LIMIT 1");
    if ($check_user->num_rows > 0) {
        echo 'El usuario o correo ya existe';
    } else {
        // Insertar el nuevo usuario en la base de datos
        $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo 'Usuario registrado exitosamente. Por favor, espera la aprobación.';
            // Redirigir o iniciar sesión el usuario aquí si es necesario
        } else {
            echo 'Error al registrar el usuario: ' . $stmt->error;
        }

        $stmt->close();
    }
}

$mysqli->close();
?>
