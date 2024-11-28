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
        $enfermera_data = buscarUsuarioPorNombre('Jeniffer Dayanara Baque Zambrano', $mysqli);
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
        <td class="morado" colspan="70">B. DATOS DE LA CIRUGÍA</td>
    </tr>
    <tr>
        <td class="verde" colspan="13">FECHA<br><span style="font-size:8pt;font-family:Arial;font-weight:normal;">(aaaa-mm-dd)</span>
        </td>
        <td class="verde" colspan="41">PROCEDIMIENTO PROPUESTO</td>
        <td class="verde" colspan="16">QUIRÓFANO</td>
    </tr>
    <tr style="height: 35px">
        <td class="blanco" colspan="13"><?php echo $fechaDia . '/' . $fechaMes . '/' . $fechaAno; ?></td>
        <td class="blanco" colspan="41"><?php
            echo strtoupper(
                $nombre_procedimiento_proyectado
                    ? $nombre_procedimiento_proyectado . ' ' . $lateralidad
                    : $protocol_data['membrete']
            );
            ?></td>
        <td class="blanco" colspan="16">1</td>
    </tr>
</table>
<table>
    <tr>
        <td class="morado" colspan="23" width="33.33%"
            style="border-bottom: 1px solid #808080; border-right: 1px solid #808080;">C. ENTRADA<br><span
                    style=" font-size:8pt;font-family:Arial;font-weight:normal;
        ">(Antes de la inducción de la anestesia)</span>
        </td>
        <td class="morado" colspan="23" width="33.33%"
            style="border-bottom: 1px solid #808080; border-right: 1px solid #808080;">D. PAUSA QUIRÚRGICA<br><span
                    style=" font-size:8pt;font-family:Arial;font-weight:normal;
        ">(Antes de la incisión cutánea)</span>
        </td>
        <td class="morado" colspan="25" width="33.33%" style="border-bottom: 1px solid #808080;">E. SALIDA<br><span
                    style="font-size:8pt;font-family:Arial;font-weight:normal;">(Antes de que el paciente salga del quirófano)</span>
        </td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"><b>El paciente ha
                confirmado</b></td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"><b>Confirrmación que todos
                los miembros del equipo se han presentado
                por su nombre y función</b>
        </td>
        <td class="blanco_unbordered" colspan="24"><b>El responsable de la lista de chequeo confirma<br>verbalmente con
                el equipo quirúrgico:</b>
        </td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="15">Su identidad</td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="15">Ojo a operar</td>
        <td class="blanco_unbordered" colspan="2">OD</td>
        <td class="blanco_unbordered" colspan="2"><?php echo ($lateralidad == 'OD') ? 'X' : ''; ?></td>
        <td class="blanco_unbordered" colspan="2">OI</td>
        <td class="blanco_unbordered" colspan="2"
            style="border-right: 1px solid #808080;"><?php echo ($lateralidad == 'OI') ? 'X' : ''; ?></td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24" rowspan="3">El recuento FINAL de material blanco e<br>instrumental
            quirúrgico
            (previo al cierre) este<br>completo:
        </td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="15">El procedimiento</td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="15">Su consentimiento verbal y escrito</td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="23" rowspan="2" style="border-right: 1px solid #808080;"><b>Responsable
                de la lista de chequeo confirma
                verbalmente
                con el
                equipo quirúrgico:</b>
        </td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="3">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"><b>Demarcación del sitio
                quirúrgico</b></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="8">NO PROCEDE</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" colspan="2" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="17"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"><b>Hubo necesidad de empaquetar al paciente</b></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="17">Identidad del paciente</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="3">NO</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" rowspan="2" style="border-right: 1px solid #808080;"><b>Se ha
                completado el control formal del instrumental
                anestésico,
                medicación y riesgo anestésico</b>
        </td>
        <td class="blanco_unbordered" colspan="16">Sitio quirúrgico</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="16">Procedimiento (lateralidad)</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="18"><b>Registre el número de compresas</b></td>
        <td class="blanco_unbordered" colspan="5"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="16"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="16">Equipo de intubación</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"><b>Previsión de eventos
                críticos</b></td>
        <td class="blanco_unbordered" colspan="24"><b>Nombre del procedimiento realizado</b></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="16">Equipo de aspiración de la vía aérea</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="16">El cirujano expresa:</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="16">Sistema de ventilación</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="16">Duración del procedimiento</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="5"></td>
        <td class="blanco_unbordered" colspan="9">Oxigeno</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="16">Pérdida prevista de sangre</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="5"></td>
        <td class="blanco_unbordered" colspan="9">Fármacos inhalados</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="5"></td>
        <td class="blanco_unbordered" colspan="9">Medicación</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="16" rowspan="2">El anestesiólogo expresa algún problema específico</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"><b>Clasificación de la herida</b></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="3" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="10">Limpia</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="7">Contaminada</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" rowspan="2" style="border-right: 1px solid #808080;"><b>Pulsoxímetro
                colocado en el paciente y funcionando
        </td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="10">Limpia-contaminada</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="7">Sucia</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" rowspan="2" style="border-right: 1px solid #808080;"><b>Equipo de
                enfermería y/o instrumentación<br>quirúrgica
                revisa:</b>
        </td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="10">Toma de muestras</td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="17"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"><b>Capnógrafo colocado y
                funcionando</b></td>
        <td class="blanco_unbordered" colspan="16" rowspan="3">Esterilidad (con resultado de<br>Indicadores e
            integradores<br>químicos
            internos y externos)
        </td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24" rowspan="2"><b>Etiquetado de las muestras (nombres y apellidos<br>completos
                del
                paciente, historia clínica, fecha)</b>
        </td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="8">NO PROCEDE</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="5"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="4"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" colspan="9"></td>
    </tr>
    <tr style="height: 19px">
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"><b>Tiene el paciente
                alergias conocidas</b></td>
        <td class="blanco_unbordered" colspan="16" rowspan="2">Recuento INICIAL de material<br>blanco e Instrumental
            quirúrgico.
        </td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="4">Cuales</td>
        <td class="blanco_unbordered" colspan="9" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"><b>Identifique el tipo de muestra a enviar</b></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="16" rowspan="2">Dudas o problemas relacionados<br>con el instrumental y
            equipos.
        </td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="6">Citoquímico</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">No.</td>
        <td class="blanco_unbordered" colspan="7"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"><b>Vía aérea difícil /
                riesgo de aspiración</b></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="4">Nombre:</td>
        <td class="blanco_unbordered" colspan="18"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="19" style="border-right: 1px solid #808080;">SI, y hay instrumental y
            equipos disponibles
        </td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="17"></td>
        <td class="blanco_unbordered" colspan="23" rowspan="2"
            style="border-left: 1px solid #808080; border-right: 1px solid #808080"><b>Se ha administrado profilaxis
                antibiótica en los<br>últimos
                60
                minutos</b>
        </td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="6">Cultivos</td>
        <td></td>
        <td></td>
        <td></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">No.</td>
        <td class="blanco_unbordered" colspan="7"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="4">Nombre:</td>
        <td class="blanco_unbordered" colspan="18"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" rowspan="2" style="border-right: 1px solid #808080;"><b>Riesgo de
                hemorragia &gt; 500 ml (7 ml/kg en
                niños)</b></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="9">NO PROCEDE</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="10">Anatomopatológico</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">No.</td>
        <td class="blanco_unbordered" colspan="7"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="19" rowspan="2" style="border-right: 1px solid #808080;">SI, y se ha
            previsto la disponibilidad de<br>acceso
            intravenoso y
            líquidos adecuados.
        </td>
        <td class="blanco_unbordered" colspan="23" rowspan="2" style="border-right: 1px solid #808080;"><b>Dispone de
                imágenes diagnosticas esenciales<br>Para el
                procedimiento quirúrgico</b>
        </td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="4">Nombre:</td>
        <td class="blanco_unbordered" colspan="18"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="9">NO PROCEDE</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="3">Otros:</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" rowspan="2" style="border-right: 1px solid #808080;"><b>Se ha
                confirmado la reserva de hemoderivados con el
                laboratorio</b>
        </td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="8">NO APLICA</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="23" style="border-right: 1px solid #808080;"></td>
        <td class="blanco_unbordered" colspan="24" rowspan="2"><b>Si hay problemas que resolver, relacionados con<br>el
                instrumental
                y los equipos</b>
        </td>
    </tr>
    <tr>
        <td class="morado" colspan="46" style="border-top: 1px solid #808080; border-right: 1px solid #808080">F. DATOS
            DE LOS PROFESIONALES RESPONSABLES
        </td>
    </tr>
    <tr style="height: 15px">
        <td class="verde" colspan="15" rowspan="2">NOMBRE COMPLETO DE LA PERSONA RESPONSABLE DE LA LISTA DE
            VERIFICACIÓN
        </td>
        <td class="verde" colspan="16" rowspan="2">NOMBRE COMPLETO DEL CIRUJANO</td>
        <td class="verde" colspan="15" rowspan="2">NOMBRE COMPLETO DEL ANESTESIÓLOGO</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="3">NO</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="24"></td>
        Cuáles:</td>
    </tr>
    <tr>
        <td class="blanco" colspan="15"><?php echo strtoupper($enfermera_data['nombre']); ?></td>
        <td class="blanco" colspan="16">MD. <?php echo $mainSurgeon; ?></td>
        <td class="blanco" colspan="15">MD. <?php echo $anestesiologo; ?></td>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="verde" colspan="15">FIRMA Y SELLO</td>
        <td class="verde" colspan="16">FIRMA Y SELLO</td>
        <td class="verde" colspan="15">FIRMA Y SELLO</td>
        <td class="blanco_unbordered" colspan="24" rowspan="3"><b>El cirujano, el anestesiólogo y el personal de<br>enfermería
                revisan los principales aspectos de la<br>recuperación del paciente.</b>
        </td>
    </tr>
    <tr>
        <td class="blanco" colspan="15"
            rowspan="5"><?php echo "<img src='" . htmlspecialchars($enfermera_data['firma']) . "' alt='Imagen de la firma' style='max-height: 70px;'>";
            ?></td>
        <td class="blanco" colspan="16"
            rowspan="5"><?php echo "<img src='" . htmlspecialchars($cirujano_data['firma']) . "' alt='Imagen de la firma' style='max-height: 70px;'>";
            ?></td>
        <td class="blanco" colspan="15"
            rowspan="5"><?php echo "<img src='" . htmlspecialchars($anestesiologo_data['firma']) . "' alt='Imagen de la firma' style='max-height: 70px;'>";
            ?></td>
    </tr>
    <tr>
        <td class="blanco"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="3"></td>
        <td class="blanco_unbordered" colspan="2">SI</td>
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered"></td>
        <td class="blanco_unbordered" colspan="3">NO</td>
        <td class="blanco_unbordered" colspan="2"></td>
        <td class="blanco_unbordered" colspan="11"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
    <tr>
        <td class="blanco_unbordered" colspan="24"></td>
    </tr>
</table>
<table style="border: none">
    <TR>
        <TD colspan="6" HEIGHT=24 ALIGN=LEFT VALIGN=M><B><FONT SIZE=1
                                                               COLOR="#000000">SNS-MSP/HCU-form.060/2021</FONT></B>
        </TD>
        <TD colspan="3" ALIGN=RIGHT VALIGN=TOP><B><FONT SIZE=3 COLOR="#000000">LISTA VERIFICACIÓN CIRUGÍA SEGURA</FONT></B>
        </TD>
    </TR>
</table>
</BODY>