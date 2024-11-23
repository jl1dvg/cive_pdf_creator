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
        <td class="morado" colspan="24">B. CONSTANTES VITALES</td>
    </tr>
    <tr>
        <td class="verde" colspan="3">FECHA</td>
        <td class="blanco_left" colspan="3"><?php echo $fechaDia . '/' . $fechaMes . '/' . $fechaAno; ?></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
    </tr>
    <tr>
        <td class="verde" colspan="3">DÍA DE INTERNACIÓN</td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
    </tr>
    <tr>
        <td class="verde" colspan="3">DÍA POST QUIRÚRGICO</td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
        <td class="blanco_left" colspan="3"></td>
    </tr>
    <tr>
        <td class="verde" rowspan="2">PULSO</td>
        <td class="verde" rowspan="2">TEMP</td>
        <td class="verde"></td>
        <td class="verde">AM</td>
        <td class="verde">PM</td>
        <td class="verde">HS</td>
        <td class="verde">AM</td>
        <td class="verde">PM</td>
        <td class="verde">HS</td>
        <td class="verde">AM</td>
        <td class="verde">PM</td>
        <td class="verde">HS</td>
        <td class="verde">AM</td>
        <td class="verde">PM</td>
        <td class="verde">HS</td>
        <td class="verde">AM</td>
        <td class="verde">PM</td>
        <td class="verde">HS</td>
        <td class="verde">AM</td>
        <td class="verde">PM</td>
        <td class="verde">HS</td>
        <td class="verde">AM</td>
        <td class="verde">PM</td>
        <td class="verde">HS</td>
    </tr>
    <tr>
        <td class="verde" rowspan="2">HORA</td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
        <td class="blanco_left" rowspan="2"></td>
    </tr>
    <tr>
        <td class="verde" rowspan="2">HORA</td>
        <td class="verde" rowspan="2">HORA</td>
    </tr>
    <tr>
        <td class="verde"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td rowspan="2" class="cyan_left" style="border: none">140</td>
        <td rowspan="2" class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td rowspan="2" class="cyan_left" style="border: none">130</td>
        <td rowspan="2" class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td rowspan="2" class="cyan_left" style="border: none">120</td>
        <td rowspan="2" class="cyan_left" style="border: none">42</td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td rowspan="2" class="cyan_left" style="border: none">110</td>
        <td rowspan="2" class="cyan_left" style="border: none">41</td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr><tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td rowspan="2" class="cyan_left" style="border: none">100</td>
        <td rowspan="2" class="cyan_left" style="border: none">40</td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td rowspan="2" class="cyan_left" style="border: none">90</td>
        <td rowspan="2" class="cyan_left" style="border: none">39</td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td rowspan="2" class="cyan_left" style="border: none">80</td>
        <td rowspan="2" class="cyan_left" style="border: none">38</td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td rowspan="2" class="cyan_left" style="border: none">70</td>
        <td rowspan="2" class="cyan_left" style="border: none">37</td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td rowspan="2" class="cyan_left" style="border: none">60</td>
        <td rowspan="2" class="cyan_left" style="border: none">36</td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left" style="border: none"></td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td rowspan="2" class="cyan_left" style="border: none">50</td>
        <td rowspan="2" class="cyan_left" style="border: none">35</td>
        <td class="cyan_left"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
    <tr>
        <td class="cyan_left" style="border-top: 2px solid #808080"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
        <td class="blanco_left_remini"></td>
    </tr>
</table>
<table>
    <tr>
        <td class="cyan_left" width="14.5%">F. RESPIRATORIA X min</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">PULSIOXIMETRÍA %</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">PRESIÓN SISTÓLICA</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">PRESIÓN DIASTÓLICA</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">RESPONSABLE</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
</table>
<table>
    <tr>
        <td colspan='8' class='morado'>C. MEDIDAS ANTROPOMÉTRICAS</td>
    </tr>
    <tr>
        <td class='cyan_left' width="14.5%">PESO (kg)</td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
    </tr>
    <tr>
        <td class='cyan_left'>TALLA (cm)</td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
    </tr>
    <tr>
        <td class='cyan_left'>PERÍMETRO CEFÁLICO (cm)</td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
    </tr>
    <tr>
        <td class='cyan_left'>PERÍMETRO ABDOMINAL (cm)</td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
    </tr>
    <tr>
        <td class='cyan_left'>OTROS ESPECIFIQUE</td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
        <td class='blanco_left_mini'></td>
    </tr>
</table>
<table>
    <tr>
        <td class="morado" colspan="9">D. INGESTA - ELIMINACIÓN / BALANCE HÍDRICO</td>
    </tr>
    <tr>
        <td class="cyan_left" rowspan="4" width="2%">INGRESOS ML</td>
        <td class="cyan_left" width="12.5%">ENTERAL</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">PARENTERAL</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">VÍA ORAL</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">TOTAL</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left" rowspan="6">ELIMINACIONES ML</td>
        <td class="cyan_left">ORINA</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">DRENAJE</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">VÓMITO</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">DIARREAS</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">OTROS ESPECIFIQUE</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">TOTAL</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left" colspan="2"><b>BALANCE HÍDRICO TOTAL</b></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left" colspan="2">DIETA PRESCRITA</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left" colspan="2">NÚMERO DE COMIDAS</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left" colspan="2">NÚMERO DE MICCIONES</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left" colspan="2">NÚMERO DE DEPOSICIONES</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
</table>
<table>
    <tr>
        <td class="morado" colspan="8">E. CUIDADOS GENERALES</td>
    </tr>
    <tr>
        <td class="cyan_left" width="12.5%">ASEO</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">BAÑO</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">REPOSO ESPECIFIQUE</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">POSICIÓN ESPECIFIQUE</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">OTROS ESPECIFIQUE</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
</table>
<table>
    <tr>
        <td class="morado" colspan="8">F. FECHA DE COLOCACIÓN DE DISPOSITIVOS MÉDICOS (aaaa-mm-dd)</td>
    </tr>
    <tr>
        <td class="cyan_left" width="12.5%">VÍA CENTRAL</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">VÍA PERIFÉRICA</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">SONDA NASOGÁSTRICA</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">SONDA VESICAL</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">OTROS ESPECIFIQUE</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
    <tr>
        <td class="cyan_left">RESPONSABLE</td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
        <td class="blanco_left_mini"></td>
    </tr>
</table>
<table style='border: none'>
    <TR>
        <TD colspan='6' HEIGHT=24 ALIGN=LEFT VALIGN=M><B><FONT SIZE=1
                                                               COLOR='#000000'>SNS-MSP/HCU-form.020/2021</FONT></B>
        </TD>
        <TD colspan='3' ALIGN=RIGHT VALIGN=TOP><B><FONT SIZE=3 COLOR='#000000'>CONSTANTES VITALES / BALANCE HÍDRICO (1)</FONT></B>
        </TD>
    </TR>
    ]
</TABLE>
</BODY>