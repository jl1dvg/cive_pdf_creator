<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include '../conexion.php';

if ($mysqli->connect_error) {
    error_log("Database connection failed: " . $mysqli->connect_error);
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON decode error: " . json_last_error_msg());
    echo json_encode(["success" => false, "message" => "Invalid JSON input"]);
    exit;
}

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
$procedimientos = $data['procedimientos'] ? json_encode($data['procedimientos']) : null;
$diagnosticos = $data['diagnosticos'] ? json_encode($data['diagnosticos']) : null;

$sql = "
    INSERT INTO prefactura_paciente (
        sede, area, afiliacion, parentesco, 
        hc_number, tipo_afiliacion, 
        numero_aprobacion, tipo_plan, 
        fecha_registro, fecha_vigencia, 
        cod_derivacion, num_secuencial_derivacion, 
        num_historia, examen_fisico, 
        observaciones, procedimientos, diagnosticos
    ) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
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
        observaciones = VALUES(observaciones),
        procedimientos = VALUES(procedimientos),
        diagnosticos = VALUES(diagnosticos)
";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    error_log("SQL prepare failed: " . $mysqli->error);
    echo json_encode(["success" => false, "message" => "SQL prepare failed"]);
    exit;
}

$stmt->bind_param(
    "sssssssssssssssss",
    $sede, $area, $afiliacion, $parentesco,
    $hcNumber, $tipoAfiliacion, $numeroAprobacion, $tipoPlan,
    $fechaRegistro, $fechaVigencia, $codDerivacion, $numSecuencialDerivacion,
    $numHistoria, $examenFisico, $observacion, $procedimientos, $diagnosticos
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Datos guardados correctamente."]);
} else {
    error_log("Error en la ejecución: " . $stmt->error);
    echo json_encode(["success" => false, "message" => "Error al guardar los datos"]);
}

$stmt->close();
$mysqli->close();
?>
