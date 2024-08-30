<?php
header("Access-Control-Allow-Origin: chrome-extension://khgclpbieihekpkippgeeilnnfjbghpd");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true"); // Esto permite enviar cookies y cabeceras HTTP de autenticación
header('Content-Type: application/json');

session_start();
require 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

$user_id = $_SESSION['user_id'];

$result = $mysqli->query("SELECT is_subscribed, is_approved FROM users WHERE id='$user_id'");

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    echo json_encode([
        'isSubscribed' => (bool)$user['is_subscribed'],
        'isApproved' => (bool)$user['is_approved']
    ]);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}
?>