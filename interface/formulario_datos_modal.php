<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Include the database connection
include '../conexion.php';

// Validate connection
if ($mysqli->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $mysqli->connect_error]);
    exit;
}

// Get and decode the JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "No data received or invalid JSON."]);
    exit;
}

// Map received data
$sede = $data['sede'] ?? null;
$area = $data['area'] ?? null;
$afiliacion = $data['afiliacion'] ?? null;
$parentesco = $data['parentesco'] ?? null;
$hcNumber = $data['hcNumber'] ?? null;
$tipoAfiliacion = $data['tipoAfiliacion'] ?? null;
$numeroAprobacion = $data['numeroAprobacion'] ?? null;
$tipoPlan = $data['tipoPlan'] ?? null;
$fechaRegistro = $data['fechaRegistro'] ?? null;
$fechaVigencia = $data['fechaVigencia'] ?? null;
$codDerivacion = $data['codDerivacion'] ?? null;
$numSecuencialDerivacion = $data['numSecuencialDerivacion'] ?? null;
$numHistoria = $data['numHistoria'] ?? null;
$examenFisico = $data['examenFisico'] ?? null;
$observacion = $data['observacion'] ?? null;


// Prepare the SQL statement
$sql = "
    INSERT INTO prefactura_paciente (
        sede, area, afiliacion, parentesco, 
        hc_number, tipo_afiliacion, 
        numero_aprobacion, tipo_plan, 
        fecha_registro, fecha_vigencia, 
        cod_derivacion, num_secuencial_derivacion, 
        num_historia, examen_fisico, 
        observaciones
    ) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        sede = VALUES(sede),
        area = VALUES(area), 
        afiliacion = VALUES(afiliacion),
        parentesco = VALUES(parentesco),
        hc_number = VALUES(hc_number),
        tipo_afiliacion = VALUES(tipo_afiliacion),
        numero_aprobacion = VALUES(numero_aprobacion),
        tipo_plan = VALUES(tipo_plan),
        fecha_registro = VALUES(fecha_registro),
        fecha_vigencia = VALUES(fecha_vigencia),
        cod_derivacion = VALUES(cod_derivacion),
        num_secuencial_derivacion = VALUES(num_secuencial_derivacion),
        num_historia = VALUES(num_historia),
        examen_fisico = VALUES(examen_fisico),
        observaciones = VALUES(observaciones)
";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "SQL prepare failed: " . $mysqli->error]);
    exit;
}

// Bind parameters
$stmt->bind_param(
    "sssssssssssssss",
    $sede, $area, $afiliacion, $parentesco,
    $hcNumber, $tipoAfiliacion, $numeroAprobacion, $tipoPlan,
    $fechaRegistro, $fechaVigencia, $codDerivacion, $numSecuencialDerivacion,
    $numHistoria, $examenFisico, $observacion
);

// Execute and handle result
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Datos guardados correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al guardar los datos: " . $stmt->error]);
}

// Clean up
$stmt->close();
$mysqli->close();
?>
