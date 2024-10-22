<?php
session_start();

require 'vendor/autoload.php';

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
function cargarHTML($archivo) {
    ob_start();
    include $archivo;
    return ob_get_clean();
}

// Incluir el archivo CSS externo general
$stylesheet = file_get_contents('styles.css');
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

// Páginas/formularios en el PDF
$paginas = ['protocolo.php', '005.php', 'medicamentos.php', 'saveqx.php'];

// Incluir cada página y agregar una nueva página solo si no es la última
$totalPaginas = count($paginas);
foreach ($paginas as $index => $pagina) {
    $html = cargarHTML($pagina);
    $mpdf->WriteHTML($html);

    // Solo agregar una nueva página si no es la última página en el ciclo
    if ($index < $totalPaginas - 1) {
        $mpdf->AddPage();
    }
}

// Cambiar la orientación a horizontal (Landscape) para la página 'transanestesico'
$mpdf->AddPage('L');  // Cambia a horizontal

// Incluir el contenido de transanestesico.php
$htmlTransanestesico = cargarHTML('transanestesico.php');
$mpdf->WriteHTML($htmlTransanestesico);

// Generar y mostrar el PDF
$mpdf->Output('informacion_paciente.pdf', 'I');
?>