<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
ob_start();
include_once 'MySQL.php';
include_once 'Email.php';  // Incluye el archivo que contiene la función enviarCorreo

// Comprobación inicial si se envió el DNI por POST
if (isset($_POST['dni'])) {
    $dni = $_POST['dni'];

    // Primero, consulta para obtener el nivel de usuario en la tabla usuarios
    if ($stmt = $mysqli->prepare("SELECT nivel, contraseña FROM usuarios WHERE dni = ? LIMIT 1")) {
        $stmt->bind_param('s', $dni);
        $stmt->execute();
        $stmt->store_result();

        // Si se encuentra el usuario, obtener el nivel y la contraseña
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($nivel, $contraseña);
            $stmt->fetch();

            // Si el usuario es de nivel 3
            if ($nivel == 3) {
                // Buscar todos los DNIs asociados en la tabla tutor
                if ($stmt_tutor = $mysqli->prepare("SELECT dni FROM tutor WHERE dni_asoc = ?")) {
                    $stmt_tutor->bind_param('s', $dni);
                    $stmt_tutor->execute();
                    $result_tutor = $stmt_tutor->get_result();

                    $dni_asoc_list = [];
                    while ($row = $result_tutor->fetch_assoc()) {
                        $dni_asoc_list[] = $row['dni'];
                    }

                    if (!empty($dni_asoc_list)) {
                        // Obtener los correos de la tabla tutordatos usando los DNIs asociados
                        $correosTutores = [];
                        $stmt_tutorDatos = $mysqli->prepare("SELECT mail FROM tutordatos WHERE dni = ?");
                        
                        foreach ($dni_asoc_list as $dni_asoc) {
                            $stmt_tutorDatos->bind_param('s', $dni_asoc);
                            $stmt_tutorDatos->execute();
                            $result_tutorDatos = $stmt_tutorDatos->get_result();

                            while ($row = $result_tutorDatos->fetch_assoc()) {
                                $correosTutores[] = $row['mail'];
                            }
                        }
                        
                        if (!empty($correosTutores)) {
                            // No necesitas título ni descripción aquí, ya que el contenido se carga desde la plantilla
                            enviarCorreo($correosTutores, 'Recuperar Contraseña', '', 'recuperacion', $contraseña); // Pasar tipo 'recuperacion' y la contraseña
                            echo json_encode(array("success" => true, "emails" => $correosTutores));
                        } else {
                            echo json_encode(array("error" => "Correos no encontrados en tutordatos."));
                        }
                        
                    } else {
                        echo json_encode(array("error" => "DNI no encontrado en tutor."));
                    }
                }
            }
            // Si el usuario es de nivel 2
            elseif ($nivel == 2 || $nivel == 4 || $nivel == 5 || $nivel == 7) {
                // Obtener todos los correos asociados al usuario en la tabla tutordatos
                $correosTutores = [];
                if ($stmt_tutorDatos = $mysqli->prepare("SELECT mail FROM tutordatos WHERE dni = ?")) {
                    $stmt_tutorDatos->bind_param('s', $dni);
                    $stmt_tutorDatos->execute();
                    $result = $stmt_tutorDatos->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $correosTutores[] = $row['mail'];
                    }

                    if (!empty($correosTutores)) {
                        // No necesitas título ni descripción aquí, ya que el contenido se carga desde la plantilla
                        enviarCorreo($correosTutores, '', '', 'recuperacion', $contraseña); // Pasar tipo 'recuperacion' y la contraseña
                        echo json_encode(array("success" => true, "emails" => $correosTutores));
                    } else {
                        echo json_encode(array("error" => "Correo no encontrado. Contacte con un administrador."));
                    }
                    
                }
            }
            // Para otros niveles de usuario, redirigir
            else {
                header("Location: /pagina");
                exit();
            }
        } else {
            echo json_encode(array("error" => "Usuario no encontrado en la base de datos."));
        }
    }
}
// Verificación de recuperación de contraseña
elseif (isset($_POST['Recovery'])) {
    $recovery = $_POST['Recovery'];

    if ($stmt_recovery = $mysqli->prepare("SELECT dni FROM usuarios WHERE contraseña = ? LIMIT 1")) {
        $stmt_recovery->bind_param('s', $recovery);
        $stmt_recovery->execute();
        $stmt_recovery->store_result();

        if ($stmt_recovery->num_rows == 1) {
            echo json_encode(array("success" => true));
            $stmt_recovery->close();
        } else {
            echo json_encode(array("error" => "Código de recuperación no existe."));
            $stmt_recovery->close();
        }
    }
}
elseif (isset($_POST['tipo'])) {
    $clave = $_POST['Clave'];
    $hash = $_POST['hash'];
    // Generar nueva contraseña encriptada
    $nueva_contraseña = password_hash($clave, PASSWORD_BCRYPT);
    // Verificar si el recovery token existe en la base de datos
    if ($stmt_recovery = $mysqli->prepare("SELECT dni FROM usuarios WHERE contraseña = ? LIMIT 1")) {
        $stmt_recovery->bind_param('s', $hash);
        $stmt_recovery->execute();
        $stmt_recovery->bind_result($dni);
    
        // Verificar si se encontró el token de recuperación
        if ($stmt_recovery->fetch()) {
            $stmt_recovery->close();
            // Actualizar la contraseña en la base de datos
            if ($stmt_update = $mysqli->prepare("UPDATE usuarios SET contraseña = ? WHERE dni = ?")) {
                $stmt_update->bind_param('ss', $nueva_contraseña, $dni);
                if ($stmt_update->execute()) {
                    echo json_encode(array("success" => true));
                } else {
                    echo json_encode(array("error" => "Error al actualizar la contraseña."));
                }
                $stmt_update->close();
            }
        } else {
            echo json_encode(array("error" => " Token no encontrado."));
        }
    } else {
        echo json_encode(array("error" => "Error en la consulta de recuperación."));
    }
}
?>
