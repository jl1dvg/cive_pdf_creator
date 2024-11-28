<?php
require 'conexion.php';  // Asegúrate de tener la conexión a la base de datos configurada
require 'library/forms.php';

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

        $cirujano_data = buscarUsuarioPorNombre($mainSurgeon, $mysqli);
        $cirujano2_data = buscarUsuarioPorNombre($assistantSurgeon1, $mysqli);
        $ayudante_data = buscarUsuarioPorNombre($ayudante, $mysqli);
        $anestesiologo_data = buscarUsuarioPorNombre($anestesiologo, $mysqli);

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

// 5. Separar el contenido del procedimiento realizado por código
$realizedProceduresArray = preg_split('/(?=\d{5}-)/', $realizedProcedure);
$formattedRealizedProcedure = implode('<br>', $realizedProceduresArray);

// Datos adicionales (tensión, frecuencia, etc.)
$sistolica = rand(110, 130);
$diastolica = rand(110, 130);
$fc = rand(110, 130);

$idProcedimiento = obtenerIdProcedimiento($realizedProcedure, $mysqli);
$diagnosticosPrevios = obtenerDiagnosticosAnteriores($hc_number, $form_id, $mysqli, $idProcedimiento);
?>
<BODY>
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
        <td colspan='10' class='morado'>B. DIAGNÓSTICOS</td>
        <td colspan='2' class='morado' style='text-align: center'>CIE</td>
    </tr>
    <tr>
        <td colspan='2' width='18%' rowspan='3' class='verde_left'>Pre Operatorio:</td>
        <td class='verde_left' width='2%'>1.</td>
        <td class='blanco_left' colspan='7'><?php echo strtoupper(substr($diagnosticosPrevios[0], 6)); ?></td>
        <td class='blanco' width='20%' colspan='2'><?php echo substr($diagnosticosPrevios[0], 0, 4); ?></td>
    </tr>
    <tr>
        <td class='verde_left' width='2%'>2.</td>
        <td class='blanco_left' colspan='7'><?php echo strtoupper(substr($diagnosticosPrevios[1], 6)); ?></td>
        <td class='blanco' width='20%' colspan='2'><?php echo substr($diagnosticosPrevios[1], 0, 4); ?></td>
    </tr>
    <tr>
        <td class='verde_left' width='2%'>3.</td>
        <td class='blanco_left' colspan='7'><?php echo strtoupper(substr($diagnosticosPrevios[2], 6)); ?></td>
        <td class='blanco' width='20%' colspan='2'><?php echo substr($diagnosticosPrevios[2], 0, 4); ?></td>
    </tr>
    <tr>
        <td colspan='2' rowspan='3' class='verde_left'>Post Operatorio:</td>
        <td class='verde_left'>1.</td>
        <td class='blanco_left' colspan='7'><?php echo substr($diagnostic1, 6); ?></td>
        <td class='blanco' colspan='2'><?php echo substr($diagnostic1, 0, 4); ?></td>
    </tr>
    <tr>
        <td class='verde_left'>2.</td>
        <td class='blanco_left' colspan='7'><?php echo substr($diagnostic2, 6); ?></td>
        <td class='blanco' colspan='2'><?php echo substr($diagnostic2, 0, 4); ?></td>
    </tr>
    <tr>
        <td class='verde_left'>3.</td>
        <td class='blanco_left' colspan='7'><?php echo substr($diagnostic3, 6); ?></td>
        <td class='blanco' colspan='2'><?php echo substr($diagnostic3, 0, 4); ?></td>
    </tr>
</table>
<table>
    <tr>
        <td colspan='11' class='morado'>C. PROCEDIMIENTO</td>
        <td colspan='2' class='verde_left' style='text-align: center'>Electiva</td>
        <td colspan='1' class='blanco' style='text-align: center'>X</td>
        <td colspan='2' class='verde_left' style='text-align: center'>Emergencia</td>
        <td colspan='1' class='blanco' style='text-align: center'></td>
        <td colspan='2' class='verde_left' style='text-align: center'>Urgencia</td>
        <td colspan='1' class='blanco' style='text-align: center'></td>
    </tr>
    <tr>
        <td colspan='2' class='verde_left'>Proyectado:</td>
        <td class='blanco_left' colspan='18'>
            <?php
            echo strtoupper(
                $nombre_procedimiento_proyectado
                    ? $nombre_procedimiento_proyectado . ' ' . $lateralidad
                    : $protocol_data['membrete']
            );
            ?>
        </td>
    </tr>
    <tr>
        <td colspan='2' class='verde_left'>Realizado:</td>
        <td class='blanco_left'
            colspan='18'><?php echo strtoupper($formattedRealizedProcedure) . ' ' . $codes_concatenados; ?></td>
    </tr>
</table>
<table>
    <tr>
        <td class='morado' colspan='20'>D. INTEGRANTES DEL EQUIPO QUIRÚRGICO</td>
    </tr>
    <tr>
        <td class='verde_left' colspan='3'>Cirujano 1:</td>
        <td class='blanco' colspan='7'><?php echo $mainSurgeon; ?></td>
        <td class='verde_left' colspan='3'>Instrumentista:</td>
        <td class='blanco' colspan='7'><?php echo $instrumentista; ?></td>
    </tr>
    <tr>
        <td class='verde_left' colspan='3'>Cirujano 2:</td>
        <td class='blanco' colspan='7'><?php echo $assistantSurgeon1; ?></td>
        <td class='verde_left' colspan='3'>Circulante:</td>
        <td class='blanco' colspan='7'><?php echo $circulante; ?></td>
    </tr>
    <tr>
        <td class='verde_left' colspan='3'>Primer Ayudante:</td>
        <td class='blanco' colspan='7'><?php echo $ayudante; ?></td>
        <td class='verde_left' colspan='3'>Anestesiologo/a:</td>
        <td class='blanco' colspan='7'><?php echo $anestesiologo; ?></td>
    </tr>
    <tr>
        <td class='verde_left' colspan='3'>Segundo Ayudante:</td>
        <td class='blanco' colspan='7'></td>
        <td class='verde_left' colspan='3'>Ayudante Anestesia:</td>
        <td class='blanco' colspan='7'><?php echo $ayudante_anestesia; ?></td>
    </tr>
    <tr>
        <td class='verde_left' colspan='3'>Tercer Ayudante:</td>
        <td class='blanco' colspan='7'></td>
        <td class='verde_left' colspan='3'>Otros:</td>
        <td class='blanco' colspan='7'></td>
    </tr>
</table>
<table>
    <tr>
        <td colspan='20' class='morado'>E. TIPO ANESTESIA</td>
    </tr>
    <tr>
        <td class='verde_left' colspan='3'>General:</td>
        <td class='blanco' colspan='1'></td>
        <td class='verde_left' colspan='3'>Local:</td>
        <td class='blanco' colspan='1'></td>
        <td class='verde_left' colspan='3'>Otros:</td>
        <td class='blanco' colspan='1'></td>
        <td class='verde_left' colspan='3'>Regional:</td>
        <td class='blanco' colspan='1'>x</td>
        <td class='verde_left' colspan='3'>Sedación:</td>
        <td class='blanco' colspan='1'></td>
    </tr>
</table>
<table>
    <tr>
        <td colspan='70' class='morado'>F. TIEMPOS QUIRÚRGICOS</td>
    </tr>
    <tr>
        <td colspan='19' rowspan='2' class='verde'>FECHA DE OPERACIÓN</td>
        <td colspan='5' class='verde'>DIA</td>
        <td colspan='5' class='verde'>MES</td>
        <td colspan='5' class='verde'>AÑO</td>
        <td colspan='18' class='verde'>HORA DE INICIO</td>
        <td colspan='18' class='verde'>HORA DE TERMINACIÓN</td>
    </tr>
    <tr>
        <td colspan='5' class='blanco'><?php echo $fechaDia; ?></td>
        <td colspan='5' class='blanco'><?php echo $fechaMes; ?></td>
        <td colspan='5' class='blanco'><?php echo $fechaAno; ?></td>
        <td colspan='18' class='blanco'><?php echo $horaInicio; ?></td>
        <td colspan='18' class='blanco'><?php echo $horaFin; ?></td>
    </tr>
    <tr>
        <td colspan='15' class='verde_left'>Dieresis:</td>
        <td colspan='55' class='blanco_left'><?php echo $dieresis; ?></td>
    </tr>
    <tr>
        <td colspan='15' class='verde_left'>Exposición y Exploración:</td>
        <td colspan='55' class='blanco_left'><?php echo $exposicion; ?></td>
    </tr>
    <tr>
        <td colspan='15' class='verde_left'>Hallazgos Quirúrgicos:</td>
        <td colspan='55' class='blanco_left'><?php echo $hallazgo; ?></td>
    </tr>
    <tr>
        <td colspan='15' class='verde_left'>Procedimiento Quirúrgicos:</td>
        <td colspan='55' class='blanco_left'><?php echo $operatorio; ?></td>
    </tr>
</table>
<table style='border: none'>
    <TR>
        <TD colspan='6 ' HEIGHT=24 ALIGN=LEFT VALIGN=M><B><FONT SIZE=1
                                                                COLOR='#000000 '>SNS-MSP/HCU-form. 017/2021</FONT></B>
        </TD>
        <TD colspan='3 ' ALIGN=RIGHT VALIGN=TOP><B><FONT SIZE=3 COLOR='#000000 '>PROTOCOLO QUIRÚRGICO (1)</FONT></B>
        </TD>
    </TR>
</TABLE>
<pagebreak>
    <table>
        <tr>
            <td colspan='15' class='verde_left'>Procedimiento Quirúrgicos:</td>
            <td colspan='55' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan='70' class='morado'>G. COMPLICACIONES DEL PROCEDIMIENTO QUIRÚRGICO</td>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco_left'></td>
        </tr>
        </tr>
        <tr>
            <td colspan='10' class='verde'>Pérdida Sanguínea total:</td>
            <td colspan='10' class='blanco'></td>
            <td colspan='5' class='blanco'>ml</td>
            <td colspan='10' class='verde'>Sangrado aproximado:</td>
            <td colspan='10' class='blanco'></td>
            <td colspan='5' class='blanco'>ml</td>
            <td colspan='10' class='verde'>Uso de Material Protésico:</td>
            <td colspan='3' class='blanco'>SI</td>
            <td colspan='2' class='blanco'></td>
            <td colspan='3' class='blanco'>NO</td>
            <td colspan='2' class='blanco'></td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan='70' class='morado'>H. EXÁMENES HISTOPATOLÓGICOS</td>
        </tr>
        <tr>
            <td colspan='10' class='verde'>Transquirúrgico:</td>
            <td colspan='60' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='10' class='verde'>Biopsia por congelación:</td>
            <td colspan='3' class='blanco'>SI</td>
            <td colspan='2' class='blanco'></td>
            <td colspan='3' class='blanco'>NO</td>
            <td colspan='2' class='blanco'>X</td>
            <td colspan='10' class='verde'>Resultado:</td>
            <td colspan='40' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='13' class='blanco_left'></td>
            <td colspan='57' class='blanco_left'>Patólogo que reporta:</td>
        </tr>
        <tr>
            <td colspan='10' class='verde'>Histopatológico:</td>
            <td colspan='3' class='blanco'>SI</td>
            <td colspan='2' class='blanco'></td>
            <td colspan='3' class='blanco'>NO</td>
            <td colspan='2' class='blanco'>X</td>
            <td colspan='10' class='verde'>Muestra:</td>
            <td colspan='40' class='blanco_left'></td>
        </tr>
        <tr>
            <td colspan='70' class='blanco'></td>
        </tr>
    </table>
    <table>
        <tr>
            <td class='morado'>I. DIAGRAMA DEL PROCEDIMIENTO</td>
        </tr>
        <tr>
            <td class='blanco' height='100px'>
                <?php
                echo mostrarImagenProcedimiento($idProcedimiento, $mysqli);;
                ?>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td class='morado' colspan='20'>J. DATOS DEL PROFESIONAL RESPONSABLE</td>
        </tr>
        <tr>
            <td class='verde' style='width: 100' colspan='5'>NOMBRE Y APELLIDOS</td>
            <td class='verde' style='width: 100' colspan='5'>ESPECIALIDAD</td>
            <td class='verde' style='width: 100' colspan='5'>FIRMA</td>
            <td class='verde' style='width: 100' colspan='5'>SELLO Y NÚMERO DE DOCUMENTO DE IDENTIFICACIÓN</td>
        </tr>
        <tr>
            <td class='blanco' style='height: 75' colspan='5'><?php echo strtoupper($cirujano_data['nombre']); ?></td>
            <td class='blanco' colspan='5'><?php echo strtoupper($cirujano_data['especialidad']); ?></td>
            <td class='blanco' colspan='5'><?php echo $cirujano_data['cedula']; ?></td>
            <td class='blanco'
                colspan='5'><?php echo "<img src='" . htmlspecialchars($cirujano_data['firma']) . "' alt='Imagen de la firma' style='max-height: 70px;'>";
                ?></td>
        </tr>
        <tr>
            <td class='blanco' style='height: 75' colspan='5'><?php
                if (empty($cirujano2_data['nombre'])) {
                    echo strtoupper($ayudante_data['nombre']);
                } else {
                    echo strtoupper($cirujano2_data['nombre']);
                } ?></td>
            <td class='blanco' colspan='5'><?php
                if (empty($cirujano2_data['especialidad'])) {
                    echo strtoupper($ayudante_data['especialidad']);
                } else {
                    echo strtoupper($cirujano2_data['especialidad']);
                } ?></td>
            <td class='blanco' colspan='5'><?php
                if (empty($cirujano2_data['cedula'])) {
                    echo $ayudante_data['cedula'];
                } else {
                    echo $cirujano2_data['cedula'];
                } ?></td>
            <td class='blanco'
                colspan='5'><?php if (!empty($cirujano2_data['firma'])) {
                    echo "<img src='" . htmlspecialchars($cirujano2_data['firma']) . "' alt='Imagen de la firma' style='max-height: 70px;'>";
                } elseif (empty($cirujano2_data['firma']) && !empty($ayudante_data['firma'])) {
                    echo "<img src='" . htmlspecialchars($ayudante_data['firma']) . "' alt='Imagen de la firma' style='max-height: 70px;'>";
                } else {
                    echo ' ';
                } ?></td>
        </tr>
        <tr>
            <td class='blanco' style='height: 75'
                colspan='5'><?php echo strtoupper($anestesiologo_data['nombre']); ?></td>
            <td class='blanco' colspan='5'><?php echo strtoupper($anestesiologo_data['especialidad']); ?></td>
            <td class='blanco' colspan='5'><?php echo $anestesiologo_data['cedula']; ?></td>
            <td class='blanco'
                colspan='5'><?php echo "<img src='" . htmlspecialchars($anestesiologo_data['firma']) . "' alt='Imagen de la firma' style='max-height: 70px;'>";
                ?></td>
        </tr>
    </table>
    <table style='border: none'>
        <TR>
            <TD colspan='6' HEIGHT=24 ALIGN=LEFT VALIGN=M><B><FONT SIZE=1
                                                                   COLOR='#000000'>SNS-MSP/HCU-form. 017/2021</FONT></B>
            </TD>
            <TD colspan='3' ALIGN=RIGHT VALIGN=TOP><B><FONT SIZE=3 COLOR='#000000'>PROTOCOLO QUIRÚRGICO (2)</FONT></B>
            </TD>
        </TR>
        ]
    </TABLE>
</pagebreak>
</BODY>
