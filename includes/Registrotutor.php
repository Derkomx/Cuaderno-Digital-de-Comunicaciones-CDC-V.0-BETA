<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include_once 'includes/MySQL.php';

// Verificar si se recibieron los datos necesarios
if (isset($_POST['dni'], $_POST['nombre'], $_POST['apellido'], $_POST['contraseña'])) {
    
    // Sanitización de los datos recibidos
    $dni = $mysqli->real_escape_string($_POST['dni']);
    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $apellido = $mysqli->real_escape_string($_POST['apellido']);
    $contraseña = $mysqli->real_escape_string($_POST['contraseña']);

    // Verificar si el DNI ya existe
    $stmt = $mysqli->prepare("SELECT dni FROM usuarios WHERE dni = ? LIMIT 1");
    $stmt->bind_param('s', $dni);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // DNI ya existe
        echo json_encode(array("error" => "El DNI ya está registrado"));
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Encriptar la contraseña
    $contraseñaHash = password_hash($contraseña, PASSWORD_BCRYPT);

    // Insertar el nuevo tutor
    $stmt = $mysqli->prepare("INSERT INTO usuarios (dni, nombre, apellido, contraseña, nivel) VALUES (?, ?, ?, ?, 2)");
    $stmt->bind_param("ssss", $dni, $nombre, $apellido, $contraseñaHash);
    
    if ($stmt->execute()) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("error" => 'Error al registrar: ' . $stmt->error));
    }
    $stmt->close();
} else {
    // Respuesta JSON si faltan datos
    echo json_encode(array("error" => 'Faltan datos obligatorios!'));
}

$mysqli->close();
?>
