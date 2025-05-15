<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include 'includes/MySQL.php'; // Incluye el archivo de conexión a la base de datos

// Inicia la sesión al inicio para acceder a $_SESSION
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica si se recibieron los datos necesarios
if (isset($_POST['dni']) && isset($_POST['hora']) && isset($_POST['dia']) && isset($_POST['fecha'])) {
    // Obtiene los datos del POST
    $dni = $mysqli->real_escape_string($_POST['dni']); // Asumiendo que el DNI viene de POST
    $hora = $mysqli->real_escape_string($_POST['hora']);
    $dia = $mysqli->real_escape_string($_POST['dia']);
    $fecha = $mysqli->real_escape_string($_POST['fecha']);
    $dni = $_SESSION['dni'];

    // Convierte la fecha a formato 'yyyy-mm-dd'
    $fechaObj = DateTime::createFromFormat('d/m/Y', $fecha);
    if (!$fechaObj) {
        echo json_encode(['success' => false, 'message' => 'Formato de fecha inválido']);
        exit;
    }
    $fechaConvertida = $fechaObj->format('Y-m-d');

    // Verifica si ya existe una inscripción para el mismo dni y fecha
    $checkSql = "SELECT * FROM inscripciones WHERE dni = '$dni' AND fecha = '$fechaConvertida'";
    $result = $mysqli->query($checkSql);

    if ($result && $result->num_rows > 0) {
        // Ya existe una inscripción para ese día
        echo json_encode(['success' => false, 'message' => 'Ya estás inscrito para este día']);
    } else {
        // Verifica la cantidad de inscripciones para la fecha y hora
        $cuposSql = "SELECT COUNT(*) AS count FROM inscripciones WHERE fecha = '$fechaConvertida' AND hora = '$hora'";
        $cuposResult = $mysqli->query($cuposSql);
        $cuposData = $cuposResult->fetch_assoc();

        if ($cuposData['count'] >= 80) {
            echo json_encode(['success' => false, 'message' => 'Lo sentimos, no queda cupo en ese horario']);
        } else {
            // Prepara el SQL para verificar la existencia en usuarios y comedor_ver
            $checkSql2 = "SELECT u.* FROM usuarios u INNER JOIN comedor_ver c ON u.apellido = c.apellido AND u.nombre = c.nombre AND u.curso = c.curso AND u.division = c.division WHERE u.dni = '$dni'";
            $result2 = $mysqli->query($checkSql2);

            if ($result2 && $result2->num_rows > 0) {
                // Inserta la inscripción
                $sql = "INSERT INTO inscripciones (dni, hora, dia, fecha) VALUES ('$dni', '$hora', '$dia', '$fechaConvertida')";

                // Ejecuta la consulta
                if ($mysqli->query($sql) === TRUE) {
                    echo json_encode(['success' => true, 'message' => 'Inscripción realizada correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error: ' . $mysqli->error]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No estás en el listado, contacta con algún directivo :)']);
            }
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}

$mysqli->close(); // Cierra la conexión a la base de datos
?>
