<?
require '../../../conexion.php';  // Asegúrate de tener la conexión a la base de datos configurada
require '../../../vendor/autoload.php';


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

        // 4. Separar la fecha y la hora de created_at
        $createdAtObj = new DateTime($createdAt);
        $createdAtDate = $createdAtObj->format('Y/m/d');
        $createdAtTime = $createdAtObj->format('H:i');

        // 3. Calcular la edad del paciente
        $birthDateObj = new DateTime($birthDate);
        $procedureDateObj = new DateTime($createdAtDate);
        $edadPaciente = $birthDateObj->diff($procedureDateObj)->y;

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
<HTML>
<BODY>
<table>
    <TR>
        <TD class="morado" colspan="12">l. DATOS DEL USARIO</TD>
    </TR>
    <TR>
        <TD class="verde" COLSPAN=2 rowspan="2">APELLIDO PATERNO</TD>
        <TD class="verde" COLSPAN=2 rowspan="2">APELLIDO MATERNO</TD>
        <TD class="verde" COLSPAN=3 rowspan="2">NOMBRES</TD>
        <TD class="verde" COLSPAN=3>Fecha de Nacimiento</TD>
        <TD class="verde" rowspan="2">EDAD</TD>
        <TD class="verde">SEXO</TD>
    </TR>
    <TR>
        <TD class="verde">Dia</TD>
        <TD class="verde">Mes</TD>
        <TD class="verde">A&ntilde;o</TD>
        <TD class="verde">H/M</TD>
    </TR>
    <TR>
        <TD class="blanco" colspan="2"><?php echo($lname) ?></TD>
        <TD class="blanco" COLSPAN=2><?php echo($lname2) ?></TD>
        <TD class="blanco" colspan="3"><?php echo ($fname) . " " . ($mname) ?>
        </TD>
        <TD class="blanco"><?php echo date("d", strtotime($birthDate)); ?>
        </TD>
        <TD class="blanco"><?php echo date("m", strtotime($birthDate)); ?>
        </TD>
        <TD class="blanco"><?php echo date("Y", strtotime($birthDate)); ?>
        </TD>
        <TD class="blanco">
            <?php
            echo $edadPaciente;
            ?>
        </TD>
        <TD class="blanco">
            <?php echo $gender; ?></TD>
    </TR>
    <TR>
        <TD class="verde" colspan="2" rowspan="2">NACIONALIDAD</TD>
        <TD class="verde" COLSPAN=2 rowspan="2">PAIS</TD>
        <TD class="verde" COLSPAN=2 rowspan="2">CEDULA O PASAPORTE</TD>
        <TD class="verde" COLSPAN=3>LUGAR DE RESIDENCIA</TD>
        <TD class="verde" COLSPAN=3 rowspan="2">DIRECCION DE DOMICILIO</TD>
    </TR>
    <TR>
        <TD class="verde">Prov.</TD>
        <TD class="verde">Canton</TD>
        <TD class="verde">Parroq.</TD>
    </TR>
    <TR>
        <TD class="blanco" colspan="2">ECUATORIANA</TD>
        <TD class="blanco" COLSPAN=2>ECUADOR</TD>
        <TD class="blanco" COLSPAN=2><?php echo $hc_number ?></TD>
        <TD class="blanco"><BR></TD>
        <TD class="blanco"><BR></TD>
        <TD class="blanco"><BR></TD>
        <TD class="blanco" COLSPAN=3><BR></TD>
    </TR>
    <TR>
        <TD class="verde">E-MAIL:</TD>
        <TD class="blanco" COLSPAN=3><BR></TD>
        <TD class="verde">TELEFONO:</TD>
        <TD class="blanco" COLSPAN=3></TD>
        <TD class="verde">FECHA:</TD>
        <TD class="blanco" COLSPAN=3><BR></TD>
    </TR>
</table>
<table>
    <TR>
        <TD class="verde" width="40%">ll. REFERENCIA 1</TD>
        <TD class="blanco" width="10%"><BR></TD>
        <TD class="verde" width="40%"> DERIVACION 2</TD>
        <TD class="blanco" width="10%">X</TD>
    </TR>
</table>
<table>
    <TR>
        <TD COLSPAN=12 CLASS="morado">1 DATOS INSTITUCIONALES</TD>
    </TR>
    <TR>
        <TD class="verde" colspan="2">ENTIDAD DEL SISTEMA</TD>
        <TD class="verde" colspan="2">HISTORIA CLINICA</TD>
        <TD class="verde" COLSPAN=3>ESTABLECIMIENTO DE SALUD</TD>
        <TD class="verde" COLSPAN=2>TIPO</TD>
        <TD class="verde" COLSPAN=3>DISTRITO /AREA</TD>
    </TR>
    <TR>
        <TD class="blanco" COLSPAN=2></TD>
        <TD class="blanco" COLSPAN=2></TD>
        <TD class="blanco" COLSPAN=3></TD>
        <TD class="blanco" COLSPAN=2><BR></TD>
        <TD class="blanco" COLSPAN=3><BR></TD>
    </TR>
    <TR>
        <TD class="verde" COLSPAN=8>REFIERE O DERIVA A:</TD>
        <TD class="verde" COLSPAN=4>FECHA</TD>
    </TR>
    <TR>
        <TD class="verde" COLSPAN=2>Entidad del Sistema</TD>
        <TD class="verde" COLSPAN=2>Establecimiento de Salud</TD>
        <TD class="verde" colspan="2">Servico</TD>
        <TD class="verde" COLSPAN=2>Especialidad</TD>
        <TD class="verde">Dia</TD>
        <TD class="verde">Mes</TD>
        <TD class="verde" colspan="2">A&ntilde;o</TD>
    </tr>
    <TR>
        <TD class="blanco" COLSPAN=2></TD>
        <TD class="blanco" COLSPAN=2></TD>
        <TD class="blanco" colspan="2">AMBULATORIO</TD>
        <TD class="blanco" COLSPAN=2></TD>
        <TD class="blanco">
        </TD>
        <TD class="blanco">
        </TD>
        <TD class="blanco" colspan="2">
        </TD>
    </TR>
</table>
<table>
    <TR>
        <TD COLSPAN=12 class="morado">2. MOTIVO DE LA REFERENCIA O DERIVACION</TD>
    </TR>
    <TR>
        <TD class="blanco" COLSPAN=4 width="40%">LIMITADA CAPACIDAD RESOLUTIVA</TD>
        <TD class="blanco" width="5%">1</TD>
        <TD class="blanco" width="5%"><BR></TD>
        <TD class="blanco" COLSPAN=4 width="40%">SATURACION DE CAPACIDAD INSTALADA</TD>
        <TD class="blanco" width="5%">4</TD>
        <TD class="blanco" width="5%"><BR></TD>
    </TR>
    <TR>
        <TD class="blanco" COLSPAN=4>AUSENCIA DEL PROFESIONAL</TD>
        <TD class="blanco">2</TD>
        <TD class="blanco"><BR></TD>
        <TD class="blanco" COLSPAN=4>CONTINUAR TRATAMIENTO</TD>
        <TD class="blanco">5</TD>
        <TD class="blanco"></TD>
    </TR>
    <tr>
        <TD class="blanco" COLSPAN=4>FALTA DEL PROFESIONAL</TD>
        <TD class="blanco">3</TD>
        <TD class="blanco"><BR></TD>
        <TD class="blanco" COLSPAN=4>OTROS ESPECIFIQUE</TD>
        <TD class="blanco"><br></TD>
        <TD class="blanco"><BR></TD>
    </tr>
</table>
<table>
    <TR>
        <TD class="morado">3. RESUMEN DEL CUADRO CLINICO</TD>
    </TR>
    <tr>
        <td class='blanco_left'></td>
    </tr>
</table>
<table>
    <TR>
        <TD class="morado">4. HALLAZGOS RELEVANTES DE EXAMENES Y PROCEDIMIENTOS DIAGNOSTICOS</TD>
    </TR>
    <TR>
        <TD class="blanco_left"></TD>
    </TR>
</table>
<table>
    <TR>
        <TD class="morado" width="55%">5. DIAGNOSTICO</TD>
        <TD class="morado" width="15%">CIE- 10</TD>
        <TD class="morado" width="15%">PRE</TD>
        <TD class="morado" width="15%">DEF</TD>
    </TR>
    <tr>
        <td class="blanco"></td>
        <td class="blanco"></td>
        <td class="blanco"></td>
        <td class="blanco"></td>
    </tr>
</table>
<table>
    <TR>
        <TD class="morado" width="80%">6. EXAMENES / PROCEDIMIENTOS SOLICITADOS</TD>
        <TD class="morado" width="20%">CODIGO TARIFARIO</TD>
    </TR>
    <tr>
        <td class="blanco_left"></td>
        <td class="blanco_left"></td>
    </tr>
</table>
<table>
    <TR>
        <TD class="blanco" width="28%"></TD>
        <TD class="blanco" width="16%"></TD>
        <TD class="blanco" width="28%"></TD>
        <TD class="blanco" width="28%"></TD>
    </TR>
    <TR>
        <TD class="verde">NOMBRE</TD>
        <TD class="verde">COD. MSP. PROF.</TD>
        <TD class="verde">DIRECTOR MEDICO</TD>
        <TD class="verde">MEDICO VERIFICADOR</TD>
    </TR>
</table>
<TABLE>
    <TR>
        <TD COLSPAN=12 class="morado">1. DATOS INSTITUCIONALES</TD>
    </TR>
    <TR>
        <TD class="verde" colspan="2">ENTIDAD DEL SISTEMA</TD>
        <TD class="verde" COLSPAN=2>HIST, CLINICA #</TD>
        <TD class="verde" COLSPAN=2>ESTABLECIMIENTO</TD>
        <TD class="verde">TIPO</TD>
        <TD class="verde" COLSPAN=2>SERVICIO</TD>
        <TD class="verde" COLSPAN=3>ESPECIALIDAD</TD>
    </TR>
    <TR>
        <TD class="blanco" COLSPAN=2><BR></TD>
        <TD class="blanco" COLSPAN=2><?php echo $hc_number ?></TD>
        <TD class="blanco" COLSPAN=2>CLINICA INTERNACIONAL DE LA VISION DE ECUADOR</TD>
        <TD class="blanco">III</TD>
        <TD class="blanco" COLSPAN=2><BR></TD>
        <TD class="blanco" COLSPAN=3><BR></TD>
    </TR>
    <TR>
        <TD class="verde" colspan="4">lll. CONTRAREFERENCIA 3</TD>
        <TD class="blanco">X</TD>
        <TD class="verde" colspan="3">REFERENCIA INVERSA 4</TD>
        <TD class="blanco"><BR></TD>
        <TD class="verde" COLSPAN=3>FECHA
        </TD>
    </TR>
    <TR>
        <TD class="blanco" colspan="2"><?php echo $insurance; ?></TD>
        <TD class="blanco" COLSPAN=3><BR></TD>
        <TD class="blanco"><BR></TD>
        <TD class="blanco" COLSPAN=3><BR></TD>
        <TD class="blanco"><?php
            echo date("d", strtotime($createdAtDate));
            ?></TD>
        <TD class="blanco"><?php
            echo date("m", strtotime($createdAtDate));
            ?></TD>
        <TD class="blanco"><?php
            echo date("Y", strtotime($createdAtDate));
            ?></TD>
    </TR>
    <TR>
        <TD class="verde" COLSPAN=2>Entidad del Sistema</TD>
        <TD class="verde" COLSPAN=3>Establecimiento de Salud</TD>
        <TD class="verde">Tipo</TD>
        <TD class="verde" COLSPAN=3>Districto/Area</TD>
        <TD class="verde">Dia</TD>
        <TD class="verde">Mes</TD>
        <TD class="verde">A&ntilde;o</TD>
    </TR>
</TABLE>
<table>
    <TR>
        <TD COLSPAN=12 class="morado">2. RESUMEN DEL CUADRO CLINICO</TD>
    </TR>
    <TR>
        <TD colspan="12" class="blanco_left">
            <?php
            $examenAI = generateEnfermedadProblemaActual($examenFisico);
            echo wordwrap($examenAI, 150, "</TD></TR><TR><TD colspan=12 class='blanco_left'>");
            ?>
        </td>
    </tr>
</table>
<table>
    <TR>
        <TD COLSPAN=12 class="morado">3. HALLAZGOS RELEVANTES DE EXAMENES Y PROCEDIMIENTOS DIAGNOSTICOS</TD>
    </TR>
    <TR>
        <TD colspan="12" class="blanco_left">
        </TD>
    </TR>
</table>
<table>
    <TR>
        <TD COLSPAN=12 class="morado">4. TRATAMIENTOS Y PROCEDIMIENTOS TERAPEUTICOS REALIZADOS</TD>
    </TR>
    <TR>
        <TD colspan="12" class="blanco_left">
            <?php
            if ($eye == 'D') {
                $eye = 'ojo derecho.';
            } elseif ($eye == 'I') {
                $eye = 'ojo izquierdo';
            }
            $planAI = $nombre_procedimiento . ' en ' . $eye . '. Se solicita a' . $insurance . ' autorización para realización de exámenes prequirúrgicos, valoración y tratamiento integral en cardiología y electrocardiograma.';
            echo wordwrap($planAI, 150, "</TD></TR><TR><TD colspan=12 class='blanco_left'>");
            ?>
        </TD>
    </TR>
</table>
<table>
    <TR>
        <TD class="morado">5. DIAGNOSTICO</TD>
        <TD class="morado">CIE-10</TD>
        <TD class="morado">PRE</TD>
        <TD class="morado">DEF</TD>
    </TR>
    <?php
    foreach ($diagnosesArray as $index => $item) {
        $parts = explode(' - ', $item['idDiagnostico'], 4);
        $cie10 = $parts[0] ?? '';
        $detalle = $parts[1] ?? '';
        echo "<tr>
            <TD CLASS='blanco_left'>" . htmlspecialchars($detalle) . "</td>
            <TD CLASS='blanco'>" . htmlspecialchars($cie10) . "</td>
            <TD CLASS='blanco'><BR></TD>
            <TD CLASS='blanco'>X</TD>
          </tr>";
    }
    ?>
</table>
<table>
    <TR>
        <TD class="morado">6.
            TRATAMIENTO RECOMENDADO A SEGUIR EN EL ESTABLECIMIENTO DE SALUD DE MENOR NIVEL DE COMPLEJIDAD
        </TD>
    </TR>
    <TR>
        <TD class="blanco_left"></TD>
    </TR>
</TABLE>
<table>
    <TR>
        <TD class="blanco" COLSPAN=4><?php echo strtoupper($cirujano_data['nombre']); ?></TD>
        <TD class="blanco" COLSPAN=4><?php echo strtoupper($cirujano_data['cedula']); ?></TD>
        <TD class="blanco"
            COLSPAN=4><?php echo "<img src='" . htmlspecialchars($cirujano_data['firma']) . "' alt='Imagen de la firma' style='max-height: 40px;'>";
            ?></TD>
    </TR>
    <TR>
        <TD class="verde" COLSPAN=4>NOMBRE</TD>
        <TD class="verde" COLSPAN=4>COD. MSP. PROF.</TD>
        <TD class="verde" COLSPAN=4>FIRMA</TD>
    </TR>
</TABLE>
<table style='border: none'>
    <TR>
        <TD colspan='6' HEIGHT=24 ALIGN=LEFT VALIGN=M><B><FONT SIZE=1
                                                               COLOR='#000000'>SNS-MSP/HCU-form. 053/2021</FONT></B>
        </TD>
        <TD colspan='3' ALIGN=RIGHT VALIGN=TOP><B><FONT SIZE=3 COLOR='#000000'>REFERENCIA - DERIVACIÓN- CONTRAREFERENCIA
                    - REFERENCIA INVERSA</FONT></B>
        </TD>
    </TR>
    ]
</TABLE>
</BODY>
</HTML>
