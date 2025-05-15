<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
session_start();
include_once 'includes/MySQL.php';

// Verificar si se recibió el DNI a vincular
if (isset($_POST['dni_asoc'])) {
    $dni_asoc = $mysqli->real_escape_string($_POST['dni_asoc']);
    $dni_tutor = $_SESSION['dni']; // DNI del tutor desde la sesión

    // Verificar si el usuario ya está vinculado
    $stmt = $mysqli->prepare("SELECT dni FROM tutor WHERE dni = ? AND dni_asoc = ? LIMIT 1");
    $stmt->bind_param("ss", $dni_tutor, $dni_asoc);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(array("error" => "El estudiante ya está vinculado a este tutor."));
    } else {
        // Verificar si el usuario existe y es de nivel 3
        $stmt = $mysqli->prepare("SELECT dni FROM usuarios WHERE dni = ? AND nivel = 3 LIMIT 1");
        $stmt->bind_param('s', $dni_asoc);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Insertar el vínculo en la tabla tutor
            $stmt = $mysqli->prepare("INSERT INTO tutor (dni, dni_asoc) VALUES (?, ?)");
            $stmt->bind_param("ss", $dni_tutor, $dni_asoc);
            
            if ($stmt->execute()) {
                echo json_encode(array("success" => true, "message" => "Vinculación exitosa."));
            } else {
                echo json_encode(array("error" => 'Error al vincular: ' . $stmt->error));
            }
        } else {
            // El usuario no existe o no es de nivel 3
            echo json_encode(array("error" => "El usuario no está registrado como estudiante."));
        }
    }
    $stmt->close();
} else {
    // Respuesta JSON si falta el DNI
    echo json_encode(array("error" => 'Falta el DNI a vincular.'));
}

$mysqli->close();
?>
