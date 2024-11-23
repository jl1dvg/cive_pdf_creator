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
$diastolica = rand(110, 130);
$fc = rand(110, 130);

$idProcedimiento = obtenerIdProcedimiento($realizedProcedure, $mysqli);
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
        <td class="verde" colspan="10">ALERGIA A MEDICAMENTOS</td>
        <td class="verde" colspan="2">SI</td>
        <td class="blanco_left" colspan="2"></td>
        <td class="verde" colspan="2">NO</td>
        <td class="blanco_left" colspan="2"></td>
        <td class="verde" colspan="7">DESCRIBA:</td>
        <td class="blanco_left" colspan="52"></td>
    </tr>
</table>
<table>
    <tr>
        <td class="morado" colspan="77">B. ADMINISTRACIÓN DE MEDICAMENTOS PRESCRITOS</td>
    </tr>
    <tr>
        <td class="morado" colspan="17" style="border-top: 1px solid #808080; border-right: 1px solid #808080;">1.
            MEDICAMENTO
        </td>
        <td class="morado" colspan="60" style="border-top: 1px solid #808080;">2. ADMINISTRACIÓN</td>
    </tr>
    <tr>
        <td class="verde" colspan="17">FECHA</td>
        <td class="blanco" colspan="15"><?php echo $fechaDia . "/" . $fechaMes . "/" . $fechaAno; ?></td>
        <td class="blanco" colspan="15"></td>
        <td class="blanco" colspan="15"></td>
        <td class="blanco" colspan="15"></td>
    </tr>
    <tr>
        <td class="verde" colspan="17">DOSIS, VIA, FRECUENCIA</td>
        <td class="verde" colspan="6">HORA</td>
        <td class="verde" colspan="9">RESPONSABLE</td>
        <td class="verde" colspan="6">HORA</td>
        <td class="verde" colspan="9">RESPONSABLE</td>
        <td class="verde" colspan="6">HORA</td>
        <td class="verde" colspan="9">RESPONSABLE</td>
        <td class="verde" colspan="6">HORA</td>
        <td class="verde" colspan="9">RESPONSABLE</td>
    </tr>
    <?php
    // Verificar si se encontró el procedimiento
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Buscar en la tabla kardex con el procedimiento_id obtenido
        $sql_kardex = "SELECT medicamentos FROM kardex WHERE procedimiento_id = ?";
        if ($stmt_kardex = $mysqli->prepare($sql_kardex)) {
            $stmt_kardex->bind_param("s", $idProcedimiento);
            $stmt_kardex->execute();
            $result_kardex = $stmt_kardex->get_result();

            if ($result_kardex->num_rows > 0) {
                $row_kardex = $result_kardex->fetch_assoc();
                $medicamentos_json = $row_kardex['medicamentos'];

                // Decodificar el JSON de medicamentos
                $medicamentosArray = json_decode($medicamentos_json, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($medicamentosArray)) {
                    // Recorrer el array de medicamentos y generar la tabla para cada uno
                    $horaActual = new DateTime($horaInicioModificada);
                    foreach ($medicamentosArray as $medicamento) {
                        $dosis = $medicamento['dosis'] ?? 'N/A';
                        $frecuencia = $medicamento['frecuencia'] ?? 'N/A';
                        $nombre_medicamento = $medicamento['medicamento'] ?? 'N/A';
                        $responsable = '';
                        if ($medicamento['responsable'] == 'Asistente') {
                            $responsable = 'ENF. ' . substr($ayudante_anestesia, 0, 1) . '. ' . substr(explode(' ', $ayudante_anestesia)[1] ?? '', 0, 1) . '. ' . substr(explode(' ', $ayudante_anestesia)[2] ?? '', 0, 1) . '. ' . substr(explode(' ', $ayudante_anestesia)[3] ?? '', 0, 1) . '.';
                        } elseif ($medicamento['responsable'] == 'Anestesiólogo') {
                            $responsable = 'ANEST. ' . substr($anestesiologo, 0, 1) . '. ' . substr(explode(' ', $anestesiologo)[1] ?? '', 0, 1) . '. ' . substr(explode(' ', $anestesiologo)[2] ?? '', 0, 1) . '. ' . substr(explode(' ', $anestesiologo)[3] ?? '', 0, 1) . '.';
                        } elseif ($medicamento['responsable'] == 'Cirujano Principal') {
                            $responsable = 'OFTAL. ' . substr($mainSurgeon, 0, 1) . '. ' . substr(explode(' ', $mainSurgeon)[1] ?? '', 0, 1) . '. ' . substr(explode(' ', $mainSurgeon)[2] ?? '', 0, 1) . '. ' . substr(explode(' ', $mainSurgeon)[3] ?? '', 0, 1) . '.';
                        }
                        $via_administracion = $medicamento['via_administracion'] ?? 'N/A';
                        $hora_administracion = $medicamento['hora_administracion'] ?? 'N/A';

                        echo "<tr>
                                    <td class='blanco_left' colspan='17' rowspan='2'>" . htmlspecialchars($nombre_medicamento) . ", " . htmlspecialchars($dosis) . ", " . htmlspecialchars($via_administracion) . ", " . htmlspecialchars($frecuencia) . "</td>
                                    <td class='blanco' colspan='6'>{$horaActual->format('H:i')}</td>
                                    <td class='blanco' colspan='9'>" . htmlspecialchars($responsable) . "</td>
                                    <td class='blanco' colspan='6'></td>
                                    <td class='blanco' colspan='9'></td>
                                    <td class='blanco' colspan='6'></td>
                                    <td class='blanco' colspan='9'></td>
                                    <td class='blanco' colspan='6'></td>
                                    <td class='blanco' colspan='9'></td>
                                </tr>
                                <tr>
                                    <td class='blanco' colspan='6'></td>
                                    <td class='blanco' colspan='9'></td>
                                    <td class='blanco' colspan='6'></td>
                                    <td class='blanco' colspan='9'></td>
                                    <td class='blanco' colspan='6'></td>
                                    <td class='blanco' colspan='9'></td>
                                    <td class='blanco' colspan='6'></td>
                                    <td class='blanco' colspan='9'></td>
                                </tr>";
                        // Aumentar la hora en 5 minutos
                        $horaActual->modify('+5 minutes');
                    }
                } else {
                    echo "Error al decodificar el JSON de medicamentos.";
                }
            }

            $stmt_kardex->close();
        }
    }
    ?>
</table>
<table style="border: none">
    <TR>
        <TD colspan="6" HEIGHT=24 ALIGN=LEFT VALIGN=M><B><FONT SIZE=1
                                                               COLOR="#000000"></FONT></B>
        </TD>
        <TD colspan="3" ALIGN=RIGHT VALIGN=TOP><B><FONT SIZE=3 COLOR="#000000">ADMINISTRACION DE MEDICAMENTOS
                    (1)</FONT></B>
        </TD>
    </TR>
</table>

</body>