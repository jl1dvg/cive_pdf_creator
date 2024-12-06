<?php

function getPatientData($mysqli, $hc_number)
{
    // Consulta SQL para obtener los datos del paciente
    $sql = "SELECT p.hc_number, p.fname, p.mname, p.lname, p.lname2, p.fecha_nacimiento, p.afiliacion, p.sexo, p.ciudad, p.celular
            FROM patient_data p
            WHERE p.hc_number = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $hc_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null; // No se encontraron datos
}

function calculatePatientAge($birthDate, $currentDate = null)
{
    $birthDate = new DateTime($birthDate);
    $currentDate = $currentDate ? new DateTime($currentDate) : new DateTime();
    $age = $currentDate->diff($birthDate);
    return $age->y; // Retorna la edad en años
}

?>
