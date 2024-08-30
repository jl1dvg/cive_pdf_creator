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

function cleanProcedureName($procedure)
{
    // Encontrar la posición del primer guion seguido de un espacio o letras mayúsculas seguidas de un guion
    if (preg_match('/^[A-Z]+-[A-Z]+-\d+-(.+)/', $procedure, $matches) || preg_match('/^\d+-/', $procedure)) {
        // Si se encuentra un patrón con guiones y números al inicio
        $procedure = preg_replace('/^[A-Z]+-[A-Z]+-\d+-|^\d+-/', '', $procedure);
    }
    return $procedure;
}

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
        <td class='blanco_left' colspan='7'><?php echo substr($diagnosticoPRE1, 6); ?></td>
        <td class='blanco' width='20%' colspan='2'><?php echo substr($diagnosticoPRE1, 0, 4); ?></td>
    </tr>
    <tr>
        <td class='verde_left' width='2%'>2.</td>
        <td class='blanco_left' colspan='7'><?php echo substr($diagnosticoPRE2, 6); ?></td>
        <td class='blanco' width='20%' colspan='2'><?php echo substr($diagnosticoPRE2, 0, 4); ?></td>
    </tr>
    <tr>
        <td class='verde_left' width='2%'>3.</td>
        <td class='blanco_left' colspan='7'><?php echo substr($diagnosticoPRE3, 6); ?></td>
        <td class='blanco' width='20%' colspan='2'><?php echo substr($diagnosticoPRE3, 0, 4); ?></td>
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
        <td class='blanco_left' colspan='7'></td>
        <td class='blanco' colspan='2'></td>
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
        <td class='blanco_left' colspan='18'><?php echo $cleanedProcedure; ?></td>
    </tr>
    <tr>
        <td colspan='2' class='verde_left'>Realizado:</td>
        <td class='blanco_left' colspan='18'><?php echo $formattedRealizedProcedure; ?></td>
    </tr>
</table>
<table>
    <tr>
        <td class='morado' colspan='20'>D. INTEGRANTES DEL EQUIPO QUIRÚRGICO</td>
    </tr>
    <tr>
        <td class='verde_left' colspan='3'>Cirujano 1:</td>
        <td class='blanco' colspan='7'><?php echo $cirujanoPrincipal; ?></td>
        <td class='verde_left' colspan='3'>Instrumentista:</td>
        <td class='blanco' colspan='7'><?php echo $instrumentista; ?></td>
    </tr>
    <tr>
        <td class='verde_left' colspan='3'>Cirujano 2:</td>
        <td class='blanco' colspan='7'></td>
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
        <td class='blanco' colspan='7'><?php echo $ayudante2; ?></td>
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
            <td class='blanco' style='height: 60' colspan='5'><?php echo $cirujanoPrincipal; ?></td>
            <td class='blanco' colspan='5'></td>
            <td class='blanco' colspan='5'></td>
            <td class='blanco' colspan='5'></td>
        </tr>
        <tr>
            <td class='blanco' style='height: 60'
                colspan='5'><?php echo $ayudante; ?>
            </td>
            <td class='blanco'
                colspan='5'></td>
            <td class='blanco'
                colspan='5'></td>
            <td class='blanco'
                colspan='5'></td>
        </tr>
        <tr>
            <td class='blanco' style='height: 60'
                colspan='5'><?php echo $anestesiologo; ?>
            </td>
            <td class='blanco'
                colspan='5'></td>
            <td class='blanco'
                colspan='5'></td>
            <td class='blanco'
                colspan='5'></td>
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
