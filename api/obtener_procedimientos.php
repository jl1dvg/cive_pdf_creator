<?php
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Incluir tu archivo de conexión a la base de datos
include '../conexion.php';

// Consulta SQL para obtener los procedimientos
$sql = "SELECT * FROM procedimientos";
$result = $mysqli->query($sql);

$procedimientos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $procedimiento_id = $row['id'];

        // Obtener técnicos relacionados
        $sql_tecnicos = "SELECT * FROM procedimientos_tecnicos WHERE procedimiento_id = '$procedimiento_id'";
        $result_tecnicos = $mysqli->query($sql_tecnicos);
        $tecnicos = [];
        while ($tecnico = $result_tecnicos->fetch_assoc()) {
            $tecnicos[] = $tecnico;
        }

        // Obtener códigos relacionados
        $sql_codigos = "SELECT * FROM procedimientos_codigos WHERE procedimiento_id = '$procedimiento_id'";
        $result_codigos = $mysqli->query($sql_codigos);
        $codigos = [];
        while ($codigo = $result_codigos->fetch_assoc()) {
            $codigos[] = $codigo;
        }

        // Obtener diagnósticos relacionados
        $sql_diagnosticos = "SELECT * FROM procedimientos_diagnosticos WHERE procedimiento_id = '$procedimiento_id'";
        $result_diagnosticos = $mysqli->query($sql_diagnosticos);
        $diagnosticos = [];
        while ($diagnostico = $result_diagnosticos->fetch_assoc()) {
            $diagnosticos[] = $diagnostico;
        }

        // Añadir todos los datos al procedimiento principal
        $procedimientos[] = [
            "id" => $row['id'],
            "cirugia" => $row['cirugia'],
            "categoria" => $row['categoria'],
            "membrete" => $row['membrete'],
            "medicacion" => $row['medicacion'],
            "cardex" => $row['cardex'],
            "dieresis" => $row['dieresis'],
            "exposicion" => $row['exposicion'],
            "hallazgo" => $row['hallazgo'],
            "operatorio" => $row['operatorio'],
            "complicacionesoperatorio" => $row['complicacionesoperatorio'],
            "perdidasanguineat" => $row['perdidasanguineat'],
            "horas" => $row['horas'],
            "staffCount" => $row['staffCount'],
            "codigoCount" => $row['codigoCount'],
            "diagnosticoCount" => $row['diagnosticoCount'],
            "tecnicos" => $tecnicos,
            "codigos" => $codigos,
            "diagnosticos" => $diagnosticos
        ];
    }
}

echo json_encode(["procedimientos" => $procedimientos]);

$mysqli->close();
?>
