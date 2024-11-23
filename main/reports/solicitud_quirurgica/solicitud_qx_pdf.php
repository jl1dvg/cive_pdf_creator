<?php
session_start();

require '../../../vendor/autoload.php';

use Mpdf\Mpdf;

// Configuración personalizada de mPDF
$mpdf = new Mpdf([
    'default_font_size' => 8,
    'default_font' => 'dejavusans',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'orientation' => 'P',  // Orientación predeterminada en vertical
    'shrink_tables_to_fit' => 1,
    'use_kwt' => true,
    'autoScriptToLang' => true,
    'keep_table_proportions' => true,
]);

// Función para cargar HTML desde un archivo
function cargarHTML($archivo)
{
    ob_start();
    include $archivo;
    return ob_get_clean();
}

// Páginas/formularios en el PDF
$paginas = ['010.php', 'referencia.php'];

// Incluir CSS específico para cada página
$totalPaginas = count($paginas);
foreach ($paginas as $index => $pagina) {
    if ($pagina === '010.php') {
        $stylesheet = file_get_contents('styles.css');
    } elseif ($pagina === 'referencia.php') {
        $stylesheet = file_get_contents('referencia.css');
    }
    $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

    $html = cargarHTML($pagina);
    $mpdf->WriteHTML($html);

    // Solo agregar una nueva página si no es la última página en el ciclo
    if ($index < $totalPaginas - 1) {
        $mpdf->AddPage();
    }
}

// Generar y mostrar el PDF
$mpdf->Output('informacion_paciente.pdf', 'I');
?>