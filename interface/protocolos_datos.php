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

if (isset($data['hcNumber'], $data['form_id'])) {
    $hcNumber = $data['hcNumber'];
    $form_id = $data['form_id'];
    $fechaActual = $data['fechaActual'] ?? date('Y-m-d');

// Datos del paciente
    $fechaNacimiento = $data['fechaNacimiento'] ?? null;
    $sexo = $data['sexo'] ?? null;
    $celular = $data['celular'] ?? null;
    $ciudad = $data['ciudad'] ?? null;

    // SQL para insertar o actualizar `patient_data`
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
        // Datos del protocolo
        $cirujano_1 = $data['cirujano_1'] ?? null;
        $instrumentista = $data['instrumentista'] ?? null;
        $cirujano_2 = $data['cirujano_2'] ?? null;
        $circulante = $data['circulante'] ?? null;
        $primer_ayudante = $data['primer_ayudante'] ?? null;
        $anestesiologo = $data['anestesiologo'] ?? null;
        $segundo_ayudante = $data['segundo_ayudante'] ?? null;
        $ayudante_anestesia = $data['ayudante_anestesiologo'] ?? null;
        $tercer_ayudante = $data['tercer_ayudante'] ?? null;
        $otros = $data['otros'] ?? null;

        // Campos adicionales
        $membrete = $data['membrete'] ?? null;
        $dieresis = $data['dieresis'] ?? null;
        $exposicion = $data['exposicion'] ?? null;
        $hallazgo = $data['hallazgo'] ?? null;
        $operatorio = $data['operatorio'] ?? null;
        $complicaciones_operatorio = $data['complicaciones_operatorio'] ?? null;
        $datos_cirugia = $data['datos_cirugia'] ?? null;
        $procedimientos = json_encode($data['procedimientos']);
        $lateralidad = $data['lateralidad'] ?? null;

        $fechaInicio = $data['fechaInicio'] ?? null;
        $horaInicio = $data['horaInicio'] ?? null;
        $fechaFin = $data['fechaFin'] ?? null;
        $horaFin = $data['horaFin'] ?? null;
        $tipoAnestesia = $data['tipoAnestesia'] ?? null;
        $diagnosticos = json_encode($data['diagnosticos']);

        // SQL para insertar o actualizar `protocolo_data`
        $sqlProtocolo = "INSERT INTO protocolo_data (
            hc_number, form_id, fecha, cirujano_1, instrumentista, cirujano_2, circulante, primer_ayudante, anestesiologo,
            segundo_ayudante, ayudante_anestesia, tercer_ayudante, otros, membrete, dieresis, exposicion, hallazgo, operatorio,
            complicaciones_operatorio, datos_cirugia, fecha_inicio, hora_inicio, fecha_fin, hora_fin, tipo_anestesia, 
            procedimientos, lateralidad, diagnosticos
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                fecha = VALUES(fecha),
                cirujano_1 = VALUES(cirujano_1),
                instrumentista = VALUES(instrumentista),
                cirujano_2 = VALUES(cirujano_2),
                circulante = VALUES(circulante),
                primer_ayudante = VALUES(primer_ayudante),
                anestesiologo = VALUES(anestesiologo),
                segundo_ayudante = VALUES(segundo_ayudante),
                ayudante_anestesia = VALUES(ayudante_anestesia),
                tercer_ayudante = VALUES(tercer_ayudante),
                otros = VALUES(otros),
                membrete = VALUES(membrete),
                dieresis = VALUES(dieresis),
                exposicion = VALUES(exposicion),
                hallazgo = VALUES(hallazgo),
                operatorio = VALUES(operatorio),
                complicaciones_operatorio = VALUES(complicaciones_operatorio),
                datos_cirugia = VALUES(datos_cirugia),
                fecha_inicio = VALUES(fecha_inicio),
                hora_inicio = VALUES(hora_inicio),
                fecha_fin = VALUES(fecha_fin),
                hora_fin = VALUES(hora_fin),
                tipo_anestesia = VALUES(tipo_anestesia),
                procedimientos = VALUES(procedimientos), 
                lateralidad = VALUES(lateralidad), 
                diagnosticos = VALUES(diagnosticos)";

        $stmtProtocolo = $mysqli->prepare($sqlProtocolo);
        $stmtProtocolo->bind_param(
            "ssssssssssssssssssssssssssss",
            $hcNumber, $form_id, $fechaActual, $cirujano_1, $instrumentista, $cirujano_2, $circulante, $primer_ayudante,
            $anestesiologo, $segundo_ayudante, $ayudante_anestesia, $tercer_ayudante, $otros, $membrete, $dieresis, $exposicion,
            $hallazgo, $operatorio, $complicaciones_operatorio, $datos_cirugia, $fechaInicio, $horaInicio, $fechaFin, $horaFin,
            $tipoAnestesia, $procedimientos, $lateralidad, $diagnosticos
        );

        if ($stmtProtocolo->execute()) {
            echo json_encode(["success" => true, "message" => "Datos guardados correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error en protocolo_data: " . $stmtProtocolo->error]);
        }

        $stmtProtocolo->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error en patient_data: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Datos no válidos"]);
}

$mysqli->close();
?>