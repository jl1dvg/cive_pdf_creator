<?
require '../../../conexion.php';  // Asegúrate de tener la conexión a la base de datos configurada
require '../../../vendor/autoload.php';
require '../../../library/forms.php';


use Dotenv\Dotenv;

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

if ($form_id && $hc_number) {
// Consulta para obtener los datos de patient_data, solicitud_procedimiento y consulta_data
    $sql = "SELECT p.hc_number, p.fname, p.mname, p.lname, p.lname2, p.fecha_nacimiento, p.afiliacion, p.sexo, p.ciudad,
                   sp.id, sp.form_id, sp.tipo, sp.procedimiento, sp.doctor, sp.fecha, sp.duracion, sp.ojo, sp.prioridad, 
                   sp.producto, sp.observacion, sp.created_at, sp.secuencia, cd.motivo_consulta, cd.enfermedad_actual, 
                   cd.examen_fisico, cd.plan, cd.diagnosticos, cd.examenes
            FROM patient_data p
            INNER JOIN solicitud_procedimiento sp ON p.hc_number = sp.hc_number
            LEFT JOIN consulta_data cd ON sp.form_id = cd.form_id AND sp.hc_number = cd.hc_number
            WHERE sp.form_id = ? AND sp.hc_number = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ss', $form_id, $hc_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $procedure_data = $result->fetch_assoc();

        // Asignar los datos de patient_data
        $historyNumber = $procedure_data['hc_number'];
        $fname = $procedure_data['fname'];
        $mname = $procedure_data['mname'];
        $lname = $procedure_data['lname'];
        $lname2 = $procedure_data['lname2'];
        $birthDate = $procedure_data['fecha_nacimiento'];
        $gender = $procedure_data['sexo'];
        $insurance = $procedure_data['afiliacion'];
        $city = $procedure_data['ciudad'];

        // Asignar los datos de solicitud_procedimiento
        $procedureId = $procedure_data['id'];
        $formId = $procedure_data['form_id'];
        $procedureType = $procedure_data['tipo'];

        $procedimiento_parts = explode(' - ', $procedure_data['procedimiento']);
        $nombre_procedimiento = ucwords(strtolower(end($procedimiento_parts)));

        $doctor = $procedure_data['doctor'];
        $procedureDate = $procedure_data['fecha'];
        $duration = $procedure_data['duracion'];
        $eye = $procedure_data['ojo'];
        $priority = $procedure_data['prioridad'];
        $product = $procedure_data['producto'];
        $observation = $procedure_data['observacion'];
        $createdAt = $procedure_data['created_at'];
        $sequence = $procedure_data['secuencia'];

        // Asignar los datos de consulta_data
        $motivoConsulta = $procedure_data['motivo_consulta'];
        $enfermedadActual = $procedure_data['enfermedad_actual'];
        $examenFisico = 'Motivo de consulta:' . $procedure_data['motivo_consulta'] . '. Enfermedad actual:' . $procedure_data['enfermedad_actual'] . '. ' . $procedure_data['examen_fisico'];
        $plan = $procedure_data['plan'];
        $diagnoses = $procedure_data['diagnosticos'];
        $examenes = $procedure_data['examenes'];

        // 1. Decodificar JSON de diagnósticos
        $diagnosesArray = json_decode($diagnoses, true);

        // Asignar diagnósticos y códigos CIE-10 a variables separadas
        $diagnostic1CIE10 = '';
        $diagnostic1Detail = '';
        if (!empty($diagnosesArray[0]['idDiagnostico'])) {
            $parts = explode(' - ', $diagnosesArray[0]['idDiagnostico'], 2);
            $diagnostic1CIE10 = $parts[0] ?? '';
            $diagnostic1Detail = $parts[1] ?? '';
        }

        $diagnostic2CIE10 = '';
        $diagnostic2Detail = '';
        if (!empty($diagnosesArray[1]['idDiagnostico'])) {
            $parts = explode(' - ', $diagnosesArray[1]['idDiagnostico'], 2);
            $diagnostic2CIE10 = $parts[0] ?? '';
            $diagnostic2Detail = $parts[1] ?? '';
        }

        $diagnostic3CIE10 = '';
        $diagnostic3Detail = '';
        if (!empty($diagnosesArray[2]['idDiagnostico'])) {
            $parts = explode(' - ', $diagnosesArray[2]['idDiagnostico'], 2);
            $diagnostic3CIE10 = $parts[0] ?? '';
            $diagnostic3Detail = $parts[1] ?? '';
        }

        // 2. Decodificar JSON de exámenes si es necesario
        $examenesArray = json_decode($examenes, true);

        // 3. Calcular la edad del paciente
        $birthDateObj = new DateTime($birthDate);
        $procedureDateObj = new DateTime($procedureDate);
        $edadPaciente = $birthDateObj->diff($procedureDateObj)->y;

        // 4. Separar la fecha y la hora de created_at
        $createdAtObj = new DateTime($createdAt);
        $createdAtDate = $createdAtObj->format('Y/m/d');
        $createdAtTime = $createdAtObj->format('H:i');

        $cirujano_data = buscarUsuarioPorNombre($doctor, $mysqli);
    }
}

if (!function_exists('generateEnfermedadProblemaActual')) {
    function generateEnfermedadProblemaActual($examenFisico)
    {
        $apiKey = $_ENV['OPENAI_API_KEY'] ?? getenv('OPENAI_API_KEY');

        if (!$apiKey) {
            die('API key is missing. Please set your OpenAI API key.');
        }

        // Definir el prompt mejorado
        $prompt = "
    Examen físico oftalmológico: $examenFisico

Redacta los hallazgos del examen físico de manera profesional, clara y sintetizada. Sigue este esquema y considera las siguientes instrucciones:

1. Combina el Motivo de consulta y enfermedad actual en una sola frase concisa que describa de manera específica la razón de la consulta y la situación actual del paciente. Evita frases introductorias como 'Motivo de consulta:' o 'Enfermedad actual:'.
2. Biomicroscopia: Presenta los hallazgos separados por ojo con las siglas OD y OI exclusivamente. Si no se menciona un ojo, omítelo. Usa frases completas y bien estructuradas.
3. Fondo de Ojo: Incluye únicamente si hay detalles reportados. Si no se mencionan hallazgos, no lo incluyas.
4. PIO: Si está disponible, escribe la presión intraocular en el formato OD/OI (por ejemplo, 18/18.5). Si no está reportada, omítela.

Instrucciones adicionales:
- Usa mayúsculas y minúsculas correctamente; solo usa siglas para OD, OI y PIO.
- No incluyas secciones vacías ni detalles no reportados.
- Sintetiza la información eliminando redundancias y enfocándote en lo relevante.
- Evita líneas separadas para frases importantes; presenta la información de forma continua y bien organizada.
- No inventes datos; si algo no está claro, simplemente no lo incluyas.

Ejemplo de formato esperado:
[Frase que combine el motivo de consulta y la enfermedad actual.]
Biomicroscopia: OD: [detalles]. OI: [detalles]. 
Fondo de Ojo: OD: [detalles]. OI: [detalles]. 
PIO: [valor].

Utiliza este esquema para el análisis.
";

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un médico oftalmólogo que está redactando una referencia detallada para un paciente que necesita cirugía.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 200
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/json\r\nAuthorization: Bearer $apiKey\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
                'ignore_errors' => true // Capturar errores HTTP
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents('https://api.openai.com/v1/chat/completions', false, $context);

        if ($response === FALSE) {
            $error = error_get_last();
            die('Error occurred: ' . $error['message']);
        }

        $responseData = json_decode($response, true);

        if (isset($responseData['error'])) {
            die('API Error: ' . $responseData['error']['message']);
        }

        return $responseData['choices'][0]['message']['content'];
    }
}
if (!function_exists('generatePlanTratamiento')) {
    function generatePlanTratamiento($plan, $insurance)
    {
        $apiKey = $_ENV['OPENAI_API_KEY'] ?? getenv('OPENAI_API_KEY');

        if (!$apiKey) {
            die('API key is missing. Please set your OpenAI API key.');
        }

        $prompt = "
Plan de tratamiento basado en la evaluación oftalmológica: $plan

Redacta un plan de tratamiento breve, claro y profesional, respetando el siguiente formato y estilo:

1. **Procedimientos:** Enumera exclusivamente los procedimientos quirúrgicos necesarios. Usa frases directas y justificaciones breves si es relevante y evita colocar fechas.
2. **Exámenes prequirúrgicos y valoración cardiológica:** Incluye esta sección con el siguiente contexto: 'Se solicita a $insurance autorización para valoración y tratamiento integral en cardiología y electrocardiograma.'

Instrucciones adicionales:
- Usa mayúsculas y minúsculas correctamente tipo oración. Esto significa que solo las iniciales de los nombres propios y términos específicos deben estar en mayúscula. No escribas todo en mayúsculas.
- Presenta la información de manera directa y estructurada en listas o frases cortas, sin introducir explicaciones extensas ni repeticiones.
- Evita incluir secciones vacías o inventar información; solo menciona datos presentes en el plan proporcionado.
- Omite encabezados si no hay contenido relevante en esa sección.

Ejemplo de formato esperado:
[Procedimiento 1].[Procedimiento 2].

Exámenes prequirúrgicos y valoración cardiológica:
- Se solicita a [aseguradora] autorización para valoración y tratamiento integral en cardiología y electrocardiograma.
";

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un médico oftalmólogo redactando un plan de tratamiento profesional basado en un análisis clínico.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 300
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/json\r\nAuthorization: Bearer $apiKey\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
                'ignore_errors' => true // Capturar errores HTTP
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents('https://api.openai.com/v1/chat/completions', false, $context);

        if ($response === FALSE) {
            $error = error_get_last();
            die('Error occurred: ' . $error['message']);
        }

        $responseData = json_decode($response, true);

        if (isset($responseData['error'])) {
            die('API Error: ' . $responseData['error']['message']);
        }

        return $responseData['choices'][0]['message']['content'];
    }
}
?>
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
        <colgroup>
            <col class="xl76" span="71">
        </colgroup>
        <tr>
            <td colspan="71" class="morado">B. CUADRO CLÍNICO DE INTERCONSULTA</td>
        </tr>
        <tr>
            <td colspan="71" class="blanco_left"><?php
                $reason = $motivoConsulta . ' ' . $enfermedadActual;
                echo wordwrap($reason, 140, "</td>
    </tr>
    <tr>
        <td colspan=\"71\" class=\"blanco_left\">"); ?></td>
        </tr>
        <tr>
            <td colspan="71" class="blanco_left"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="morado">C. RESUMEN DEL CRITERIO CLÍNICO</td>
        </tr>
        <tr>
            <td class="blanco_left">
                <?php
                $examenAI = generateEnfermedadProblemaActual($examenFisico);
                echo wordwrap($examenAI, 150, "</TD></TR><TR><TD class='blanco_left'>");
                ?>
            </td>
        </tr>
    </table>
<?php
// Generar la tabla con el nuevo formato para imprimir diagnósticos
// Inicializar variables de control
$totalItems = count($diagnosesArray);
$rows = max(ceil($totalItems / 2), 3); // Asegurarse de que haya al menos 3 filas por columna

// Crear la tabla HTML
echo "<table>";
// Encabezado de la tabla
echo "<tr>
    <td class='morado' width='2%'>D.</td>
    <td class='morado' width='17.5%'>DIAGNÓSTICOS</td>
    <td class='morado' width='17.5%' style='font-weight: normal; font-size: 6pt'>PRE= PRESUNTIVO DEF= DEFINITIVO</td>
    <td class='morado' width='6%' style='font-size: 6pt; text-align: center'>CIE</td>
    <td class='morado' width='3.5%' style='font-size: 6pt; text-align: center'>PRE</td>
    <td class='morado' width='3.5%' style='font-size: 6pt; text-align: center'>DEF</td>
    <td class='morado' width='2%'><br></td>
    <td class='morado' width='17.5%'><br></td>
    <td class='morado' width='17.5%'><br></td>
    <td class='morado' width='6%' style='font-size: 6pt; text-align: center'>CIE</td>
    <td class='morado' width='3.5%' style='font-size: 6pt; text-align: center'>PRE</td>
    <td class='morado' width='3.5%' style='font-size: 6pt; text-align: center'>DEF</td>
</tr>";

// Generar filas para los diagnósticos
for ($i = 0; $i < $rows; $i++) {
    $leftIndex = $i * 2;
    $rightIndex = $leftIndex + 1;

    echo "<tr>";

    // Columna izquierda
    if ($leftIndex < $totalItems) {
        $partsLeft = explode(' - ', $diagnosesArray[$leftIndex]['idDiagnostico'], 2);
        $cie10Left = $partsLeft[0] ?? '';
        $detalleLeft = $partsLeft[1] ?? '';

        echo "<td class='verde'>" . ($leftIndex + 1) . "</td>";
        echo "<td colspan='2' class='blanco' style='text-align: left'>" . htmlspecialchars($detalleLeft) . "</td>";
        echo "<td class='blanco'>" . htmlspecialchars($cie10Left) . "</td>";
        echo "<td class='amarillo'></td>";
        echo "<td class='amarillo'>x</td>";
    } else {
        echo "<td class='verde'>" . ($leftIndex + 1) . "</td><td colspan='2' class='blanco'></td><td class='blanco'></td><td class='amarillo'></td><td class='amarillo'></td>";
    }

    // Columna derecha
    if ($rightIndex < $totalItems) {
        $partsRight = explode(' - ', $diagnosesArray[$rightIndex]['idDiagnostico'], 2);
        $cie10Right = $partsRight[0] ?? '';
        $detalleRight = $partsRight[1] ?? '';

        echo "<td class='verde'>" . ($rightIndex + 1) . "</td>";
        echo "<td colspan='2' class='blanco' style='text-align: left'>" . htmlspecialchars($detalleRight) . "</td>";
        echo "<td class='blanco'>" . htmlspecialchars($cie10Right) . "</td>";
        echo "<td class='amarillo'></td>";
        echo "<td class='amarillo'>x</td>";
    } else {
        echo "<td class='verde'>" . ($rightIndex + 1) . "</td><td colspan='2' class='blanco'></td><td class='blanco'></td><td class='amarillo'></td><td class='amarillo'></td>";
    }

    echo "</tr>";
}

// Cerrar la tabla
echo "</table>";
?>
    <table>
        <tr>
            <td class="morado">E. PLAN DE DIAGNÓSTICO PROPUESTO</td>
        </tr>
        <tr>
            <td class="blanco" style="border-right: none; text-align: left">
                <?php
                echo wordwrap($plan, 140, "</TD></TR><TR><TD class='blanco_left'>");
                ?>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="71" class="morado">F. PLAN TERAPEÚTICO PROPUESTO</td>
        </tr>
        <tr>
            <td colspan="71" class="blanco_left">
                <?php
                if ($eye == 'D') {
                    $eye = 'ojo derecho.';
                } elseif ($eye == 'I') {
                    $eye = 'ojo izquierdo';
                }
                $planAI = $nombre_procedimiento . ' en ' . $eye . '. Se solicita a' . $insurance . ' autorización para realización de exámenes prequirúrgicos, valoración y tratamiento integral en cardiología y electrocardiograma.';
                echo wordwrap($planAI, 150, "</TD></TR><TR><TD colspan=71 class='blanco_left'>");
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="71" class="blanco" style="border-right: none; text-align: left"></td>
        </tr>
        <tr>
            <td colspan="71" class="blanco" style="border-right: none; text-align: left"></td>
        </tr>
        <tr>
            <td colspan="71" class="blanco" style="border-right: none; text-align: left"></td>
        </tr>
        <tr>
            <td colspan="71" class="blanco" style="border-right: none; text-align: left"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td colspan="71" class="morado">G. DATOS DEL PROFESIONAL RESPONSABLE</td>
        </tr>
        <tr class="xl78">
            <td colspan="8" class="verde">FECHA<br>
                <font class="font5">(aaaa-mm-dd)</font>
            </td>
            <td colspan="7" class="verde">HORA<br>
                <font class="font5">(hh:mm)</font>
            </td>
            <td colspan="21" class="verde">PRIMER NOMBRE</td>
            <td colspan="19" class="verde">PRIMER APELLIDO</td>
            <td colspan="16" class="verde">SEGUNDO APELLIDO</td>
        </tr>
        <tr>
            <td colspan="8" class="blanco"><?php echo htmlspecialchars($createdAtDate); ?></td>
            <td colspan="7" class="blanco"><?php echo htmlspecialchars($createdAtTime); ?></td>
            <td colspan="21" class="blanco"><?php echo htmlspecialchars($cirujano_data['nombre']); ?></td>
            <td colspan="19" class="blanco"></td>
            <td colspan="16" class="blanco"></td>
        </tr>
        <tr>
            <td colspan="15" class="verde">NÚMERO DE DOCUMENTO DE IDENTIFICACIÓN</td>
            <td colspan="26" class="verde">FIRMA</td>
            <td colspan="30" class="verde">SELLO</td>
        </tr>
        <tr>
            <td colspan="15" class="blanco"
                style="height: 40px"><?php echo htmlspecialchars($cirujano_data['cedula']); ?></td>
            <td colspan="26" class="blanco">&nbsp;</td>
            <td colspan="30" class="blanco">&nbsp;</td>
        </tr>
    </table>
    <table style="border: none">
        <TR>
            <TD colspan="6" HEIGHT=24 ALIGN=LEFT VALIGN=TOP><B><FONT SIZE=1
                                                                     COLOR="#000000">SNS-MSP/HCU-form.007/2021</FONT></B>
            </TD>
            <TD colspan="3" ALIGN=RIGHT VALIGN=TOP><B><FONT SIZE=3 COLOR="#000000">INTERCONSULTA -
                        INFORME</FONT></B>
            </TD>
        </TR>
        ]
    </TABLE>
