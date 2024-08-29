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
        <td class="verde" colspan="10">ALERGIA A MEDICAMENTOS</td>
        <td class="verde" colspan="2">SI</td>
        <td class="blanco_left" colspan="2"></td>
        <td class="verde" colspan="2">NO</td>
        <td class="blanco_left" colspan="2"></td>
        <td class="verde" colspan="7">DESCRIBA:</td>
        <td class="blanco_left" colspan="52"></td>
    </tr>
</table>
<table>
    <tr>
        <td class="morado" colspan="77">B. ADMINISTRACIÓN DE MEDICAMENTOS PRESCRITOS</td>
    </tr>
    <tr>
        <td class="morado" colspan="17" style="border-top: 1px solid #808080; border-right: 1px solid #808080;">1.
            MEDICAMENTO
        </td>
        <td class="morado" colspan="60" style="border-top: 1px solid #808080;">2. ADMINISTRACIÓN</td>
    </tr>
    <tr>
        <td class="verde" colspan="17">FECHA</td>
        <td class="blanco" colspan="15"><?php echo $fechaDia . "/" . $fechaMes . "/" . $fechaAno; ?></td>
        <td class="blanco" colspan="15"></td>
        <td class="blanco" colspan="15"></td>
        <td class="blanco" colspan="15"></td>
    </tr>
    <tr>
        <td class="verde" colspan="17">DOSIS, VIA, FRECUENCIA</td>
        <td class="verde" colspan="6">HORA</td>
        <td class="verde" colspan="9">RESPONSABLE</td>
        <td class="verde" colspan="6">HORA</td>
        <td class="verde" colspan="9">RESPONSABLE</td>
        <td class="verde" colspan="6">HORA</td>
        <td class="verde" colspan="9">RESPONSABLE</td>
        <td class="verde" colspan="6">HORA</td>
        <td class="verde" colspan="9">RESPONSABLE</td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            CLORURO DE SODIO 0,9% LIQUIDO PARENTERAL (1000ML) 60 GOTAS POR
            MINUTO, INTRAVENOSA, STAT.
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_hfinal; // Mostrar el nuevo valor de la hora?></td>
        <td class="blanco"
            colspan="9"><?php echo $anestesiologo; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            MANITOL 20% LIQUIDO PARENTERAL 500 MILILITROS INTRAVENOSA, STAT.
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_hfinal; // Mostrar el nuevo valor de la hora?></td>
        <td class="blanco"
            colspan="9"><?php echo $anestesiologo; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            MIDAZOLAM LIQUIDO PARENTERAL 5MG/ML (3ML) DOSIS: 2,5 MILIGRAMOS/0,5 MILILITRO, INTRAVENOSA,STAT.
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_hfinal; // Mostrar el nuevo valor de la hora?></td>
        <td class="blanco"
            colspan="9"><?php echo $anestesiologo; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            FENTANILO LIQUIDO PARENTERAL 0,05MG/ ML (10ML) DOSIS: 60 MICROGRAMOS/ 1MILILITRO, INTRAVENOSO, STAT
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_hfinal; // Mostrar el nuevo valor de la hora?></td>
        <td class="blanco"
            colspan="9"><?php echo $anestesiologo; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            CEFTRIAXONA SOLIDO PARENTERAL 1000MG DOSIS: 1000MILIGRAMOS DILUIDO EN 100MILILITROS DE CLORURO DE SODIO
            AL 0,9% INTRAVENOSA, STAT
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_hfinal; ?></td>
        <td class="blanco"
            colspan="9"><?php echo $anestesiologo; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            KETOROLACO LIQUIDO PARENTERAL 30MG/ML (1ML) DOSIS: 60MILIGRAMOS DILUIDO EN 100 MILILITROS DE CLORURO DE
            SODIO AL 0,9%, INTRAVENOSA, STAT
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_hfinal; ?></td>
        <td class="blanco"
            colspan="9"><?php echo $anestesiologo; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            LIDOCAINA CON EPINEFRINA, LIQUIDO PARENTERAL 2% + 1,200,000 (50ML), DOSIS: 80MILIGRAMO /4 MILILITRO, VIA
            INFILTRATIVA, STAT.
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_hfinal; // Mostrar el nuevo valor de la hora?></td>
        <td class="blanco"
            colspan="9"><?php echo $anestesiologo; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            BUPIVACAINA SIN EPINEFRINA, LIQUIDO PARENTERAL 0,5% (20ML), DOSIS: 20 MILIGRAMO /4MILILITRO, VIA
            INFILTRATIVA, STAT.
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_hfinal; // Mostrar el nuevo valor de la hora?></td>
        <td class="blanco"
            colspan="9"><?php echo $anestesiologo; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            GENTAMICINA LIQUIDO PARENTERAL 80MG/ML (2ML) DOSIS: 160MILIGRAMOS /2 MILILITROS, SUBCONJUNTIVAL, STAT.
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_halta; ?></td>
        <td class="blanco" colspan="9"><?php echo $cirujanoPrincipal; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            DEXAMETASONA LIQUIDO PARENTERAL 4MG/DL (2ML) DOSIS: 8MILIGRAMOS /2MILILITROS, SUBCONJUNTIVAL, STAT.
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_halta; ?></td>
        <td class="blanco" colspan="9"><?php echo $cirujanoPrincipal; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            DEXAMETASONA + TOBRAMICINA LIQUIDO OFTALMOLOGICO 0,1%+0,3% (5ML) DOSIS: 1 GOTA, VIA TOPICA, STAT.
        </td>
        <td class="blanco" colspan="6"><?php echo $pot_halta; ?></td>
        <td class="blanco" colspan="9"><?php echo $cirujanoPrincipal; ?></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <!--
    <tr>
        <td class="blanco" colspan="17" rowspan="2">
            MITOMICINA SOLIDO PARENTERAL 20MG (20MILIGRAMOS DILUIDO EN 10 MILILITROS DE CLORURO DE SODIO AL 0,9%)
            DOSIS: 1MILILITRO, VIA TOPIA, STAT.
        </td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    <tr>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
        <td class="blanco" colspan="6"></td>
        <td class="blanco" colspan="9"></td>
    </tr>
    -->
</table>
<table style="border: none">
    <TR>
        <TD colspan="6" HEIGHT=24 ALIGN=LEFT VALIGN=M><B><FONT SIZE=1
                                                               COLOR="#000000"></FONT></B>
        </TD>
        <TD colspan="3" ALIGN=RIGHT VALIGN=TOP><B><FONT SIZE=3 COLOR="#000000">ADMINISTRACION DE MEDICAMENTOS
                    (1)</FONT></B>
        </TD>
    </TR>
</table>
</body>