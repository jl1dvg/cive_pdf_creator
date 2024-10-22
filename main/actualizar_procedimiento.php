<?php
// Configuración de la sesión y conexión a la base de datos
ini_set('session.save_path', __DIR__ . '/../sessions');
session_name('mi_sesion');
session_start();
error_reporting(E_ALL); // Mostrar todos los errores
ini_set('display_errors', 1); // Habilitar la visualización de errores
ini_set('log_errors', 1); // Habilitar el registro de errores en un archivo
ini_set('error_log', __DIR__ . '/php_error.log'); // Especificar un archivo de registro de errores
require '../conexion.php';  // Incluir conexión a la base de datos

header('Content-Type: application/json');  // Asegurarnos de que siempre devolvemos JSON

// Depuración: registrar los datos recibidos del formulario en un archivo
file_put_contents('debug_post_data.txt', print_r($_POST, true));

// Validar que se reciban los datos obligatorios
if (empty($_POST['form_id']) || empty($_POST['hc_number'])) {
    file_put_contents('error_log.txt', "Faltan datos obligatorios: form_id o hc_number" . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios: form_id o hc_number."]);
    exit;
}

// Obtener los datos enviados por el formulario y validarlos
$form_id = $_POST['form_id'];
$hc_number = $_POST['hc_number'];
$fname = $_POST['fname'] ?? null;
$mname = $_POST['mname'] ?? null;
$lname = $_POST['lname'] ?? null;
$lname2 = $_POST['lname2'] ?? null;
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
$afiliacion = $_POST['afiliacion'] ?? null;  // Nota: este campo está desactivado para edición
$lateralidad = $_POST['lateralidad'] ?? null;

// Validar procedimientos (si no hay, se deja vacío)
$procedimientos = isset($_POST['procedimientos']) ? json_encode($_POST['procedimientos']) : json_encode([]);

// Validar diagnósticos (si no hay, se deja vacío)
$diagnosticos = isset($_POST['diagnosticos']) ? json_encode($_POST['diagnosticos']) : json_encode([]);

// Staff quirúrgico
$cirujano_1 = $_POST['cirujano_1'] ?? null;
$cirujano_2 = $_POST['cirujano_2'] ?? null;
$primer_ayudante = $_POST['primer_ayudante'] ?? null;
$segundo_ayudante = $_POST['segundo_ayudante'] ?? null;
$tercer_ayudante = $_POST['tercer_ayudante'] ?? null;
$anestesiologo = $_POST['anestesiologo'] ?? null;
$ayudante_anestesia = $_POST['ayudante_anestesia'] ?? null;
$circulante = $_POST['circulante'] ?? null;
$instrumentista = $_POST['instrumentista'] ?? null;

// Procedimientos específicos y detalles operatorios
$membrete = $_POST['membrete'] ?? null;
$procedimiento_proyectado = $_POST['procedimiento_proyectado'] ?? null;
$dieresis = $_POST['dieresis'] ?? null;
$exposicion = $_POST['exposicion'] ?? null;
$hallazgo = $_POST['hallazgo'] ?? null;
$operatorio = $_POST['operatorio'] ?? null;
$complicaciones_operatorio = $_POST['complicaciones_operatorio'] ?? null;
$datos_cirugia = $_POST['datos_cirugia'] ?? null;

// Fechas y horas (estas variables no se están enviando actualmente, así que las comentamos)
$fecha_inicio = $_POST['fecha_inicio'] ?? null;
$hora_inicio = $_POST['hora_inicio'] ?? null;
$fecha_fin = $_POST['fecha_fin'] ?? null;
$hora_fin = $_POST['hora_fin'] ?? null;
$tipo_anestesia = $_POST['tipo_anestesia'] ?? null;

// Validaciones adicionales de campos críticos
if (empty($procedimientos) || empty($diagnosticos)) {
    file_put_contents('error_log.txt', "Procedimientos o diagnósticos vacíos" . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Los procedimientos o diagnósticos no pueden estar vacíos."]);
    exit;
}

// Definir la consulta SQL
$sql = "UPDATE protocolo_data pr
            INNER JOIN patient_data p ON pr.hc_number = p.hc_number
            LEFT JOIN procedimiento_proyectado pp ON pp.form_id = pr.form_id AND pp.hc_number = pr.hc_number
            SET p.fname = ?, p.mname = ?, p.lname = ?, p.lname2 = ?, p.fecha_nacimiento = ?, 
            pr.lateralidad = ?, pr.procedimientos = ?, pr.diagnosticos = ?, 
            pr.cirujano_1 = ?, pr.cirujano_2 = ?, pr.primer_ayudante = ?, pr.segundo_ayudante = ?, pr.tercer_ayudante = ?, 
            pr.anestesiologo = ?, pr.ayudante_anestesia = ?, pr.circulante = ?, pr.instrumentista = ?, 
            pr.membrete = ?, pp.procedimiento_proyectado = ?, pr.dieresis = ?, pr.exposicion = ?, pr.hallazgo = ?, pr.operatorio = ?, 
            pr.complicaciones_operatorio = ?, pr.datos_cirugia = ?, pr.fecha_inicio = ?, pr.hora_inicio = ?, pr.fecha_fin = ?, pr.hora_fin = ?, pr.tipo_anestesia = ?
            WHERE pr.form_id = ? AND p.hc_number = ?";

// Preparar la declaración
if ($stmt = $mysqli->prepare($sql)) {
    // Vincular los parámetros (eliminamos los campos de fecha y anestesia que no se envían)
    $stmt->bind_param('ssssssssssssssssssssssssssssssss',
        $fname, $mname, $lname, $lname2, $fecha_nacimiento,
        $lateralidad, $procedimientos, $diagnosticos,
        $cirujano_1, $cirujano_2, $primer_ayudante, $segundo_ayudante, $tercer_ayudante,
        $anestesiologo, $ayudante_anestesia, $circulante, $instrumentista,
        $membrete, $procedimiento_proyectado, $dieresis, $exposicion, $hallazgo, $operatorio,
    $complicaciones_operatorio, $datos_cirugia, $fecha_inicio, $hora_inicio, $fecha_fin, $hora_fin, $tipo_anestesia,
        $form_id, $hc_number
    );

    // Ejecutar la consulta y verificar el resultado
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Datos actualizados correctamente."]);
        } else {
            echo json_encode(["success" => false, "message" => "No se realizaron cambios en la base de datos."]);
        }
    } else {
        // Depuración de errores al ejecutar la consulta
        file_put_contents('sql_error.txt', "Error al ejecutar la consulta: " . $stmt->error . PHP_EOL, FILE_APPEND);
        echo json_encode(["success" => false, "message" => "Error al ejecutar la consulta: " . $stmt->error]);
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    // Depuración: Error al preparar la consulta
    file_put_contents('sql_error.txt', "Error al preparar la consulta: " . $mysqli->error . PHP_EOL, FILE_APPEND);
    echo json_encode(["success" => false, "message" => "Error al preparar la consulta SQL. Revisa el archivo sql_error.txt para más detalles."]);
}

// Cerrar la conexión
$mysqli->close();
?>