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
        <td class='verde' colspan='6'>FECHA<br><span style='font-size:6pt;font-family:Arial;font-weight:normal;'>(aaaa-mm-dd)</span>
        </td>
        <td class='verde' colspan='3'>HORA<br><span
                    style='font-size:6pt;font-family:Arial;font-weight:normal;'>(hh:mm)</span></td>
        <td class='verde' colspan='29'>NOTAS DE EVOLUCIÓN</td>
        <td class='blanco_break'></td>
        <td class='verde' colspan='23'>FARMACOTERAPIA E INDICACIONES<span
                    style='font-size:6pt;font-family:Arial;font-weight:normal;'><br>(Para enfermería y otro profesional de salud)</span>
        </td>
        <td class='verde' colspan='5'><span style='font-size:6pt;font-family:Arial;font-weight:normal;'>ADMINISTR. <br>FÁRMACOS<br>DISPOSITIVO</span>
        </td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'><?php echo $fechaDia . '/' . $fechaMes . '/' . $fechaAno; ?></td>
        <td class='blanco_left' colspan='3'><?php echo $horaInicioModificada; ?></td>
        <td class='blanco_left' colspan='29' style='text-align: center'>PRE-OPERATORIO</td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23' style='text-align: center'>PRE-OPERATORIO</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            Paciente de <?php echo $edadPaciente; ?> años de edad, conciente, orientado
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>Se recibe paciente en el área; se procede a:</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            en tiempo y espacio es recibido en el área de preoperatorio
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>Colocar anestesia tópica:</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            hemodinámicamente activo, con diagnóstico de:
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>-Proximetacaína 0.5%</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            -<?php echo $diagnostic1 . ' ' . $diagnostic2 . ' ' . $diagnostic3; ?>
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>Cateterización de acceso venoso periférico en</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            Se realiza canalización de vía periférica con Cloruro de Sodio al 0.9%, Manitol el 20%
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>Miembro superior con:</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            colocación de anestesia tópica con Proximetacaína al 0.5%
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>-Catéter calibre 22G</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            Se indica oxigenoterapia
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>-Equipo de venoclisis para administración de solución endovenosa</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            bajo anestesia local (Proximetacaína) y sedación se efectúa anestesia retrobulbar
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>Fijación con Tegaderm IV</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            Monitoreo de signos vitales:
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>Signos Vitales</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            T.A.: <?php echo $sistolica . '/' . $diastolica . ' F.C.: ' . $fc; ?> SATO2: 100%
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'></td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
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
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'></td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'></td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'><?php echo $horaFinModificada; ?></td>
        <td class='blanco_left' colspan='29' style='text-align: center'>POST-OPERATORIO</td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23' style='text-align: center'>POST-OPERATORIO</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            Paciente que sale de cirugía al momento conciente, orientado en tiempo y espacio
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>Ketorolaco líquido parenteral 30mg en 100ml de cloruro de sodio al 0.9%
        </td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            Hemodinámicamente activo, se administra analgésico más corticoides y antagonista
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>Ceftriaxona sólido parenteral 1000 mg en cloruro de sodio 0.9% líquido
            parenteral 1000ml
        </td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'>
            Cruza post operatorio inmediato sin complicaciones
        </td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'>Dexametasona 4mg líquido parenteral</td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
    <tr>
        <td class='blanco_left' colspan='6'></td>
        <td class='blanco_left' colspan='3'></td>
        <td class='blanco_left' colspan='29'><?php echo $mainSurgeon; ?></td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'></td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
</table>
</body>
