<?php
// Configuración de encabezados para permitir CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Incluir la conexión a la base de datos
include '../conexion.php';

// Obtener y decodificar la solicitud JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validar los datos recibidos
if (!isset($data['hcNumber']) || !isset($data['form_id'])) {
    echo json_encode(["success" => false, "message" => "Parámetros insuficientes"]);
    exit;
}

$hcNumber = $data['hcNumber'];
$form_id = $data['form_id'];

// Consulta para obtener los insumos de protocolo_data
$sql = "SELECT insumos FROM protocolo_data WHERE hc_number = ? AND form_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $hcNumber, $form_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Decodificar el JSON de insumos si existe
    $insumos = json_decode($row['insumos'], true);

    echo json_encode([
        "success" => true,
        "message" => "Insumos encontrados",
        "insumos" => $insumos
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No se encontraron insumos para este paciente"
    ]);
}

// Cerrar la conexión
$stmt->close();
$mysqli->close();
?>