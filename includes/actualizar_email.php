<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include 'includes/MySQL.php'; // Conexión a la base de datos

$dni = $_POST['dni']; // Obtener el DNI del usuario desde la sesión
$email = $_POST['email']; // Obtener el email del formulario

// Verificar si ya existe un registro para ese DNI
$sql_check = "SELECT dni FROM tutordatos WHERE dni = '$dni'";
$result = $mysqli->query($sql_check);

if ($result->num_rows > 0) {
    // Si existe, actualizar el email
    $sql_update = "UPDATE tutordatos SET mail = ? WHERE dni = ?";
    $stmt = $mysqli->prepare($sql_update);
    $stmt->bind_param('ss', $email, $dni);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Email actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar el email.']);
    }
    $stmt->close();
} else {
    // Si no existe, insertar un nuevo registro
    $sql_insert = "INSERT INTO tutordatos (dni, mail) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql_insert);
    $stmt->bind_param('ss', $dni, $email);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Email actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar el email.']);
    }
    $stmt->close();
}

$mysqli->close();
?>
