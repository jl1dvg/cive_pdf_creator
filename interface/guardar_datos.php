<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Incluir el archivo de conexión
include '../conexion.php';

// Obtener los datos enviados desde la extensión
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['hcNumber'])) {
    $hcNumber = $data['hcNumber'];
    $lname = $data['lname'] ?? null;
    $lname2 = $data['lname2'] ?? null;
    $fname = $data['fname'] ?? null;
    $mname = $data['mname'] ?? null;
    $afiliacion = $data['afiliacion'] ?? null;
    $fechaCaducidad = $data['fechaCaducidad'] ?? null;
    $form_id = $data['form_id'];
    $doctor = $data['doctor'] ?? null;
    $procedimiento_proyectado = $data['procedimiento_proyectado'];

    // Insertar o actualizar en patient_data
    $sqlPatient = "
        INSERT INTO patient_data (hc_number, lname, lname2, fname, mname, afiliacion, fecha_caducidad) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            lname = VALUES(lname), 
            lname2 = VALUES(lname2), 
            fname = VALUES(fname), 
            mname = VALUES(mname), 
            afiliacion = VALUES(afiliacion), 
            fecha_caducidad = VALUES(fecha_caducidad)";

    $stmtPatient = $mysqli->prepare($sqlPatient);
    $stmtPatient->bind_param("sssssss", $hcNumber, $lname, $lname2, $fname, $mname, $afiliacion, $fechaCaducidad);

    if ($stmtPatient->execute()) {
        // Insertar o actualizar procedimiento_proyectado
            $sqlProcedimiento = "
                INSERT INTO procedimiento_proyectado (form_id, procedimiento_proyectado, doctor, hc_number) 
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    procedimiento_proyectado = VALUES(procedimiento_proyectado),
                    doctor = VALUES(doctor)";

            $stmtProcedimiento = $mysqli->prepare($sqlProcedimiento);
            $stmtProcedimiento->bind_param("isss", $form_id, $procedimiento_proyectado, $doctor, $hcNumber);

            if ($stmtProcedimiento->execute()) {
                echo json_encode(["success" => true, "message" => "Datos guardados correctamente"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al guardar el procedimiento: " . $stmtProcedimiento->error]);
            }

            $stmtProcedimiento->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar los datos del paciente: " . $stmtPatient->error]);
    }

    $stmtPatient->close();
} else {
    echo json_encode(["success" => false, "message" => "Datos faltantes o incompletos. Verifique hcNumber, form_id, y procedimiento_proyectado."]);
}

$mysqli->close();
?>