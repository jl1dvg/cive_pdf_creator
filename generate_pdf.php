<?php
require 'vendor/autoload.php';

use Mpdf\Mpdf;

// Configuración personalizada de mPDF
$mpdf = new Mpdf([
    'default_font_size' => 8,
    'default_font' => 'dejavusans',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 8,
    'margin_bottom' => 8,
    'orientation' => 'P',
    'shrink_tables_to_fit' => 1,
    'use_kwt' => true,
    'autoScriptToLang' => true,
    'keep_table_proportions' => true
]);

// Incluir el archivo CSS externo
$stylesheet = file_get_contents('styles.css');
$mpdf->WriteHTML($stylesheet, 1);

// Incluir el contenido de las páginas/formularios
ob_start();
include 'page1.php';
$html = ob_get_clean();
$mpdf->WriteHTML($html);
//$mpdf->AddPage();

ob_start();
include 'protocolo.php';
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->AddPage();

// Agrega más páginas según sea necesario
ob_start();
include '005.php';
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->AddPage();

ob_start();
include 'medicamentos.php';
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->AddPage();

ob_start();
include 'saveqx.php';
$html = ob_get_clean();
$mpdf->WriteHTML($html);
$mpdf->AddPage();

// Mostrar el PDF en la misma página
$mpdf->Output('informacion_paciente.pdf', 'I');
?>
