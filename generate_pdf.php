<?php
session_start();

// Asegúrate de que el usuario esté autenticado
if (!isset($_SESSION['user_id'])) {
    die("Acceso denegado. Por favor, inicie sesión.");
}

// Conectarse a la base de datos
require 'conexion.php';

// Verificar conexión
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Obtener el ID del usuario autenticado
$user_id = $_SESSION['user_id'];

// Consulta para verificar si el usuario tiene una suscripción activa y está aprobado
$sql = "SELECT is_subscribed, is_approved FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($is_subscribed, $is_approved);
$stmt->fetch();
$stmt->close();

// Verificar si el usuario tiene acceso
if (!$is_subscribed || !$is_approved) {
    die("No tiene una suscripción activa o no está aprobado para usar esta función.");
}

require 'vendor/autoload.php';

use Mpdf\Mpdf;

// Configuración personalizada de mPDF
$mpdf = new Mpdf([
    'default_font_size' => 8,
    'default_font' => 'dejavusans',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 8,
    'margin_bottom' => 8,
    'orientation' => 'P',
    'shrink_tables_to_fit' => 1,
    'use_kwt' => true,
    'autoScriptToLang' => true,
    'keep_table_proportions' => true
]);

// Incluir el archivo CSS externo
$stylesheet = file_get_contents('styles.css');
$mpdf->WriteHTML($stylesheet, 1);

// Incluir el contenido de las páginas/formularios
ob_start();
include 'page1.php';
$html = ob_get_clean();
$mpdf->WriteHTML($html);
//$mpdf->AddPage();

ob_start();
include 'protocolo.php';
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->AddPage();

// Agrega más páginas según sea necesario
ob_start();
include '005.php';
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->AddPage();

ob_start();
include 'medicamentos.php';
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->AddPage();

ob_start();
include 'saveqx.php';
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->AddPage();

// Mostrar el PDF en la misma página
$mpdf->Output('informacion_paciente.pdf', 'I');

$mysqli->close();
?>
