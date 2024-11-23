<?php
function buscarUsuarioPorNombre($nombreCompleto, $mysqli)
{
    // Convertir el nombre completo a minúsculas y eliminar espacios extra
    $nombreCompletoNormalizado = strtolower(trim($nombreCompleto));

    // Consulta para buscar el usuario por el campo `nombre`, convirtiendo ambos lados a minúsculas
    $sql = "SELECT * FROM users WHERE LOWER(TRIM(nombre)) LIKE ?";

    // Preparar la sentencia SQL
    if ($stmt = $mysqli->prepare($sql)) {
        // Añadir comodines para búsqueda flexible
        $param = "%" . $nombreCompletoNormalizado . "%";

        // Vincular el parámetro
        $stmt->bind_param("s", $param);

        // Ejecutar la consulta y verificar si se ejecuta correctamente
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Devolver la primera coincidencia encontrada
                return $result->fetch_assoc();
            } else {
                // No se encontraron coincidencias
                return null;
            }
        } else {
            // Error al ejecutar la consulta
            echo "Error en la ejecución de la consulta: " . $stmt->error;
            return null;
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        // Error al preparar la consulta
        echo "Error en la preparación de la consulta: " . $mysqli->error;
        return null;
    }
}
function obtenerIdProcedimiento($realizedProcedure, $mysqli)
{
    // Normalizar el nombre del procedimiento realizado
    $normalized_realized = strtolower(trim($realizedProcedure));

    // Utilizar una expresión regular para extraer el nombre del procedimiento proyectado sin las partes adicionales
    preg_match('/^(.*?)(\sen\sojo\s.*|\sao|\soi|\sod)?$/i', $normalized_realized, $matches);
    $nombre_procedimiento_realizado = $matches[1] ?? '';

    if (!empty($nombre_procedimiento_realizado)) {
        // Consulta para extraer el id de la tabla procedimientos
        $sql = "SELECT id FROM procedimientos WHERE LOWER(TRIM(membrete)) LIKE ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $searchTerm = $nombre_procedimiento_realizado;
            $stmt->bind_param('s', $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['id'];
            }
        }
    }

    // Retornar null si no se encuentra el ID
    return null;
}
function obtenerDiagnosticosAnteriores($hc_number, $form_id, $mysqli, $idProcedimientos)
{
    // Nueva consulta para obtener diagnósticos anteriores
    $sql = "SELECT diagnosticos FROM consulta_data WHERE hc_number = ? AND form_id < ? ORDER BY form_id DESC LIMIT 1";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ss', $hc_number, $form_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $previousData = $result->fetch_assoc();
        $previousDiagnoses = $previousData['diagnosticos'] ?? null;

        // Decodificar JSON de diagnósticos anteriores
        $previousDiagnosesArray = $previousDiagnoses ? json_decode($previousDiagnoses, true) : [];

        if (empty($previousDiagnosesArray)) {
            // Realizar un LEFT JOIN para obtener dx_code y long_desc
            $sql2 = "
                SELECT p.dx_pre, i.dx_code, i.long_desc
                FROM procedimientos p
                LEFT JOIN icd10_dx_order_code i ON p.dx_pre = i.dx_code
                WHERE p.id = ? LIMIT 1
            ";
            $stmt2 = $mysqli->prepare($sql2);

            if ($stmt2) {
                $stmt2->bind_param('s', $idProcedimientos);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                $procedimientoData = $result2->fetch_assoc();

                // Verificar si se obtuvieron datos del LEFT JOIN
                $dx_code = $procedimientoData['dx_code'] ?? null;
                $long_desc = $procedimientoData['long_desc'] ?? null;

                if ($dx_code && $long_desc) {
                    return [
                        "$dx_code - $long_desc", // Combina dx_code y long_desc
                        '',     // Diagnóstico 2 vacío
                        ''      // Diagnóstico 3 vacío
                    ];
                } else {
                    // Debug: No se encontraron datos en la tabla icd10_dx_order_code
                    error_log("No se encontraron datos para dx_pre en icd10_dx_order_code para el ID de procedimiento: $idProcedimientos");
                }
            } else {
                // Debug: Error en la preparación del statement
                error_log("Error al preparar la consulta para LEFT JOIN: " . $mysqli->error);
            }
        } else {
            // Retornar diagnósticos existentes
            return [
                $previousDiagnosesArray[0]['idDiagnostico'] ?? '',
                $previousDiagnosesArray[1]['idDiagnostico'] ?? '',
                $previousDiagnosesArray[2]['idDiagnostico'] ?? ''
            ];
        }
    } else {
        // Debug: Error en la preparación del statement principal
        error_log("Error al preparar la consulta principal: " . $mysqli->error);
    }

    // Retornar diagnósticos vacíos si la consulta falla
    return ['', '', ''];
}
function mostrarImagenProcedimiento($idProcedimiento, $mysqli)
{
    if (!empty($idProcedimiento)) {
        // Consulta para extraer el link de imagen de la tabla procedimientos
        $sql = "SELECT imagen_link FROM procedimientos WHERE id = ?";

        // Preparar la sentencia
        if ($stmt = $mysqli->prepare($sql)) {
            // Vincular el parámetro
            $stmt->bind_param("s", $idProcedimiento);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado
            $result = $stmt->get_result();

            // Verificar si se encontró el procedimiento
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $imagen_link = $row['imagen_link'];

                // Mostrar la imagen directamente
                echo "<img src='" . htmlspecialchars($imagen_link) . "' alt='Imagen del Procedimiento' style='max-height: 140px;'>";
            } else {
                // Mensaje en caso de que no se encuentre el procedimiento
                echo "No se encontró la imagen para el procedimiento.";
            }
        } else {
            // Error al preparar la consulta
            error_log("Error al preparar la consulta: " . $mysqli->error);
        }
    } else {
        echo "ID de procedimiento vacío.";
    }
}
?>