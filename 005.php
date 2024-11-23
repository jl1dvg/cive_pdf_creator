<?php

require 'conexion.php';  // Asegúrate de tener la conexión a la base de datos configurada

// Obtener los parámetros enviados desde el enlace
$form_id = $_GET['form_id'] ?? null;
$hc_number = $_GET['hc_number'] ?? null;

if ($form_id && $hc_number) {
// Consulta para obtener los datos del protocolo y el paciente
    $sql = "SELECT p.hc_number, p.fname, p.mname, p.lname, p.lname2, p.fecha_nacimiento, p.afiliacion, p.sexo, p.ciudad, 
            pr.form_id, pr.fecha_inicio, pr.hora_inicio, pr.fecha_fin, pr.hora_fin, pr.cirujano_1, pr.instrumentista, 
            pr.cirujano_2, pr.circulante, pr.primer_ayudante, pr.anestesiologo, pr.segundo_ayudante, 
            pr.ayudante_anestesia, pr.tercer_ayudante, pr.membrete, pr.dieresis, pr.exposicion, pr.hallazgo, 
            pr.operatorio, pr.complicaciones_operatorio, pr.datos_cirugia, pr.procedimientos, pr.lateralidad, 
            pr.tipo_anestesia, pr.diagnosticos, pp.procedimiento_proyectado
        FROM patient_data p 
        INNER JOIN protocolo_data pr ON p.hc_number = pr.hc_number
        LEFT JOIN procedimiento_proyectado pp ON pp.form_id = pr.form_id AND pp.hc_number = pr.hc_number
        WHERE pr.form_id = ? AND p.hc_number = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ss', $form_id, $hc_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $protocol_data = $result->fetch_assoc();

        // Ahora también tienes la variable `procedimiento_proyectado`
        $procedimientoProyectado = $protocol_data['procedimiento_proyectado'] ?? 'No data';  // Valor del procedimiento proyectado
        // Dividir la cadena en partes usando " - " como delimitador
        $parts = explode(' - ', $procedimientoProyectado);

        // Asignar la tercera parte como el nombre del procedimiento
        $nombre_procedimiento_proyectado = isset($parts[2]) ? $parts[2] : '';

        // Asignar los datos de `patient_data`
        $fname = $protocol_data['fname'];
        $mname = $protocol_data['mname'];
        $lname = $protocol_data['lname'];
        $lname2 = $protocol_data['lname2'];
        $historyNumber = $protocol_data['hc_number'];
        $birthDate = $protocol_data['fecha_nacimiento'];
        $gender = $protocol_data['sexo'];
        $insurance = $protocol_data['afiliacion'];
        $city = $protocol_data['ciudad'];

        // Asignar los datos de `protocolo_data`
        $form_id = $protocol_data['form_id'];
        $fechaInicio = $protocol_data['fecha_inicio'];
        $horaInicio = $protocol_data['hora_inicio'];
        $fechaFin = $protocol_data['fecha_fin'];
        $horaFin = $protocol_data['hora_fin'];
        $mainSurgeon = $protocol_data['cirujano_1'];
        $assistantSurgeon1 = $protocol_data['cirujano_2'];
        $instrumentista = $protocol_data['instrumentista'];
        $circulante = $protocol_data['circulante'];
        $ayudante = $protocol_data['primer_ayudante'];
        $anestesiologo = $protocol_data['anestesiologo'];
        $ayudante2 = $protocol_data['segundo_ayudante'];
        $thirdAssistant = $protocol_data['tercer_ayudante'];
        $ayudante_anestesia = $protocol_data['ayudante_anestesia'];
        $tipoAnestesia = $protocol_data['tipo_anestesia'];
        $realizedProcedure = $protocol_data['membrete'];
        $dieresis = $protocol_data['dieresis'];
        $exposicion = $protocol_data['exposicion'];
        $hallazgo = $protocol_data['hallazgo'];
        $operatorio = nl2br($protocol_data['operatorio']);
        $surgicalDetails = $protocol_data['datos_cirugia'];
        $procedures = $protocol_data['procedimientos'];
        $lateralidad = $protocol_data['lateralidad'];
        $diagnoses = $protocol_data['diagnosticos'];

        // 1. Decodificar JSON de diagnósticos
        $diagnosesArray = json_decode($diagnoses, true);

        // Asignar diagnósticos y códigos CIE-10 a variables
        $diagnostic1 = $diagnosesArray[0]['idDiagnostico'] ?? '';
        $diagnostic2 = $diagnosesArray[1]['idDiagnostico'] ?? '';
        $diagnostic3 = $diagnosesArray[2]['idDiagnostico'] ?? '';

        // Decodificar el JSON de procedimientos
        $proceduresArray = json_decode($procedures, true);

        $codes = [];

        // Verificar si el array de procedimientos es válido
        if (is_array($proceduresArray)) {
            foreach ($proceduresArray as $proc) {
                if (isset($proc['procInterno'])) {
                    // Separar la cadena en partes usando " - " como delimitador
                    $parts = explode(' - ', $proc['procInterno']);
                    // Verificar si existe la parte del código (la segunda parte)
                    if (isset($parts[1])) {
                        // Agregar el código al array de códigos
                        $codes[] = $parts[1];
                    }
                }
            }
        }

        // Unir todos los códigos con "/"
        $codes_concatenados = implode('/', $codes);

    }
}

// 3. Calcular la edad del paciente
$birthDateObj = new DateTime($birthDate);
$fechaInicioObj = new DateTime($fechaInicio);
$edadPaciente = $birthDateObj->diff($fechaInicioObj)->y;

// Separar la fecha y la hora
$fechaInicioParts = explode(' ', $fechaInicio); // Dividir la fecha y la hora
$fechaPart = $fechaInicioParts[0];  // Parte de la fecha 'Y-m-d'
// Separar la fecha en día, mes y año
list($fechaAno, $fechaMes, $fechaDia) = explode('-', $fechaPart);

// Convertir las horas a objetos DateTime
$horaInicioObj = new DateTime($horaInicio);
$horaFinObj = new DateTime($horaFin);

// Restar 45 minutos a $horaInicio
$horaInicioObj->modify('-45 minutes');

// Agregar 30 minutos a $horaFin
$horaFinObj->modify('+30 minutes');

// Formatear las horas para imprimirlas en el formato deseado (por ejemplo, HH:mm)
$horaInicioModificada = $horaInicioObj->format('H:i');
$horaFinModificada = $horaFinObj->format('H:i');

// 5. Separar el contenido del procedimiento realizado por código
$realizedProceduresArray = preg_split('/(?=\d{5}-)/', $realizedProcedure);
$formattedRealizedProcedure = implode('<br>', $realizedProceduresArray);

// Datos adicionales (tensión, frecuencia, etc.)
$sistolica = rand(110, 130);
$diastolica = rand(70, 83);
$fc = rand(75, 100);
// Llamada a la función
$idProcedimiento = obtenerIdProcedimiento($realizedProcedure, $mysqli);
$diagnosticosPrevios = obtenerDiagnosticosAnteriores($hc_number, $form_id, $mysqli, $idProcedimiento);
$previousDiagnostic1 = $diagnosticosPrevios[0];
$previousDiagnostic2 = $diagnosticosPrevios[1];
$previousDiagnostic3 = $diagnosticosPrevios[2];
$procedimientoProyectadoNow = $nombre_procedimiento_proyectado;
?>
<body>
<TABLE>
    <tr>
        <td colspan='71' class='morado'>A. DATOS DEL ESTABLECIMIENTO
            Y USUARIO / PACIENTE
        </td>
    </tr>
    <tr>
        <td colspan='15' height='27' class='verde'>INSTITUCIÓN DEL SISTEMA</td>
        <td colspan='6' class='verde'>UNICÓDIGO</td>
        <td colspan='18' class='verde'>ESTABLECIMIENTO DE SALUD</td>
        <td colspan='18' class='verde'>NÚMERO DE HISTORIA CLÍNICA ÚNICA</td>
        <td colspan='14' class='verde' style='border-right: none'>NÚMERO DE ARCHIVO</td>
    </tr>
    <tr>
        <td colspan='15' height='27' class='blanco'><?php echo $insurance; ?></td>
        <td colspan='6' class='blanco'>&nbsp;</td>
        <td colspan='18' class='blanco'>CIVE</td>
        <td colspan='18' class='blanco'><?php echo $historyNumber; ?></td>
        <td colspan='14' class='blanco' style='border-right: none'><?php echo $historyNumber; ?></td>
    </tr>
    <tr>
        <td colspan='15' rowspan='2' height='41' class='verde' style='height:31.0pt;'>PRIMER APELLIDO</td>
        <td colspan='13' rowspan='2' class='verde'>SEGUNDO APELLIDO</td>
        <td colspan='13' rowspan='2' class='verde'>PRIMER NOMBRE</td>
        <td colspan='10' rowspan='2' class='verde'>SEGUNDO NOMBRE</td>
        <td colspan='3' rowspan='2' class='verde'>SEXO</td>
        <td colspan='6' rowspan='2' class='verde'>FECHA NACIMIENTO</td>
        <td colspan='3' rowspan='2' class='verde'>EDAD</td>
        <td colspan='8' class='verde' style='border-right: none; border-bottom: none'>CONDICIÓN EDAD <font
                    class='font7'>(MARCAR)</font></td>
    </tr>
    <tr>
        <td colspan='2' height='17' class='verde'>H</td>
        <td colspan='2' class='verde'>D</td>
        <td colspan='2' class='verde'>M</td>
        <td colspan='2' class='verde' style='border-right: none'>A</td>
    </tr>
    <tr>
        <td colspan='15' height='27' class='blanco'><?php echo $lname; ?></td>
        <td colspan='13' class='blanco'><?php echo $lname2; ?></td>
        <td colspan='13' class='blanco'><?php echo $fname; ?></td>
        <td colspan='10' class='blanco'><?php echo $mname; ?></td>
        <td colspan='3' class='blanco'><?php echo $gender; ?></td>
        <td colspan='6' class='blanco'><?php echo $birthDate; ?></td>
        <td colspan='3' class='blanco'><?php echo $edadPaciente; ?></td>
        <td colspan='2' class='blanco'>&nbsp;</td>
        <td colspan='2' class='blanco'>&nbsp;</td>
        <td colspan='2' class='blanco'>&nbsp;</td>
        <td colspan='2' class='blanco' style='border-right: none'>&nbsp;</td>
    </tr>
</TABLE>
<table>
    <tr>
        <td class='morado' colspan='26' style='border-bottom: 1px solid #808080;'>B. EVOLUCIÓN Y PRESCRIPCIONES</td>
        <td class='morado' colspan='20' style='font-size: 4pt; font-weight: lighter; border-bottom: 1px solid #808080;'>
            FIRMAR AL PIE DE CADA EVOLUCIÓN Y PRESCRIPCIÓN
        </td>
        <td class='morado' colspan='21'
            style='font-size: 4pt; font-weight: lighter; text-align: right; border-bottom: 1px solid #808080;'>
            REGISTRAR CON ROJO LA ADMINISTRACIÓN DE FÁRMACOS Y COLOCACIÓN DE DISPOSITIVOS MÉDICOS
        </td>
    </tr>
    <tr>
        <td class='morado' colspan='38' style='text-align: center'>1. EVOLUCIÓN</td>
        <td class='blanco_break'></td>
        <td class='morado' colspan='28' style='text-align: center'>2. PRESCRIPCIONES</td>
    </tr>
    <tr>
        <td class='verde' colspan='6' width="8%">FECHA<br><span
                    style='font-size:6pt;font-family:Arial;font-weight:normal;'>(aaaa-mm-dd)</span>
        </td>
        <td class='verde' colspan='3'>HORA<br><span
                    style='font-size:6pt;font-family:Arial;font-weight:normal;'>(hh:mm)</span></td>
        <td class='verde' colspan='29' width="40%">NOTAS DE EVOLUCIÓN</td>
        <td class='blanco_break'></td>
        <td class='verde' colspan='23' width="35%">FARMACOTERAPIA E INDICACIONES<span
                    style='font-size:6pt;font-family:Arial;font-weight:normal;'><br>(Para enfermería y otro profesional de salud)</span>
        </td>
        <td class='verde' colspan='5'><span style='font-size:6pt;font-family:Arial;font-weight:normal;'>ADMINISTR. <br>FÁRMACOS<br>DISPOSITIVO</span>
        </td>
    </tr>
    <?php
    // Verificar si se encontró el procedimiento
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Buscar en la tabla evolucion005 con el procedimiento_id obtenido
        $sql_evolucion = "SELECT pre_evolucion, pre_indicacion, post_evolucion, post_indicacion, alta_evolucion, alta_indicacion FROM evolucion005 WHERE id = ?";
        if ($stmt_evolucion = $mysqli->prepare($sql_evolucion)) {
            $stmt_evolucion->bind_param("s", $idProcedimiento);
            $stmt_evolucion->execute();
            $result_evolucion = $stmt_evolucion->get_result();

            if ($result_evolucion->num_rows > 0) {
                $row_evolucion = $result_evolucion->fetch_assoc();

                // Asignar valores a las variables
                $text = eval("return \"" . addslashes($row_evolucion['pre_evolucion']) . "\";");
                $text2 = $row_evolucion['pre_indicacion'];
                $text3 = eval("return \"" . addslashes($row_evolucion['post_evolucion']) . "\";");
                $text4 = $row_evolucion['post_indicacion'];
                $text5 = eval("return \"" . addslashes($row_evolucion['alta_evolucion']) . "\";");
                $text6 = $row_evolucion['alta_indicacion'];

                // Dividir el texto en líneas cada 100 caracteres
                $lines1 = wordwrap($text, 70, "\n", true);
                $lines1 = explode("\n", $lines1);
                $lines2 = wordwrap($text2, 80, "\n", true);
                $lines2 = explode("\n", $lines2);
                $lines3 = wordwrap($text3, 70, "\n", true);
                $lines3 = explode("\n", $lines3);
                $lines4 = wordwrap($text4, 60, "\n", true);
                $lines4 = explode("\n", $lines4);
                $lines5 = wordwrap($text5, 70, "\n", true);
                $lines5 = explode("\n", $lines5);
                $lines6 = wordwrap($text6, 60, "\n", true);
                $lines6 = explode("\n", $lines6);

                // Determinar el número máximo de líneas entre todos los textos
                $maxLines = max(count($lines1), count($lines2), count($lines3), count($lines4), count($lines5), count($lines6));
                // Iniciar la tabla
                ?>
                <tr>
                    <td class='blanco_left'
                        colspan='6'><?php echo $fechaDia . '/' . $fechaMes . '/' . $fechaAno; ?></td>
                    <td class='blanco_left' colspan='3'><?php echo $horaInicioModificada; ?></td>
                    <td class='blanco_left' colspan='29' style='text-align: center'><b>PRE-OPERATORIO</b></td>
                    <td class='blanco_break'></td>
                    <td class='blanco_left' colspan='23' style='text-align: center'><b>PRE-OPERATORIO</b></td>
                    <td class='blanco_left' colspan='5'></td>
                </tr>
                <?php
                // Iterar sobre el número máximo de líneas
                for ($i = 0; $i < $maxLines; $i++) {
                    echo "<tr>";

                    // Imprimir línea del primer bloque si existe, si no, imprimir celda vacía
                    if (isset($lines1[$i])) {
                        echo "<td class='blanco_left' colspan='6'></td>";
                        echo "<td class='blanco_left' colspan='3'></td>";
                        echo "<td class='blanco_left' colspan='29'>" . htmlspecialchars(trim($lines1[$i])) . "</td>";
                    } else {
                        echo "<td class='blanco_left' colspan='6'></td>";
                        echo "<td class='blanco_left' colspan='3'></td>";
                        echo "<td class='blanco_left' colspan='29'></td>";
                    }

                    echo "<td class='blanco_break'></td>";

                    // Imprimir línea del segundo bloque si existe, si no, imprimir celda vacía
                    if (isset($lines2[$i])) {
                        echo "<td class='blanco_left' colspan='23'>" . htmlspecialchars(trim($lines2[$i])) . "</td>";
                    } else {
                        echo "<td class='blanco_left' colspan='23'></td>";
                    }

                    echo "<td class='blanco_left' colspan='5'></td>";
                    echo "</tr>";
                } ?>
                <tr>
                    <td class='blanco_left' colspan='6'></td>
                    <td class='blanco_left' colspan='3'></td>
                    <td class='blanco_left' colspan='29'></td>
                    <td class='blanco_break'></td>
                    <td class='blanco_left' colspan='23'></td>
                    <td class='blanco_left' colspan='5'></td>
                </tr>
                <tr>
                    <td class='blanco_left' colspan='6'></td>
                    <td class='blanco_left' colspan='3'><?php echo $horaFin; ?></td>
                    <td class='blanco_left' colspan='29' style='text-align: center'><b>POST-OPERATORIO</b></td>
                    <td class='blanco_break'></td>
                    <td class='blanco_left' colspan='23' style='text-align: center'><b>POST-OPERATORIO</b></td>
                    <td class='blanco_left' colspan='5'></td>
                </tr>
                <?php
                // Iterar sobre el número máximo de líneas para el tercer bloque
                for ($i = 0; $i < $maxLines; $i++) {
                    echo "<tr>";

                    // Imprimir línea del tercer bloque si existe, si no, imprimir celda vacía
                    if (isset($lines3[$i])) {
                        echo "<td class='blanco_left' colspan='6'></td>";
                        echo "<td class='blanco_left' colspan='3'></td>";
                        echo "<td class='blanco_left' colspan='29'>" . htmlspecialchars(trim($lines3[$i])) . "</td>";
                    } else {
                        echo "<td class='blanco_left' colspan='6'></td>";
                        echo "<td class='blanco_left' colspan='3'></td>";
                        echo "<td class='blanco_left' colspan='29'></td>";
                    }

                    echo "<td class='blanco_break'></td>";

                    // Imprimir línea del segundo bloque si existe, si no, imprimir celda vacía
                    if (isset($lines2[$i])) {
                        echo "<td class='blanco_left' colspan='23'>" . htmlspecialchars(trim($lines4[$i])) . "</td>";
                    } else {
                        echo "<td class='blanco_left' colspan='23'></td>";
                    }

                    echo "<td class='blanco_left' colspan='5'></td>";
                    echo "</tr>";
                }
                ?>
                <tr>
                    <td class='blanco_left' colspan='6'></td>
                    <td class='blanco_left' colspan='3'></td>
                    <td class='blanco_left' colspan='29'>MD. <?php echo $anestesiologo; ?></td>
                    <td class='blanco_break'></td>
                    <td class='blanco_left' colspan='23'></td>
                    <td class='blanco_left' colspan='5'></td>
                </tr>
                <tr>
                    <td class='blanco_left' colspan='6'></td>
                    <td class='blanco_left' colspan='3'><?php echo $horaFinModificada; ?></td>
                    <td class='blanco_left' colspan='29' style='text-align: center'><b>ALTA MEDICA</b></td>
                    <td class='blanco_break'></td>
                    <td class='blanco_left' colspan='23' style='text-align: center'><b>ALTA MEDICA</b></td>
                    <td class='blanco_left' colspan='5'></td>
                </tr>
                <?php
                // Iterar sobre el número máximo de líneas para el tercer bloque
                for ($i = 0; $i < $maxLines; $i++) {
                    echo "<tr>";

                    // Imprimir línea del tercer bloque si existe, si no, imprimir celda vacía
                    if (isset($lines5[$i])) {
                        echo "<td class='blanco_left' colspan='6'></td>";
                        echo "<td class='blanco_left' colspan='3'></td>";
                        echo "<td class='blanco_left' colspan='29'>" . htmlspecialchars(trim($lines5[$i])) . "</td>";
                    } else {
                        echo "<td class='blanco_left' colspan='6'></td>";
                        echo "<td class='blanco_left' colspan='3'></td>";
                        echo "<td class='blanco_left' colspan='29'></td>";
                    }

                    echo "<td class='blanco_break'></td>";

                    // Imprimir línea del segundo bloque si existe, si no, imprimir celda vacía
                    if (isset($lines6[$i])) {
                        echo "<td class='blanco_left' colspan='23'>" . htmlspecialchars(trim($lines6[$i])) . "</td>";
                    } else {
                        echo "<td class='blanco_left' colspan='23'></td>";
                    }

                    echo "<td class='blanco_left' colspan='5'></td>";
                    echo "</tr>";
                } ?>
                <tr>
                    <td class='blanco_left' colspan='6'></td>
                    <td class='blanco_left' colspan='3'></td>
                    <td class='blanco_left' colspan='29'>MD. <?php echo $mainSurgeon; ?></td>
                    <td class='blanco_break'></td>
                    <td class='blanco_left' colspan='23'></td>
                    <td class='blanco_left' colspan='5'></td>
                </tr>
                <?php
                // Cerrar la tabla
                echo "</table>";
            }
            $stmt_evolucion->close();
        }
    }
    // Cerrar la sentencia
    $stmt->close();


    // Iniciar la tabla
    ?>
    <table style="border: none">
        <TR>
            <TD colspan="6" HEIGHT=24 ALIGN=LEFT VALIGN=M><B><FONT SIZE=1
                                                                   COLOR="#000000">SNS-MSP/HCU-form.005/2021</FONT></B>
            </TD>
            <TD colspan="3" ALIGN=RIGHT VALIGN=TOP><B><FONT SIZE=3 COLOR="#000000">EVOLUCIÓN Y PRESCRIPCIONES
                        (1)</FONT></B>
            </TD>
        </TR>
    </table>
</body>
