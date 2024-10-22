<?php
ini_set('session.save_path', __DIR__ . '/../sessions');
session_name('mi_sesion');
session_start();

if (!isset($_SESSION['test'])) {
    $_SESSION['test'] = 'Session is working';
}

echo 'Session ID: ' . session_id() . '<br>';
echo 'Session Variables: ';
var_dump($_SESSION);
?>