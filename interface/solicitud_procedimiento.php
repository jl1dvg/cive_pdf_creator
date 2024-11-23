<?php
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Incluir el archivo de conexión a la base de datos
include '../conexion.php';

// Obtener y validar los datos recibidos
$data = json_decode(file_get_contents('php://input'), true);

if ($data === null || !isset($data['hcNumber'], $data['form_id'], $data['solicitudes'])) {
    echo json_encode(["success" => false, "message" => "Datos no válidos o incompletos"]);
    exit;
}

$hcNumber = $data['hcNumber'];
$form_id = $data['form_id'];

// Preparar la consulta para insertar o actualizar cada solicitud
$sql = "INSERT INTO solicitud_procedimiento 
        (hc_number, form_id, secuencia, tipo, afiliacion, procedimiento, doctor, fecha, duracion, ojo, prioridad, producto, observacion) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            tipo = VALUES(tipo),
            afiliacion = VALUES(afiliacion),
            procedimiento = VALUES(procedimiento),
            doctor = VALUES(doctor),
            fecha = VALUES(fecha),
            duracion = VALUES(duracion),
            ojo = VALUES(ojo),
            prioridad = VALUES(prioridad),
            producto = VALUES(producto),
            observacion = VALUES(observacion)";

$stmt = $mysqli->prepare($sql);

// Verificar si la preparación fue exitosa
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error en la preparación: " . $mysqli->error]);
    exit;
}

// Recorrer las solicitudes y guardarlas o actualizarlas en la base de datos
foreach ($data['solicitudes'] as $solicitud) {
    $secuencia = $solicitud['secuencia'] ?? null; // Ahora la secuencia viene del JSON
    $tipo = $solicitud['tipo'] ?? null;
    $afiliacion = $solicitud['afiliacion'] ?? null;
    $procedimiento = $solicitud['procedimiento'] ?? null;
    $doctor = $solicitud['doctor'] ?? null;
    $fecha = $solicitud['fecha'] ?? null;
    $duracion = $solicitud['duracion'] ?? null;
    $ojo = $solicitud['ojo'] ?? null;
    $prioridad = $solicitud['prioridad'] ?? null;
    $producto = $solicitud['producto'] ?? null;
    $observacion = $solicitud['observacion'] ?? null;

    // Bindear los parámetros
    $stmt->bind_param(
    "ssissssssssss",
        $hcNumber,
        $form_id,
        $secuencia, // secuencia desde el JSON
        $tipo,
        $afiliacion,
        $procedimiento,
        $doctor,
        $fecha,
        $duracion,
        $ojo,
        $prioridad,
        $producto,
        $observacion
    );

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Error al guardar solicitud: " . $stmt->error]);
        exit;
    }
}

// Cerrar la consulta preparada
$stmt->close();

// Enviar respuesta de éxito
echo json_encode(["success" => true, "message" => "Solicitudes guardadas o actualizadas correctamente"]);
$mysqli->close();
?>