<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

function enviarCorreo($correosTutores, $titulo, $descripcion, $tipo, $contraseña = null) {
    ob_start();

    // Selección de plantilla
    if ($tipo === 'nota') {
        include('Emails/Email_Nota.php');
    } elseif ($tipo === 'recuperacion') {
        include('Emails/Email_Recuperacion.php');
    } elseif ($tipo === 'personal') {
        include('Emails/Email_NotaPersonal.php');
    }

    $Contenido = ob_get_clean();

    if ($tipo === 'recuperacion' && $contraseña !== null) {
        $Contenido = str_replace('{CONTRASEÑA}', $contraseña, $Contenido);
        $titulo = 'Recuperar Contraseña';
    }

    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = '';
        $mail->SMTPAuth = true;
        $mail->Username = '';
        $mail->Password = ''; // Se recomienda usar variables de entorno
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Optimización
        $mail->SMTPKeepAlive = true; // Mantiene la conexión abierta
        $mail->Timeout = 30; // Tiempo de espera extendido

        $mail->setFrom('', 'aca colocar institucion');
        $mail->isHTML(true);
        $mail->Subject = $titulo;
        $mail->Body = $Contenido ?: 'Contenido no disponible';
        $mail->CharSet = "UTF-8";

        // Enviar correos individualmente para evitar fallos en un destinatario
        foreach ($correosTutores as $correo) {
            $mail->clearAddresses(); // Limpia destinatarios previos
            $mail->addAddress($correo);

            if (!$mail->send()) {
                error_log("Error al enviar correo a $correo: " . $mail->ErrorInfo);
            }
        }

        // Cierra la conexión SMTP
        $mail->smtpClose();
    } catch (Exception $e) {
        error_log("Error de correo. Mailer Error: {$mail->ErrorInfo}");
    }
}
