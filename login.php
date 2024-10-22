<?php
ini_set('session.save_path', __DIR__ . '/sessions');
session_name('mi_sesion');  // Nombre personalizado para la sesión
session_start();
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $mysqli->query("SELECT id, password FROM users WHERE username='$username' LIMIT 1");

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Guardar la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['test_value'] = 'Sesión iniciada correctamente';
            $_SESSION['session_active'] = true;  // Agregar un indicador de control

            session_write_close();
            // Redirigir pasando el ID de sesión en la URL
            header('Location: /main/main.php?PHPSESSID=' . session_id());
            exit();
        } else {
            echo 'Contraseña incorrecta';
        }
    } else {
        echo 'Usuario no encontrado';
    }
}
?>