<?php
ini_set('session.save_path', __DIR__ . '/../../sessions');
session_name('mi_sesion');
session_start();
require '../../conexion.php';

header('Content-Type: application/json');  // Asegurarnos de que siempre devolvemos JSON

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPrimaria = $_POST['id'];
    $cirugia = $_POST['cirugia'];
    $categoria = $_POST['categoriaQX'];
    $membrete = $_POST['membrete'];
    $dieresis = $_POST['dieresis'];
    $exposicion = $_POST['exposicion'];
    $hallazgo = $_POST['hallazgo'];
    $horas = $_POST['horas'];
    $imagen_link = $_POST['imagen_link'];
    $operatorio = $_POST['operatorio'];

    $pre_evolucion = $_POST['pre_evolucion'];
    $pre_indicacion = $_POST['pre_indicacion'];
    $post_evolucion = $_POST['post_evolucion'];
    $post_indicacion = $_POST['post_indicacion'];
    $alta_evolucion = $_POST['alta_evolucion'];
    $alta_indicacion = $_POST['alta_indicacion'];

    $insumos = !empty($_POST['insumos']) ? $_POST['insumos'] : json_encode([]);
    $medicamentos = !empty($_POST['medicamentos']) ? $_POST['medicamentos'] : json_encode([]);

    // Depuración: Verificar el contenido de insumos y medicamentos
    file_put_contents('debug_data.txt', "Insumos: " . print_r($insumos, true) . PHP_EOL, FILE_APPEND);

    // Validar JSON
    if (json_decode($insumos) === null) {
        echo json_encode(["success" => false, "message" => "El JSON de insumos no es válido."]);
        exit;
    }

    // Actualizar procedimientos y evolución
    $sql = "UPDATE procedimientos p
            JOIN evolucion005 e ON p.id = e.id
            SET 
                p.cirugia = ?, 
                p.categoria = ?, 
                p.membrete = ?,
                p.dieresis = ?,
                p.exposicion = ?,
                p.hallazgo = ?,
                p.horas = ?,
                p.imagen_link = ?, 
                p.operatorio = ?,
                e.pre_evolucion = ?, 
                e.pre_indicacion = ?, 
                e.post_evolucion = ?, 
                e.post_indicacion = ?, 
                e.alta_evolucion = ?, 
                e.alta_indicacion = ? 
            WHERE p.id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssss",
        $cirugia, $categoria, $membrete, $dieresis, $exposicion, $hallazgo, $horas,
        $imagen_link, $operatorio, $pre_evolucion, $pre_indicacion, $post_evolucion,
        $post_indicacion, $alta_evolucion, $alta_indicacion, $idPrimaria
    );

    if ($stmt->execute()) {
        // Verificar si insumos_pack existe, si no, insertarlo
        $sql_check_insumos = "SELECT COUNT(*) AS count FROM insumos_pack WHERE procedimiento_id = ?";
        $stmt_check = $mysqli->prepare($sql_check_insumos);
        $stmt_check->bind_param("s", $idPrimaria);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();

        if ($row_check['count'] == 0) {
            // Insertar nuevo registro en insumos_pack con los insumos proporcionados
            $insumos = !empty($_POST['insumos']) ? $_POST['insumos'] : json_encode(["equipos" => [], "quirurgicos" => [], "anestesia" => []]);
            $sql_insert_insumos = "INSERT INTO insumos_pack (procedimiento_id, insumos) VALUES (?, ?)";
            $stmt_insert = $mysqli->prepare($sql_insert_insumos);
            $stmt_insert->bind_param("ss", $idPrimaria, $insumos);
            if ($stmt_insert->execute()) {
                echo json_encode(["success" => true, "message" => "Nuevo pack de insumos creado correctamente."]);
            } else {
                file_put_contents('sql_error.txt', "Error al insertar insumos: " . $stmt_insert->error . PHP_EOL, FILE_APPEND);
                echo json_encode(["success" => false, "message" => "Error al insertar el pack de insumos: " . $stmt_insert->error]);
            }
            $stmt_insert->close();
        } else {
            // Actualizar los insumos en la tabla insumos_pack
            $sql_insumos = "UPDATE insumos_pack SET insumos = ? WHERE procedimiento_id = ?";
            $stmt_insumos = $mysqli->prepare($sql_insumos);
            $stmt_insumos->bind_param("ss", $insumos, $idPrimaria);
            if ($stmt_insumos->execute()) {
                if ($stmt->affected_rows > 0 || $stmt_insumos->affected_rows > 0) {
                    echo json_encode(["success" => true, "message" => "Datos actualizados correctamente, incluyendo los insumos."]);
                } else {
                    // Registrar en el log si la consulta se ejecutó sin cambios
                    file_put_contents('debug_no_changes.txt', "No se realizaron cambios en la tabla procedimientos o insumos_pack, procedimiento_id: " . $idPrimaria . PHP_EOL, FILE_APPEND);
                    echo json_encode(["success" => true, "message" => "No se realizaron cambios en la base de datos, pero la consulta fue exitosa."]);
                }
            } else {
                // Depuración de errores al actualizar insumos
                file_put_contents('sql_error.txt', "Error al actualizar insumos: " . $stmt_insumos->error . PHP_EOL, FILE_APPEND);
                echo json_encode(["success" => false, "message" => "Error al actualizar los insumos: " . $stmt_insumos->error]);
            }
            $stmt_insumos->close();
        }
        $stmt_check->close();
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