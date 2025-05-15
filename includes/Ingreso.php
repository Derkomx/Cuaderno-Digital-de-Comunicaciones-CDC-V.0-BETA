<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
    session_start();
    include_once 'MySQL.php';

    // Función para iniciar sesión
    function login($dni, $password, $mysqli) {
        // Preparar la consulta SQL
        if ($stmt = $mysqli->prepare("SELECT dni, contraseña, nivel, chat FROM usuarios WHERE dni = ? LIMIT 1")) {
            $stmt->bind_param('s', $dni); // 's' indica que el parámetro es una cadena
            $stmt->execute();              // Ejecutar la consulta
            $stmt->store_result();         // Almacenar el resultado

            // Si el usuario existe
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($dni_db, $hashed_password, $nivel, $chat); // Recuperar el nivel también
                $stmt->fetch();

                // Verificar la contraseña utilizando password_verify
                if (password_verify($password, $hashed_password)) {
                    // Almacena la sesión del usuario
                    $_SESSION['nivel'] = $nivel; // Almacena el nivel del usuario
                    $_SESSION['dni'] = $dni;
                    $_SESSION['chat'] = $chat;
                    return true;
                } else {
                    return false; // Contraseña incorrecta
                }
            } else {
                return false; // Usuario no encontrado
            }
        }
        return false; // Si algo sale mal
    }

    if (isset($_POST['dni'], $_POST['clave'])) {
        $dni = $_POST['dni'];
        $password = $_POST['clave'];

        // Intenta iniciar sesión
        if (login($dni, $password, $mysqli) == true) {
            // Si inicia sesión correctamente
            echo json_encode(array("location" => "deberia funcionar"));
            exit();
        } else {
            // Si no puede iniciar sesión, los datos son incorrectos
            echo json_encode(array("error" => "Usuario o Contraseña incorrectos"));
            exit();
        }
    } else {
        // Si no se procesa correctamente el ingreso
        echo json_encode(array("error" => "¡Ocurrió un error inesperado!"));
        exit();
    }
?>
