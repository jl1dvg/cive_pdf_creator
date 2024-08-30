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

// Ahora puedes usar $diagnostico1, $diagnostico2 y $diagnostico3 como necesites

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
        <td class="blanco" colspan="41"><?php echo $cleanedProcedure; ?></td>
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
        <td class="blanco_unbordered" colspan="2">X</td>
        <td class="blanco_unbordered" colspan="2">OI</td>
        <td class="blanco_unbordered" colspan="2" style="border-right: 1px solid #808080;">X</td>
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
        <td class="blanco" colspan="15">JENIFFER BAQUE Z.</td>
        <td class="blanco" colspan="16">DR. <?php echo $cirujanoPrincipal; ?></td>
        <td class="blanco" colspan="15">DR. <?php echo $anestesiologo; ?></td>
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
        <td class="blanco" colspan="15" rowspan="5"></td>
        <td class="blanco" colspan="16" rowspan="5"></td>
        <td class="blanco" colspan="15" rowspan="5"></td>
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