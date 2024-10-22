<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirige al login
    header('Location: /main/auth_login.html');
    exit();
} else {
    // Si está autenticado, redirige al index en la carpeta main
    header('Location: /main/main.html');
    exit();
}
?>