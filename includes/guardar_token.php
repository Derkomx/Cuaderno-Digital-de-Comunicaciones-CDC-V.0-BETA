<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include 'includes/MySQL.php'; // Asegúrate de ajustar el nombre según tu configuración de conexión a la base de datos

if (isset($_POST['token']) && isset($_POST['dni'])) {
    $dni = $_POST['dni'];
    $token = $_POST['token'];

    // Verificar si ya existe el token asociado al dni
    $stmt_check = $mysqli->prepare("SELECT COUNT(*) FROM token_fcm WHERE dni = ? AND token = ?");
    $stmt_check->bind_param("ss", $dni, $token);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    // Si ya existe el token para ese dni, no hacemos la inserción
    if ($count > 0) {
        echo json_encode(['success' => true, 'message' => 'El token ya está asociado a este DNI']);
    } else {
        // Si no existe, proceder con la inserción
        $stmt = $mysqli->prepare("INSERT INTO token_fcm (dni, token) VALUES (?, ?)");
        $stmt->bind_param("ss", $dni, $token);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo $stmt->error;
            echo json_encode(['success' => false]);
        }

        $stmt->close();
    }

    $mysqli->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Token o DNI no proporcionados']);
}
?>
