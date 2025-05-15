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

// Configuración para el modo de prueba
$modoPrueba = true; // Cambia a false para desactivar el modo de prueba

// Verifica si se recibieron los datos necesarios
if (isset($_POST['dni']) && isset($_POST['dia']) && isset($_POST['fecha'])) {
    // Obtiene los datos del POST
    session_start();
    $dni = $_SESSION['dni'];
    $dia = $mysqli->real_escape_string($_POST['dia']);
    $fecha = $mysqli->real_escape_string($_POST['fecha']);
    
    // Convierte la fecha a formato 'yyyy-mm-dd'
    $fechaConvertida = DateTime::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
    
    // Verifica si existe una inscripción para el dni, fecha y día especificados
    $checkSql = "SELECT * FROM inscripciones WHERE dni = '$dni' AND fecha = '$fechaConvertida'";
    $result = $mysqli->query($checkSql);
    
    if ($result->num_rows > 0) {
        // Verifica el horario actual
        $now = new DateTime();
        $now->setTimezone(new DateTimeZone('America/Argentina/Buenos_Aires'));

        $startLimit = new DateTime('07:30:00');
        $endLimit = new DateTime('09:30:00');

        // Si el modo de prueba está activado, se omite la verificación del horario
        if (!$modoPrueba && ($now < $startLimit || $now > $endLimit)) {
            echo json_encode(['success' => false, 'message' => 'El horario para cancelar la inscripción es de 7:30 hs a 9:30 hs. Comunícate con un preceptor.']);
        } else {
            $deleteSql = "DELETE FROM inscripciones WHERE dni = '$dni' AND fecha = '$fechaConvertida'";
            
            if ($mysqli->query($deleteSql) === TRUE) {
                echo json_encode(['success' => true, 'message' => 'Inscripción cancelada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $mysqli->error]);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No tienes inscripción para este día']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}

$mysqli->close(); // Cierra la conexión a la base de datos
?>
