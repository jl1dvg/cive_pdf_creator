<?php
// Obtener los parámetros desde GET
$patientName = isset($_GET['patientName']) ? $_GET['patientName'] : ' ';
$historyNumber = isset($_GET['historyNumber']) ? $_GET['historyNumber'] : ' ';
$birthDate = isset($_GET['birthDate']) ? $_GET['birthDate'] : ' ';
$gender = isset($_GET['gender']) ? $_GET['gender'] : ' ';
$insurance = isset($_GET['insurance']) ? $_GET['insurance'] : ' ';
$diagnostic1 = isset($_GET['diagnostic1']) ? $_GET['diagnostic1'] : ' ';
$diagnostic2 = isset($_GET['diagnostic2']) ? $_GET['diagnostic2'] : ' ';
$definitiveDisease1 = isset($_GET['definitiveDisease1']) ? $_GET['definitiveDisease1'] : ' ';
$definitiveDisease2 = isset($_GET['definitiveDisease2']) ? $_GET['definitiveDisease2'] : ' ';
$definitiveDisease3 = isset($_GET['definitiveDisease3']) ? $_GET['definitiveDisease3'] : ' ';
$projectProcedure = isset($_GET['projectProcedure']) ? $_GET['projectProcedure'] : ' ';
$realizedProcedure = isset($_GET['realizedProcedure']) ? $_GET['realizedProcedure'] : ' ';
$cirujanoPrincipal = isset($_GET['cirujano_principal']) ? $_GET['cirujano_principal'] : ' ';
$ayudante = isset($_GET['ayudante']) ? $_GET['ayudante'] : ' ';
$ayudante2 = isset($_GET['ayudante2']) ? $_GET['ayudante2'] : ' ';
$anestesiologo = isset($_GET['anestesiologo']) ? $_GET['anestesiologo'] : ' ';
$instrumentista = isset($_GET['instrumentista']) ? $_GET['instrumentista'] : ' ';
$circulante = isset($_GET['circulante']) ? $_GET['circulante'] : ' ';

// Nuevas variables
$dieresis = isset($_GET['dieresis']) ? $_GET['dieresis'] : ' ';
$exposicion = isset($_GET['exposicion']) ? $_GET['exposicion'] : ' ';
$hallazgo = isset($_GET['hallazgo']) ? $_GET['hallazgo'] : ' ';
$operatorio = isset($_GET['operatorio']) ? $_GET['operatorio'] : ' ';
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : ' ';
$horaInicio = isset($_GET['horaInicio']) ? $_GET['horaInicio'] : ' ';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : ' ';
$horaFin = isset($_GET['horaFin']) ? $_GET['horaFin'] : ' ';
$tipoAnestesia = isset($_GET['tipoAnestesia']) ? $_GET['tipoAnestesia'] : ' ';

// Reemplazar %0A por <br> para los saltos de línea en el texto del procedimiento operatorio
$operatorio = strtoupper(str_replace('%0A', '<br>', $operatorio));

// Formatear fechas y horas
$fechaInicioParts = explode('-', $fechaInicio);
$fechaDia = isset($fechaInicioParts[2]) ? $fechaInicioParts[2] : ' ';
$fechaMes = isset($fechaInicioParts[1]) ? $fechaInicioParts[1] : ' ';
$fechaAno = isset($fechaInicioParts[0]) ? $fechaInicioParts[0] : ' ';

// Suponiendo que ya tienes $fechaInicio y $horaInicio, $horaFin definidas como cadenas (strings)

$prot_hini_timestamp = strtotime($fechaInicio . ' ' . $horaInicio); // Convertir la fecha y hora de inicio a timestamp
$prot_hfinal_timestamp = strtotime($fechaInicio . ' ' . $horaFin); // Convertir la fecha y hora de finalización a timestamp

// Calcular el timestamp para el tiempo preoperatorio (30 minutos antes del inicio)
$prot_hpre_timestamp = $prot_hini_timestamp - 1800; // 1800 segundos = 30 minutos

// Calcular el timestamp para el tiempo de alta (45 minutos después de la finalización)
$prot_halta_timestamp = $prot_hfinal_timestamp + 2700; // 2700 segundos = 45 minutos

// Formatear los timestamps en formato de hora (HH:MM)
$pot_hinicio = date("H:i", $prot_hini_timestamp);
$pot_hfinal = date("H:i", $prot_hfinal_timestamp);
$pot_hpre = date("H:i", $prot_hpre_timestamp);
$pot_halta = date("H:i", $prot_halta_timestamp);

// Dividir el nombre completo en partes (asumiendo que son 2 apellidos y 2 nombres)
$nameParts = explode(' ', $patientName);
$lname = $nameParts[0] ?? '';
$lname2 = $nameParts[1] ?? '';
$fname = $nameParts[2] ?? '';
$mname = $nameParts[3] ?? '';

// Separar el contenido de $realizedProcedure por código (número seguido de un guion)
$realizedProceduresArray = preg_split('/(?=\d{5}-)/', $realizedProcedure);
$formattedRealizedProcedure = implode('<br>', $realizedProceduresArray);

// Convertir las fechas a objetos DateTime
$birthDateObj = new DateTime($birthDate);
$fechaInicioObj = new DateTime($fechaInicio);

$sistolica = rand(110, 130);
$diastolica = rand(110, 130);
$fc = rand(110, 130);

// Calcular la diferencia entre las dos fechas
$ageInterval = $birthDateObj->diff($fechaInicioObj);

// Obtener la edad en años
$edadPaciente = $ageInterval->y;

// Contenido de la primera página
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
        <td class='blanco_left' colspan='3'><?php echo $pot_hpre; ?></td>
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
            -<?php echo $definitiveDisease1; ?>
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
        <td class='blanco_left' colspan='3'><?php echo $pot_hfinal; ?></td>
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
        <td class='blanco_left' colspan='29'><?php echo $cirujanoPrincipal; ?></td>
        <td class='blanco_break'></td>
        <td class='blanco_left' colspan='23'></td>
        <td class='blanco_left' colspan='5'></td>
    </tr>
</table>
</body>
