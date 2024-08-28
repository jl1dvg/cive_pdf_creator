<?php
require 'vendor/autoload.php';

use Mpdf\Mpdf;

// Obtener el nombre del archivo JSON desde GET
$jsonFile = isset($_GET['file']) ? $_GET['file'] : 'data.json';

// Cargar y decodificar el contenido del archivo JSON
$data = json_decode(file_get_contents(__DIR__ . '/data/' . $jsonFile), true);

// Crear una instancia de mPDF
$mpdf = new Mpdf();

// Crear el contenido HTML del PDF
$html = "
<h1>{$data['title']}</h1>
<p><strong>Author:</strong> {$data['author']}</p>
<div>{$data['content']}</div>
";

// Escribir el contenido en el PDF
$mpdf->WriteHTML($html);

// Generar el PDF
$mpdf->Output('document.pdf', 'D');
?>
