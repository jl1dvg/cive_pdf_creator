<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Incluir el archivo de conexión
include '../conexion.php';

// Obtener los datos enviados desde la extensión
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['hcNumber'])) {
    // Mapear los datos recibidos
    $hcNumber = $data['hcNumber'];
    $lname = $data['lname'] ?? null;
    $lname2 = $data['lname2'] ?? null;
    $fname = $data['fname'] ?? null;
    $mname = $data['mname'] ?? null;
    $fechaNacimiento = $data['fechaNacimiento'] ?? null;
    $sexo = $data['sexo'] ?? null;
    $telefonoMovil = $data['telefonoMovil'] ?? null;
    $email = $data['email'] ?? null;
    $direccion = $data['direccion'] ?? null;
    $ocupacion = $data['ocupacion'] ?? null;
    $lugarTrabajo = $data['lugarTrabajo'] ?? null;
    $ciudad = $data['ciudad'] ?? null;
    $parroquia = $data['parroquia'] ?? null;
    $nacionalidad = $data['nacionalidad'] ?? null;
    $estadoCivil = $data['estadoCivil'] ?? null;
    $idProcedencia = $data['idProcedencia'] ?? null;
    $idReferido = $data['idReferido'] ?? null;

    // Insertar o actualizar en patient_data
    $sqlPatient = "
        INSERT INTO patient_data (
            hc_number, lname, lname2, fname, mname, 
            fecha_nacimiento, sexo, celular, email, direccion, 
            ocupacion, lugar_trabajo, ciudad, parroquia, nacionalidad, 
            estado_civil, id_procedencia, id_referido
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            lname = VALUES(lname), 
            lname2 = VALUES(lname2), 
            fname = VALUES(fname), 
            mname = VALUES(mname), 
            fecha_nacimiento = VALUES(fecha_nacimiento), 
            sexo = VALUES(sexo), 
            celular = VALUES(celular), 
            email = VALUES(email), 
            direccion = VALUES(direccion), 
            ocupacion = VALUES(ocupacion), 
            lugar_trabajo = VALUES(lugar_trabajo), 
            ciudad = VALUES(ciudad), 
            parroquia = VALUES(parroquia), 
            nacionalidad = VALUES(nacionalidad), 
            estado_civil = VALUES(estado_civil), 
            id_procedencia = VALUES(id_procedencia), 
            id_referido = VALUES(id_referido)";

    $stmtPatient = $mysqli->prepare($sqlPatient);
    $stmtPatient->bind_param(
        "ssssssssssssssssss",
        $hcNumber, $lname, $lname2, $fname, $mname,
        $fechaNacimiento, $sexo, $telefonoMovil, $email, $direccion,
        $ocupacion, $lugarTrabajo, $ciudad, $parroquia, $nacionalidad,
        $estadoCivil, $idProcedencia, $idReferido
    );

    if ($stmtPatient->execute()) {
        echo json_encode(["success" => true, "message" => "Datos guardados correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar los datos: " . $stmtPatient->error]);
    }

    $stmtPatient->close();
} else {
    echo json_encode(["success" => false, "message" => "Datos faltantes o incompletos. Verifique hcNumber."]);
}

$mysqli->close();
?>