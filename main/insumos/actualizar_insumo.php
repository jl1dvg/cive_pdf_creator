<?php
require '../../conexion.php';  // Ajusta el path si es necesario

header('Content-Type: application/json');

$input = file_get_contents('php://input');
file_put_contents('debug_input.txt', $input . PHP_EOL, FILE_APPEND);
$data = json_decode($input, true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.', 'raw' => $input]);
    exit;
}

$id = $data['id'] ?? null;
$campos = [
    'nombre', 'categoria', 'codigo_issfa', 'codigo_isspol', 'codigo_iess', 'codigo_msp',
    'producto_issfa', 'precio_base', 'iva_15', 'gestion_10', 'precio_total', 'precio_isspol'
];

// Validar campos requeridos
foreach ($campos as $campo) {
    if (!isset($data[$campo])) {
        echo json_encode(['success' => false, 'message' => "Campo faltante: $campo"]);
        exit;
    }
}

// Convertir campos numéricos vacíos a null
$numericos = ['precio_base', 'iva_15', 'gestion_10', 'precio_total', 'precio_isspol'];
foreach ($numericos as $campo) {
    if ($data[$campo] === '') {
        $data[$campo] = null;
    }
}
if ($data['codigo_iess'] === '') {
    $data['codigo_iess'] = null;
}

if ($id) {
    // Actualizar
    $sql = "UPDATE insumos SET 
                nombre=?, categoria=?, codigo_issfa=?, codigo_isspol=?, codigo_iess=?, codigo_msp=?, 
                producto_issfa=?, precio_base=?, iva_15=?, gestion_10=?, 
                precio_total=?, precio_isspol=? 
            WHERE id=?";
    $stmt = $mysqli->prepare($sql);
} else {
    // Insertar
    $sql = "INSERT INTO insumos 
                (nombre, categoria, codigo_issfa, codigo_isspol, codigo_iess, codigo_msp, 
                 producto_issfa, precio_base, iva_15, gestion_10, 
                 precio_total, precio_isspol) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
}

if ($stmt) {
    if ($id) {
        $stmt->bind_param(
            'ssssssssssssi',
            $data['nombre'], $data['categoria'], $data['codigo_issfa'], $data['codigo_isspol'], $data['codigo_iess'], $data['codigo_msp'],
            $data['producto_issfa'], $data['precio_base'], $data['iva_15'], $data['gestion_10'],
            $data['precio_total'], $data['precio_isspol'], $id
        );
    } else {
        $stmt->bind_param(
            'ssssssssssss',
            $data['nombre'], $data['categoria'], $data['codigo_issfa'], $data['codigo_isspol'], $data['codigo_iess'], $data['codigo_msp'],
            $data['producto_issfa'], $data['precio_base'], $data['iva_15'], $data['gestion_10'],
            $data['precio_total'], $data['precio_isspol']
        );
    }

    if ($stmt->execute()) {
        $nuevoId = $id ?: $stmt->insert_id;
        echo json_encode(['success' => true, 'message' => 'Insumo guardado correctamente.', 'id' => $nuevoId]);
    } else {
        file_put_contents('debug_sql_error.txt', "SQL error: " . $stmt->error . PHP_EOL, FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $stmt->error]);
    }
} else {
    file_put_contents('debug_sql_error.txt', "Prepare error: " . $mysqli->error . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $mysqli->error]);
}

$stmt->close();
$mysqli->close();