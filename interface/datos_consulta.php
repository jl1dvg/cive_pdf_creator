<?php
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Incluir el archivo de conexión
include '../conexion.php';

// Obtener y validar los datos recibidos
$data = json_decode(file_get_contents('php://input'), true);

if ($data === null) {
    echo json_encode(["success" => false, "message" => "JSON mal formado"]);
    exit;
}

if (isset($data['hcNumber'], $data['form_id'], $data['motivoConsulta'])) {
    $hcNumber = $data['hcNumber'];
    $form_id = $data['form_id'];
    $fechaActual = $data['fechaActual'] ?? date('Y-m-d');

    // Datos del paciente
    $fechaNacimiento = $data['fechaNacimiento'] ?? null;
    $sexo = $data['sexo'] ?? null;
    $celular = $data['celular'] ?? null;
    $ciudad = $data['ciudad'] ?? null;

    // SQL para insertar o actualizar los datos del paciente
    $sql = "INSERT INTO patient_data (hc_number, fecha_nacimiento, sexo, celular, ciudad) 
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            fecha_nacimiento = VALUES(fecha_nacimiento),
            sexo = VALUES(sexo), 
            celular = VALUES(celular), 
            ciudad = VALUES(ciudad)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssss", $hcNumber, $fechaNacimiento, $sexo, $celular, $ciudad);

    if ($stmt->execute()) {
        // Datos de la consulta
        $motivoConsulta = $data['motivoConsulta'] ?? null;
        $enfermedadActual = $data['enfermedadActual'] ?? null;
        $examenFisico = $data['examenFisico'] ?? null;
        $plan = $data['plan'] ?? null;

        // SQL para insertar o actualizar los datos de la consulta
        $sqlConsulta = "INSERT INTO consulta_data (
            hc_number, form_id, fecha, motivo_consulta, enfermedad_actual, examen_fisico, plan
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            fecha = VALUES(fecha),
            motivo_consulta = VALUES(motivo_consulta),
            enfermedad_actual = VALUES(enfermedad_actual),
            examen_fisico = VALUES(examen_fisico),
            plan = VALUES(plan)";

        $stmtConsulta = $mysqli->prepare($sqlConsulta);
        $stmtConsulta->bind_param(
            "sssssss",
            $hcNumber, $form_id, $fechaActual, $motivoConsulta, $enfermedadActual, $examenFisico, $plan
        );

        if ($stmtConsulta->execute()) {
            echo json_encode(["success" => true, "message" => "Datos de la consulta guardados correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error en consulta_data: " . $stmtConsulta->error]);
        }

        $stmtConsulta->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error en patient_data: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Datos no válidos o incompletos"]);
}

$mysqli->close();
?>