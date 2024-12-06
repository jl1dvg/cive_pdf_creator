<?php
session_start();

require '../../../vendor/autoload.php';

use Mpdf\Mpdf;

// Obtener parámetros de la URL
$hc_number = isset($_GET['hc_number']) ? $_GET['hc_number'] : null;
$form_id = isset($_GET['form_id']) ? $_GET['form_id'] : null;

if (!$hc_number || !$form_id) {
    die("Faltan parámetros necesarios.");
}

// Configuración personalizada de mPDF
$mpdf = new Mpdf([
    'default_font_size' => 8,
    'default_font' => 'dejavusans',
    'margin_left' => 3,
    'margin_right' => 3,
    'margin_top' => 3,
    'margin_bottom' => 3,
    'orientation' => 'P',
    'shrink_tables_to_fit' => 1,
    'use_kwt' => true,
    'autoScriptToLang' => true,
    'keep_table_proportions' => true,
]);

// Función para cargar HTML desde un archivo y pasar parámetros
function cargarHTML($archivo, $hc_number, $form_id)
{
    ob_start();
    include $archivo;
    return ob_get_clean();
}

// Páginas/formularios en el PDF
$paginas = ['007.php', 'referencia.php', '010.php'];

// Incluir CSS específico para cada página
$totalPaginas = count($paginas);
foreach ($paginas as $index => $pagina) {
    if ($pagina === '010.php') {
        $stylesheet = file_get_contents('styles.css');
    } elseif ($pagina === '007.php' || $pagina === 'referencia.php') {
        $stylesheet = file_get_contents('referencia.css');
    }
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    // Cargar el contenido HTML de cada página pasando los parámetros
    $html = cargarHTML($pagina, $hc_number, $form_id);
    $mpdf->WriteHTML($html);

    // Solo agregar una nueva página si no es la última página en el ciclo
    if ($index < $totalPaginas - 1) {
        $mpdf->AddPage();
    }
}

// Generar y mostrar el PDF
$mpdf->Output('informacion_paciente.pdf', 'I');
?>