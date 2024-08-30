<?php
// Obtener los parámetros desde GET
$patientName = isset($_GET['patientName']) ? $_GET['patientName'] : ' ';
$historyNumber = isset($_GET['historyNumber']) ? $_GET['historyNumber'] : ' ';
$birthDate = isset($_GET['birthDate']) ? $_GET['birthDate'] : ' ';
$gender = isset($_GET['gender']) ? $_GET['gender'] : ' ';
$insurance = isset($_GET['insurance']) ? $_GET['insurance'] : ' ';
$diagnostic1 = isset($_GET['diagnostic1']) ? $_GET['diagnostic1'] : ' ';
$diagnostic2 = isset($_GET['diagnostic2']) ? $_GET['diagnostic2'] : ' ';

// Obtener los valores desde GET
$definitiveDisease1 = isset($_GET['definitiveDisease1']) ? $_GET['definitiveDisease1'] : '';
$definitiveDisease2 = isset($_GET['definitiveDisease2']) ? $_GET['definitiveDisease2'] : '';
$definitiveDisease3 = isset($_GET['definitiveDisease3']) ? $_GET['definitiveDisease3'] : ' ';

// Separar las cadenas en arreglos usando " ," como delimitador
$diseasesArray1 = explode(' ,', $definitiveDisease1);
$diseasesArray2 = explode(' ,', $definitiveDisease2);
$diseasesArray3 = explode(' ,', $definitiveDisease3);

// Combinar los dos arreglos en uno solo
$combinedDiseasesArray = array_merge($diseasesArray1, $diseasesArray2, $diseasesArray3);

// Limitar el número de elementos en el arreglo combinado a un máximo de 3
$limitedDiseasesArray = array_slice($combinedDiseasesArray, 0, 3);

// Asignar diagnósticos a variables individuales de forma segura
$diagnosticoPRE1 = isset($limitedDiseasesArray[0]) ? $limitedDiseasesArray[0] : '';
$diagnosticoPRE2 = isset($limitedDiseasesArray[1]) ? $limitedDiseasesArray[1] : '';
$diagnosticoPRE3 = isset($limitedDiseasesArray[2]) ? $limitedDiseasesArray[2] : '';

// Ejemplo de uso
$projectProcedure = isset($_GET['projectProcedure']) ? $_GET['projectProcedure'] : '';

// Limpiar el nombre del procedimiento
$cleanedProcedure = cleanProcedureName($projectProcedure);

// Ahora puedes usar $cleanedProcedure para mostrar el nombre limpio del procedimiento

$realizedProcedure = isset($_GET['realizedProcedure']) ? $_GET['realizedProcedure'] : ' ';
$cirujanoPrincipal = isset($_GET['cirujano_principal']) ? $_GET['cirujano_principal'] : ' ';
$ayudante = isset($_GET['ayudante']) ? $_GET['ayudante'] : ' ';
$ayudante2 = isset($_GET['ayudante2']) ? $_GET['ayudante2'] : ' ';
$anestesiologo = isset($_GET['anestesiologo']) ? $_GET['anestesiologo'] : ' ';
$instrumentista = isset($_GET['instrumentista']) ? $_GET['instrumentista'] : ' ';
$circulante = isset($_GET['circulante']) ? $_GET['circulante'] : ' ';

// Nuevas variables
$code = isset($_GET['code']) ? $_GET['code'] : ' ';
// Utilizar explode para dividir la cadena
$parts = explode('-', $code);

// Asignar cada parte a una variable específica
$idCirugia = isset($parts[0]) ? $parts[0] : ' ';
$medicamentos = isset($parts[1]) ? $parts[1] : ' ';
$cardex = isset($parts[2]) ? $parts[2] : ' ';

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
<table class="trans">
    <tr style="height: 18px">
        <td class="morado_tr" colspan="138">A. DATOS DEL ESTABLECIMIENTO Y USUARIO</td>
    </tr>
    <tr style="height: 16px">
        <td class="verde_tr" colspan="27">INSTITUCIÓN DEL SISTEMA</td>
        <td class="verde_tr" colspan="45">ESTABLECIMIENTO DE SALUD</td>
        <td class="verde_tr" colspan="40">NÚMERO DE HISTORIA CLÍNICA ÚNICA</td>
        <td class="verde_tr" colspan="26">NÚMERO DE ARCHIVO</td>
    </tr>
    <tr style="height: 16px">
        <td class="blanco_tr" colspan="27"><?php echo $insurance; ?></td>
        <td class="blanco_tr" colspan="45">AlTA VISION</td>
        <td class="blanco_tr" colspan="40"><?php echo $historyNumber; ?></td>
        <td class="blanco_tr" colspan="26"><?php echo $historyNumber; ?></td>
    </tr>
    <tr style="height: 16px">
        <td class="verde_tr" colspan="23" rowspan="2">PRIMER APELLIDO</td>
        <td class="verde_tr" colspan="21" rowspan="2">SEGUNDO APELLIDO</td>
        <td class="verde_tr" colspan="25" rowspan="2">PRIMER NOMBRE</td>
        <td class="verde_tr" colspan="24" rowspan="2">SEGUNDO NOMBRE</td>
        <td class="verde_tr" colspan="8" rowspan="2">SEXO</td>
        <td class="verde_tr" colspan="16" rowspan="2">FECHA NACIMIENTO</td>
        <td class="verde_tr" colspan="12" rowspan="2">EDAD</td>
        <td class="verde_tr" colspan="9">CONDICIÓN EDAD</td>
    </tr>
    <tr style="height: 11px">
        <td class="blanco_tr" colspan="2">H</td>
        <td class="blanco_tr" colspan="2">D</td>
        <td class="blanco_tr" colspan="2">M</td>
        <td class="blanco_tr" colspan="3">A</td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_tr" colspan="23"><?php echo $lname; ?></td>
        <td class="blanco_tr" colspan="21"><?php echo $lname2; ?></td>
        <td class="blanco_tr" colspan="25"><?php echo $fname; ?></td>
        <td class="blanco_tr" colspan="24"><?php echo $mname; ?></td>
        <td class="blanco_tr" colspan="8"><?php echo substr($gender, 0, 1); ?></td>
        <td class="blanco_tr" colspan="16"><?php echo $birthDate; ?></td>
        <td class="blanco_tr"
            colspan="12"><?php echo $edadPaciente; ?></td>
        <td class="blanco_tr" colspan="2"></td>
        <td class="blanco_tr" colspan="2"></td>
        <td class="blanco_tr" colspan="2"></td>
        <td class="blanco_tr" colspan="3">X</td>
    </tr>
    <tr style="height: 17px">
        <td class="verde_tr" colspan="9">FECHA:</td>
        <td class="blanco_tr" colspan="18">
            <?php echo $fechaInicio; ?>
        </td>
        <td class="verde_tr" colspan="6">TALLA (cm)</td>
        <td class="blanco_tr" colspan="14"></td>
        <td class="verde_tr" colspan="14">PESO (kg)</td>
        <td class="blanco_tr" colspan="11"></td>
        <td class="verde_tr" colspan="4">IMC</td>
        <td class="blanco_tr" colspan="18"></td>
        <td class="verde_tr" colspan="14">GRUPO Y FACTOR</td>
        <td class="blanco_tr" colspan="4"></td>
        <td class="verde_tr" colspan="20">CONSENTIMIENTO INFORMADO</td>
        <td class="verde_tr">SI</td>
        <td class="blanco_tr">X</td>
        <td class="verde_tr" colspan="2">NO</td>
        <td class="blanco_tr" colspan="2"></td>
    </tr>
</table>
<table class="trans">
    <tr style="height: 18px">
        <td class="morado_tr" colspan="138">B. SERVICIO Y PRIORIDAD DE ATENCIÓN</td>
    </tr>
    <tr style="height: 12px">
        <td class="verde_left_tr" colspan="13">DIAGNÓSTICO PREOPERATORIO</td>
        <td class="blanco_tr" style="text-align: left"
            colspan="29">
            <?php
            echo $definitiveDisease1;
            ?>
        </td>
        <td class="verde_left_tr" colspan="3">CIE</td>
        <td class="blanco_tr" style="text-align: left" colspan="16">
            <?php
            echo $definitiveDisease1;
            ?>
        </td>
        <td class="verde_left_tr" colspan="11">CIRUGÍA PROPUESTA</td>
        <td class="blanco_tr" style="text-align: left" colspan="38">
            <?php echo $projectProcedure; ?>
        </td>
        <td class="verde_left_tr" colspan="8">ESPECIALIDAD</td>
        <td class="verde_left_tr" colspan="11" rowspan="4">PRIORIDAD</td>
        <td class="blanco_left_tr" colspan="6">EMERGENTE</td>
        <td class="blanco_tr" colspan="3"></td>
    </tr>
    <tr style="height: 11px">
        <td class="verde_left_tr" colspan="13">DIAGNÓSTICO POSTOPERATORIO</td>
        <td class="blanco_left_tr" style="text-align: left" colspan="29"><?php
            echo $diagnostic1;
            ?>
        </td>
        <td class="verde_left_tr" colspan="3">CIE</td>
        <td class="blanco_left_tr" style="text-align: left" colspan="16"><?php
            echo $diagnostic1;
            ?>
        </td>
        <td class="verde_left_tr" colspan="11">CIRUGÍA REALIZADA</td>
        <td class="blanco_left_tr" style="text-align: left" colspan="38">
            <?php echo $projectProcedure;
            ?>
        </td>
        <td class="blanco_left_tr" colspan="8">Oftalmología</td>
        <td class="blanco_left_tr" colspan="6">URGENTE</td>
        <td class="blanco_left_tr" colspan="3"></td>
    </tr>
    <tr style="height: 11px">
        <td class="verde_left_tr" colspan="7">ANESTESIÓLOGO</td>
        <td class="blanco_left_tr" style="text-align: left"
            colspan="35"><?php echo $anestesiologo; ?></td>
        <td class="verde_left_tr" colspan="6">AYUDANTE (S)</td>
        <td class="blanco_left_tr" colspan="24"></td>
        <td class="verde_left_tr" colspan="19">INSTRUMENTISTA</td>
        <td class="blanco_left_tr" colspan="19">
            <?php
            echo $instrumentista; ?>
        </td>
        <td class="verde_left_tr" colspan="8">QUIRÓFANO</td>
        <td class="blanco_left_tr" colspan="6">ELECTIVO</td>
        <td class="blanco_left_tr" colspan="3">X</td>
    </tr>
    <tr style="height: 11px">
        <td class="verde_left_tr" colspan="7">CIRUJANO</td>
        <td class="blanco_left_tr" style="text-align: left" colspan="35"><?php echo $cirujanoPrincipal; ?></td>
        <td class="verde_left_tr" colspan="6">AYUDANTE (S)</td>
        <td class="blanco_left_tr"
            colspan="24"><?php echo $ayudante; ?></td>
        <td class="verde_left_tr" colspan="19">CIRCULANTE</td>
        <td class="blanco_left_tr" colspan="19"><?php
            echo $circulante;
            ?></td>
        </td>
        <td class="blanco_left_tr" colspan="8">1</td>
        <td class="blanco_left_tr" colspan="6"></td>
        <td class="blanco_left_tr" colspan="3"></td>
    </tr>
</table>
<table class="trans">
    <tr style="height: 18px">
        <td class="morado_tr" colspan="138">C. REGIÓN OPERATORIA</td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="6"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="8"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="5">CABEZA</td>
        <td class="blanco_left_tr" colspan="2">X</td>
        <td class="blanco_left_tr" colspan="3">ORGANOS DE LOS SENTIDOS</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="5">CUELLO</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="8">COLUMNA</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="10">TORAX</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="6">ABDOMEN</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="7">PELVIS</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="7">EXTREMIDADES SUPERIORES</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="15">EXTREMIDADES INFERIORES</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="8"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="4">PERINEAL</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="6"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="7"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="3"></td>
    </tr>
</table>
<table class="trans">
    <tr style="height: 18px">
        <td class="morado_tr" colspan="138">D. REGISTRO TRANSANESTÉSICO</td>
    </tr>
    <tr style="height: 12px">
        <td class="s36" colspan="30">AGENTE INHALATORIO / INFUSIÓN CONTINUA</td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon"
                             style="height: 6px"></img></td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s40"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
    </tr>
    <tr style="height: 12px">
        <td class="s41" colspan="30"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s41" colspan="30"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s41" colspan="30"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s41" colspan="30"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s41" colspan="30"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s41" colspan="30"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s36" colspan="30">PARAMETROS DE MONITOREO ANESTÉSICO</td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s38" colspan="2"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s39"></td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s38" colspan="2"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s39"></td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s38" colspan="2"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s39"></td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s38" colspan="2"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s39"></td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s38" colspan="2"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s39"></td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s38" colspan="2"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s39"></td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s38" colspan="2"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s39"></td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s38" colspan="2"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s39"></td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s38" colspan="2"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2"></td>
        <td class="s38"></td>
        <td class="s40"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s44" colspan="30">ONDA DELTA PP</td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s44" colspan="30">SATURACION O2</td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s44" colspan="30">CAPNOMETRÍA</td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s42"></td>
        <td class="s38"></td>
        <td class="s38"></td>
        <td class="s43"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s44" colspan="30">RELAJACIÓN NEUROMUSCULAR</td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s44" colspan="30">PROFUNDIDAD ANESTÉSICA</td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s42" colspan="3"></td>
        <td class="s43" colspan="3"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s45"></td>
        <td class="s46"></td>
        <td class="s46"></td>
        <td class="s47"></td>
        <td class="s48" colspan="108"></td>
    </tr>
    <tr style="height: 15px">
        <td class="s49" colspan="27" rowspan="2">SIMBOLOGÍA</td>
        <td class="s50" rowspan="2">T°</td>
        <td class="s51" rowspan="2">PV</td>
        <td class="s51" rowspan="2">TA<br>P. / R.</td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s52"></td>
        <td class="s53"></td>
    </tr>
    <tr style="height: 15px">
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s39"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
        <td class="s37"></td>
        <td class="s37"></td>
        <td class="s38" colspan="2">15</td>
        <td class="s38"></td>
        <td class="s38" colspan="2">30</td>
        <td class="s37"></td>
        <td class="s38" colspan="2">45</td>
        <td class="s38"></td>
        <td class="s54"><img src="https://cdn-icons-png.flaticon.com/512/626/626075.png" alt="Icon" style="height: 6px">
        </td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:89px;left:-1px">INICIO ANESTESIA</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s59"><img src="https://cdn-icons-png.flaticon.com/512/545/545678.png" style="height: 8px"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">240</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:63px;left:-1px">INDUCCIÓN</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"><img src="https://cdn-icons-png.flaticon.com/512/32/32178.png" style="height: 8px"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:76px;left:-1px">INICIO CIRUGÍA</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"><img src="https://cdn-icons-png.flaticon.com/512/565/565762.png" style="height: 8px"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">220</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:76px;left:-1px">FIN DE CIRUGÍA</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"><img src="https://cdn-icons-png.flaticon.com/512/565/565762.png" style="height: 8px"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:89px;left:-1px">FIN DE ANESTESIA</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"><img src="https://cdn-icons-png.flaticon.com/512/2198/2198359.png" style="height: 8px"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">200</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:24px;left:-1px">TAS</div>
        </td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"><img src="https://cdn-icons-png.flaticon.com/512/32/32195.png" style="height: 8px"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:24px;left:-1px">TAD</div>
        </td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"><img src="https://cdn-icons-png.flaticon.com/512/3838/3838683.png" style="height: 8px"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">180</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:24px;left:-1px">TAM</div>
        </td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s66">
            <img src="https://cdn-icons-png.flaticon.com/512/32/32178.png" style="height: 8px">
        </td>
        <td class="s58"></td>
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:76px;left:-1px">SIN NEGRITAS</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s67">17</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:102px;left:-1px">FRECUENCIA CARDÍACA</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s66">
            <img src="https://cdn-icons-png.flaticon.com/512/0/14.png" style="height: 8px">
        </td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">160</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:76px;left:-1px">TEMPERATURA</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s66"><img src="https://cdn-icons-png.flaticon.com/512/649/649731.png" style="height: 8px">
        </td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s67">42</td>
        <td class="s67">15</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:24px;left:-1px">PVC</div>
        </td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"><img src="https://cdn-icons-png.flaticon.com/512/702/702845.png" style="height: 8px"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">140</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:128px;left:-1px">RESPIRACIÓN ESPONTÁNEA</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s67">41</td>
        <td class="s67">13</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:115px;left:-1px">RESPIRACIÓN ASISTIDA</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">120</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:128px;left:-1px">RESPIRACIÓN CONTROLADA</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s67">40</td>
        <td class="s67">11</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:63px;left:-1px">TORNIQUETE</div>
        </td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">100</td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s70"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s55 softmerge">
            <div class="softmerge-inner" style="width:37px;left:-1px">FETO</div>
        </td>
        <td class="s56"></td>
        <td class="s57"></td>
        <td class="s57"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s67">38</td>
        <td class="s67">9</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">80</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s67">37</td>
        <td class="s67">7</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s58"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">60</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s58"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s67">36</td>
        <td class="s67">5</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s58"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">40</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s67">35</td>
        <td class="s67">3</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">20</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s67">1</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s62" rowspan="2">0</td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s71"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s58"></td>
        <td class="s60"></td>
        <td class="s61"></td>
        <td class="s61"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s64"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s63"></td>
        <td class="s65"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s72"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s73"></td>
        <td class="s74"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s69"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s68"></td>
        <td class="s70"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s75" colspan="30" rowspan="3">DROGAS ADMINISTRADAS</td>
        <td class="blanco_tr" colspan="12" rowspan="3"></td>
        <td class="blanco_tr" colspan="12" rowspan="3"></td>
        <td class="blanco_tr" colspan="12" rowspan="3"></td>
        <td class="blanco_tr" colspan="12" rowspan="3"></td>
        <td class="blanco_tr" colspan="12" rowspan="3"></td>
        <td class="blanco_tr" colspan="12" rowspan="3"></td>
        <td class="blanco_tr" colspan="12" rowspan="3"></td>
        <td class="blanco_tr" colspan="12" rowspan="3"></td>
        <td class="blanco_tr" colspan="12" rowspan="3"></td>
    </tr>
    <tr style="height: 12px">
    </tr>
    <tr style="height: 12px">
    </tr>
    <tr style="height: 12px">
        <td class="s77" colspan="30">POSICIÓN</td>
        <td class="blanco_tr" colspan="12"></td>
        <td class="blanco_tr" colspan="12"></td>
        <td class="blanco_tr" colspan="12"></td>
        <td class="blanco_tr" colspan="12"></td>
        <td class="blanco_tr" colspan="12"></td>
        <td class="blanco_tr" colspan="12"></td>
        <td class="blanco_tr" colspan="12"></td>
        <td class="blanco_tr" colspan="12"></td>
        <td class="blanco_tr" colspan="12"></td>
    </tr>
</table>
<table class="trans">
    <tr style="height: 18px">
        <td class="morado_tr" colspan="138">E. DROGAS ADMINISTRADAS</td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr">1</td>
        <td class="blanco_left_tr" colspan="18">MIDAZOLAM 1 MG</td>
        <td class="blanco_left_tr">5</td>
        <td class="blanco_left_tr" colspan="22">KETOROLACO 60MG</td>
        <td class="blanco_left_tr">9</td>
        <td class="blanco_left_tr" colspan="24">GENTAMICINA 80MG/ML</td>
        <td class="blanco_left_tr">13</td>
        <td class="blanco_left_tr" colspan="24"></td>
        <td class="blanco_left_tr">17</td>
        <td class="blanco_left_tr" colspan="21"></td>
        <td class="blanco_left_tr">21</td>
        <td class="blanco_left_tr" colspan="23"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr">2</td>
        <td class="blanco_left_tr" colspan="18">FENTALINO 25MG</td>
        <td class="blanco_left_tr">6</td>
        <td class="blanco_left_tr" colspan="22">ONDASETRON 4MG</td>
        <td class="blanco_left_tr">10</td>
        <td class="blanco_left_tr" colspan="24"></td>
        <td class="blanco_left_tr">14</td>
        <td class="blanco_left_tr" colspan="24"></td>
        <td class="blanco_left_tr">18</td>
        <td class="blanco_left_tr" colspan="21"></td>
        <td class="blanco_left_tr">22</td>
        <td class="blanco_left_tr" colspan="23"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr">3</td>
        <td class="blanco_left_tr" colspan="18">LIDOCAINA 2% 40MG</td>
        <td class="blanco_left_tr">7</td>
        <td class="blanco_left_tr" colspan="22">DEXAMETASONA 4MG</td>
        <td class="blanco_left_tr">11</td>
        <td class="blanco_left_tr" colspan="24"></td>
        <td class="blanco_left_tr">15</td>
        <td class="blanco_left_tr" colspan="24"></td>
        <td class="blanco_left_tr">19</td>
        <td class="blanco_left_tr" colspan="21"></td>
        <td class="blanco_left_tr">23</td>
        <td class="blanco_left_tr" colspan="23"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr">4</td>
        <td class="blanco_left_tr" colspan="18">BUPIVACAINA 0,5% 10MG</td>
        <td class="blanco_left_tr">8</td>
        <td class="blanco_left_tr" colspan="22">CEXFTRIAXONA 1G</td>
        <td class="blanco_left_tr">12</td>
        <td class="blanco_left_tr" colspan="24"></td>
        <td class="blanco_left_tr">16</td>
        <td class="blanco_left_tr" colspan="24"></td>
        <td class="blanco_left_tr">20</td>
        <td class="blanco_left_tr" colspan="21"></td>
        <td class="blanco_left_tr">24</td>
        <td class="blanco_left_tr" colspan="23"></td>
    </tr>
</table>
<table class="trans">
    <tr style="height: 17px">
        <td class="morado_tr" colspan="138">F. TÉCNICA ANESTÉSICA</td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_tr" colspan="49"><b>GENERAL</b></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_tr" colspan="50"><b>REGIONAL</b></td>
        <td class="blanco_tr"></td>
        <td class="blanco_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_tr" colspan="33"><b>SEDO - ANALGESIA</b></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="25">SISTEMA</td>
        <td class="blanco_left_tr" colspan="24">APARATO</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="9">ASEPSIA CON:</td>
        <td class="blanco_tr" colspan="21"><b>ALCOHOL</b></td>
        <td class="blanco_left_tr" colspan="5">HABÓN CON:</td>
        <td class="blanco_left_tr" colspan="15"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="4">ABIERTO</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="6">SEMICERRADO</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="12">CERRADO</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="9">CIRCUITO CIRCULAR</td>
        <td class="blanco_left_tr" colspan="5"></td>
        <td class="blanco_left_tr" colspan="6">UNIDIRECCIONAL</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="9">LOCAL ASISTIDA</td>
        <td class="blanco_left_tr" colspan="21"><b>BLOQUEO RETROBULBAR</b></td>
        <td class="blanco_left_tr" colspan="5">INTRAVENOSA</td>
        <td class="blanco_tr" colspan="15"><b>X</b></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="49">MANEJO DE VÍA AÉREA</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_tr" colspan="50"><b>TRONCULAR</b></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="13">MÁSCARA FACIAL</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="13">SUPRAGLÓTICA</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr" colspan="13">TRAQUEOTOMO</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="11">BLOQUEO DE NERVIO</td>
        <td class="blanco_left_tr" colspan="21"></td>
        <td class="blanco_left_tr" colspan="9">No. INTENTOS</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="49">INTUBACIÓN</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="11">BLOQUEO DEL PLEXO</td>
        <td class="blanco_left_tr" colspan="21"></td>
        <td class="blanco_left_tr" colspan="9">No. INTENTOS</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="5">NASAL</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr" colspan="5">ORAL</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr" colspan="9">SUBMENTONEANA</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="9">VISIÓN DIRECTA</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="5">A CIEGAS</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="11">ANESTÉSICO LOCAL</td>
        <td class="blanco_tr" colspan="21"><b>REGIONAL</b></td>
        <td class="blanco_left_tr" colspan="9">COADYUVANTE</td>
        <td class="blanco_left_tr" colspan="9"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="49">TIPO DE TUBO</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="11">TIPO DE AGUJA</td>
        <td class="blanco_tr" colspan="21"><b>25X1</b></td>
        <td class="blanco_left_tr" colspan="9">EQUIPO</td>
        <td class="blanco_left_tr" colspan="9"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="8">CONVENCIONAL</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="10">PREFORMADO ORAL</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="9">PREFORMADO NASAL</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr" colspan="7">REFORZADO</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_tr" colspan="50"><b>NEUROAXIAL</b></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="8">DOBLE LUMEN</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr" colspan="7">DIÁMETRO</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="3">BALÓN</td>
        <td class="blanco_left_tr">SI</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="3">NO</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="11">TAPONAMIENTO</td>
        <td class="blanco_left_tr">SI</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="2">NO</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="6">RAQUÍDEA</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="5">EPIDURAL</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="4">CAUDAL</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="5"></td>
        <td class="blanco_left_tr" colspan="4">CATETER</td>
        <td class="blanco_left_tr" colspan="5"></td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="5"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr">
            <div class="softmerge-inner" style="width:128px;left:-1px">EQUIPO PARA INTUBACIÓN</div>
        </td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="39"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="6">TIPO AGUJA</td>
        <td class="blanco_left_tr" colspan="9"></td>
        <td class="blanco_left_tr" colspan="19">NÚMERO AGUJA</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="9">No. INTENTOS</td>
        <td class="blanco_left_tr" colspan="5"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="5">CORMACK:</td>
        <td class="blanco_left_tr" colspan="2">I</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="2">II</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="2">III</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="2">IV</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="9">NÚMERO DE INTENTOS</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="6">BORBOTAJE</td>
        <td class="blanco_left_tr" colspan="3">SI</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="2">NO</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="4">ACCESO</td>
        <td class="blanco_left_tr" colspan="4">MEDIAL</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="4">LATERAL</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr" colspan="7">SITIO DE PUNCIÓN</td>
        <td class="blanco_left_tr" colspan="11"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33"></td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="21">INDUCCIÓN</td>
        <td class="blanco_left_tr" colspan="28">MANTENIMIENTO</td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="11"></td>
        <td class="blanco_left_tr" colspan="21"></td>
        <td class="blanco_left_tr" colspan="9"></td>
        <td class="blanco_left_tr" colspan="9"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="33">ESCALA DE RAMSAY</td>
    </tr>
    <tr style="height: 12px">
        <td class="blanco_left_tr" colspan="9">INHALATORIA</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr" colspan="6">INTRAVENOSA</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr" colspan="6">INHALATORIA</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr" colspan="6">INTRAVENOSA</td>
        <td class="blanco_left_tr" colspan="4"></td>
        <td class="blanco_left_tr" colspan="6">BALANCEADA</td>
        <td class="blanco_left_tr" colspan="3"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="11">DERMATOMA</td>
        <td class="blanco_left_tr" colspan="21"></td>
        <td class="blanco_left_tr" colspan="9">POSICIÓN</td>
        <td class="blanco_left_tr" colspan="9"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr"></td>
        <td class="blanco_left_tr" colspan="4">1</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="4">2</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="4">3</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="4">4</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="3">5</td>
        <td class="blanco_left_tr" colspan="2"></td>
        <td class="blanco_left_tr" colspan="2">6</td>
        <td class="blanco_left_tr" colspan="2"></td>
    </tr>
    <tr style="height: 12px">
        <td class="s107"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s107"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s107"></td>
        <td class="s109"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s108"></td>
        <td class="s110"></td>
    </tr>
</table>
<table style="border: none">
    <tr style="height: 21px">
        <td class="s138">SNS-MSP / HCU-form.018A/2021</td>
        <td class="s140">TRANSANESTÉSICO (1)</td>
    </tr>
</table>
<pagebreak>
    <table class="trans">
        <tr style="height: 18px">
            <td class="morado_tr" colspan="42">G. ACCESOS VASCULARES</td>
            <td class="morado_tr" colspan="48">H. REPOSICIÓN VOLÉMICA (ml)</td>
            <td class="morado_tr" colspan="48">I. PERDIDAS</td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_left_tr" colspan="6"><b>TIPO</b></td>
            <td class="blanco_tr" colspan="7"><b>CALIBRE</b></td>
            <td class="blanco_tr" colspan="29"><b>SITIO</b></td>
            <td class="blanco_left_tr" colspan="12">DEXTROSA 5%</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12">SANGRE</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12">SANGRADO</td>
            <td class="blanco_left_tr" colspan="12"><b>MINIMO</b></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_left_tr" colspan="6">IV PERIFÉRICO 1</td>
            <td class="blanco_tr" colspan="7"><b>"22"</b></td>
            <td class="blancO" colspan="29"><b>ANTEBRAZO</b></td>
            <td class="blanco_left_tr" colspan="12">DEXTROSA 10%</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12">PLASMA</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12">DIURESIS</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_left_tr" colspan="6">IV PERIFÉRICO 2</td>
            <td class="blanco_left_tr" colspan="7"></td>
            <td class="blanco_left_tr" colspan="29"></td>
            <td class="blanco_left_tr" colspan="12">DEXTROSA 50%</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12">PLAQUETAS</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12">OTROS</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_left_tr" colspan="6">IV PERIFÉRICO 3</td>
            <td class="blanco_left_tr" colspan="7"></td>
            <td class="blanco_left_tr" colspan="29"></td>
            <td class="blanco_left_tr" colspan="12">DEXTROSA EN SS</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12">CRIOPRECIPITADOS</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12">TOTAL</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_left_tr" colspan="6">IV CENTRAL</td>
            <td class="blanco_left_tr" colspan="7"></td>
            <td class="blanco_left_tr" colspan="29"></td>
            <td class="blanco_left_tr" colspan="12">SS 0.9%</td>
            <td class="blanco_tr" colspan="12"><b>1000 ML</b></td>
            <td class="blanco_left_tr" colspan="12" rowspan="2">OTROS</td>
            <td class="blanco_left_tr" colspan="12"><b>MANITOL 20% 100ML</b></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_left_tr" colspan="6">INTRA ARTERIAL</td>
            <td class="blanco_left_tr" colspan="7"></td>
            <td class="blanco_left_tr" colspan="29"></td>
            <td class="blanco_left_tr" colspan="12">LACTATO RINGER</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_left_tr" colspan="6">OTRO</td>
            <td class="blanco_left_tr" colspan="7"></td>
            <td class="blanco_left_tr" colspan="29"></td>
            <td class="blanco_left_tr" colspan="12">EXPANSORES</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"><b>TOTAL</b></td>
            <td class="blanco_tr" colspan="12"><b>1100 ML</b></td>
            <td class="blanco_left_tr" colspan="12">BALANCE</td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
        </tr>
        <tr style="height: 12px">
            <td class="s124"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s124"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s124"></td>
            <td class="s126"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s125"></td>
            <td class="s127"></td>
        </tr>
    </table>
    <table class="trans">
        <tr style="height: 18px">
            <td class="morado_tr" colspan="22">J. DATOS DEL RECIÉN NACIDO</td>
            <td class="morado_tr" colspan="116">K. TIEMPOS TRANSCURRIDOS</td>
        </tr>
        <tr style="height: 16px">
            <td class="blanco_left_tr" colspan="4" rowspan="2">APGAR</td>
            <td class="blanco_left_tr" colspan="6">FETO MUERTO</td>
            <td class="blanco_left_tr" colspan="2"></td>
            <td class="blanco_left_tr" colspan="8">5 MINUTOS</td>
            <td class="blanco_left_tr" colspan="2"></td>
            <td class="verde_tr" colspan="14">DURACIÓN ANESTESIA</td>
            <td class="blanco_left_tr" colspan="14">
                <?php
                echo $pot_hpre;
                ?>
            </td>
            <td class="verde_tr" colspan="13">DURACIÓN DE CIRUGÍA</td>
            <td class="blanco_left_tr" colspan="27">
                <?php echo $pot_hfinal;
                ?>
            </td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
        </tr>
        <tr style="height: 16px">
            <td class="blanco_left_tr" colspan="6">1 MINUTO</td>
            <td class="blanco_left_tr" colspan="2"></td>
            <td class="blanco_left_tr" colspan="8">10 MINUTOS</td>
            <td class="blanco_left_tr" colspan="2"></td>
            <td class="blanco_left_tr" colspan="8"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
            <td class="blanco_left_tr" colspan="12"></td>
        </tr>
        <tr style="height: 12px">
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
            <td class="s92"></td>
        </tr>
    </table>
    <table class="trans">
        <tr style="height: 12px">
            <td class="morado_tr" colspan="68">L. TÉCNICAS ESPECIALES</td>
        </tr>
        <tr style="height: 23px">
            <td class="blanco_tr" colspan="11">HEMODILUCIÓN</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">AUTOTRANSFUSIÓN</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">HIPOTENSIÓN</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">HIPOTERMIA</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="13">CIRCULACIÓN EXTRACORPÓREA</td>
            <td class="blanco_tr" colspan="3"></td>
        </tr>
    </table>
    <table class="trans">
        <tr style="height: 12px">
            <td class="morado_tr" colspan="68">M. MANTENIMIENTO TEMPERATURA CORPORAL</td>
        </tr>
        <tr style="height: 17px">
            <td class="blanco_tr" colspan="11">MANTA TÉRMICA</td>
            <td class="blanco_tr" colspan="2">X</td>
            <td class="blanco_tr" colspan="11">CALENTAMIENTO DE FLUIDOS</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="4">OTROS</td>
            <td class="blanco_tr" colspan="38"></td>
        </tr>
    </table>
    <table class="trans">
        <tr style="height: 20px">
            <td class="morado_tr" colspan="68">N. INCIDENTES</td>
        </tr>
        <tr style="height: 23px">
            <td class="blanco_tr" colspan="11">ACTIVIDAD ELÉCTRICA SIN PULSO</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">ARRITMIA</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">ASISTOLIA</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">BRADICARDIA INESTABLE</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="13">TROMBOEMBOLIA PULMONAR</td>
            <td class="blanco_tr" colspan="3"></td>
        </tr>
        <tr style="height: 16px">
            <td class="blanco_tr" colspan="11">HIPERTERMIA MALIGNA</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">ANAFILAXIA</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">ISQUEMIA MIOCÁRDICA</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">HIPOXEMIA</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="13">NEUMOTÓRAX</td>
            <td class="blanco_tr" colspan="3"></td>
        </tr>
        <tr style="height: 16px">
            <td class="blanco_tr" colspan="11">BRONCOESPASMO</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">DESPERTAR PROLONGADO</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">EMBOLIA AÉREA VENOSA</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="11">REACCIÓN A LA TRANSFUSIÓN</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="13">LARINGOESPASMO</td>
            <td class="blanco_tr" colspan="3"></td>
        </tr>
        <tr style="height: 23px">
            <td class="blanco_tr" colspan="11">DIFICULTAD DE LA TÉCNICA</td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="55">OTROS</td>
        </tr>
    </table>
    <table class="trans">
        <tr style="height: 20px">
            <td class="morado_tr" colspan="68">O. RESULTADO DE EXÁMENES DE LABORATORIO</td>
        </tr>
        <tr style="height: 16px">
            <td class="blanco_tr"></td>
            <td class="blanco_tr" colspan="4">HORA</td>
            <td class="blanco_tr" colspan="5">pH</td>
            <td class="blanco_tr" colspan="5">PO2</td>
            <td class="blanco_tr" colspan="5">PCO2</td>
            <td class="blanco_tr" colspan="5">HCO3</td>
            <td class="blanco_tr" colspan="5">EB</td>
            <td class="blanco_tr" colspan="5">SAT. O2</td>
            <td class="blanco_tr" colspan="5">LACTATO</td>
            <td class="blanco_tr" colspan="5">GLUCOSA</td>
            <td class="blanco_tr" colspan="4">Na</td>
            <td class="blanco_tr" colspan="3">K</td>
            <td class="blanco_tr" colspan="3">Cl</td>
            <td class="blanco_tr" colspan="3">HCTO</td>
            <td class="blanco_tr" colspan="2">HB</td>
            <td class="blanco_tr" colspan="8">OTRO</td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_tr">1</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="8"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_tr">2</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="8"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_tr">3</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="8"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_tr">4</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="8"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_tr">5</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="8"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_tr">6</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="8"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_tr">7</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="8"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_tr">7</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="8"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_tr">9</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="8"></td>
        </tr>
        <tr style="height: 12px">
            <td class="blanco_tr">10</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="3"></td>
            <td class="blanco_tr" colspan="2"></td>
            <td class="blanco_tr" colspan="8"></td>
        </tr>
    </table>
    <table class="trans">
        <tr style="height: 20px">
            <td class="morado_tr" colspan="68">P. OBSERVACIONES</td>
        </tr>
        <tr style="height: 15px">
            <td class="blanco_tr" colspan="68"><br></td>
        </tr>
        <tr style="height: 15px">
            <td class="blanco_tr" colspan="68"><br></td>
        </tr>
        <tr style="height: 15px">
            <td class="blanco_tr" colspan="68"><br></td>
        </tr>
        <tr style="height: 15px">
            <td class="blanco_tr" colspan="68"><br></td>
        </tr>
        <tr style="height: 15px">
            <td class="blanco_tr" colspan="68"><br></td>
        </tr>
        <tr style="height: 15px">
            <td class="blanco_tr" colspan="68"><br></td>
        </tr>
        <tr style="height: 15px">
            <td class="blanco_tr" colspan="68"><br></td>
        </tr>
    </table>
    <table class="trans">
        <tr style="height: 20px">
            <td class="morado_tr" colspan="68">R. CONDICIÓN DE EGRESO</td>
        </tr>
        <tr style="height: 23px">
            <td class="blanco_tr" colspan="13" rowspan="2">CONDICIONES AL SALIR</td>
            <td class="blanco_tr" colspan="7">EXTUBADO</td>
            <td class="blanco_tr" colspan="4"></td>
            <td class="blanco_tr" colspan="7" rowspan="2">CONDUCIDO A:</td>
            <td class="blanco_tr" colspan="7" rowspan="2">UNIDAD DE CUIDADOS POST ANESTÉSICOS</td>
            <td class="blanco_tr" colspan="3" rowspan="2">X</td>
            <td class="blanco_tr" colspan="6" rowspan="2">UNIDAD CUIDADOS INTENSIVOS</td>
            <td class="blanco_tr" colspan="3" rowspan="2"></td>
            <td class="blanco_tr" colspan="7" rowspan="2">CRÍTICOS DE EMERGENCIA</td>
            <td class="blanco_tr" colspan="3" rowspan="2"></td>
            <td class="blanco_tr" colspan="5" rowspan="2">MORGUE</td>
            <td class="blanco_tr" colspan="3" rowspan="2"></td>
        </tr>
        <tr style="height: 23px">
            <td class="blanco_tr" colspan="7">INTUBADO</td>
            <td class="blanco_tr" colspan="4"></td>
        </tr>
        <tr style="height: 23px">
            <td class="blanco_tr" colspan="13">CONSTANTES VITALES DE ENTREGA</td>
            <td class="blanco_tr" colspan="14">TA: 103/96</td>
            <td class="blanco_tr" colspan="10">FC: 74</td>
            <td class="blanco_tr" colspan="10">FR: 20</td>
            <td class="blanco_tr" colspan="12">SAT. O2: 99%</td>
            <td class="blanco_tr" colspan="9">T: 37.5°</td>
        </tr>
    </table>
    <table class="trans">
        <tr style="height: 20px">
            <td class="morado_tr" colspan="68">S. DATOS DEL PROFESIONAL RESPONSABLE</td>
        </tr>
        <tr style="height: 50px">
            <td class="verde_tr" style="height: 40px" colspan="3">HORA</td>
            <td class="blanco_tr" colspan="5"></td>
            <td class="verde_tr" colspan="12">NOMBRE Y APELLIDO DEL PROFESIONAL</td>
            <td class="blanco_tr"
                colspan="18"><?php echo $anestesiologo; ?></td>
            <td class="verde_tr" colspan="4">FIRMA</td>
            <td class="blanco_tr" colspan="12"></td>
            <td class="verde_tr" colspan="4">SELLO Y CÓDIGO</td>
            <td class="blanco_tr" colspan="10"></td>
        </tr>
    </table>
    <table style="border: none">
        <tr style="height: 21px">
            <td class="s138">SNS-MSP / HCU-form.018A/2021</td>
            <td class="s140">TRANSANESTÉSICO (2)</td>
        </tr>
    </table>
</pagebreak>
</body>
