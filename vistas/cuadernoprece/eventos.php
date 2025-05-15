<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include 'includes/Email.php';
include 'includes/MySQL.php';
require 'vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = trim($_POST['evento-titulo'] ?? '');
    $descripcion = trim($_POST['evento-descripcion'] ?? '');
    $fechaEvento = $_POST['evento-fecha'] ?? '';

    if (empty($titulo) || empty($descripcion) || empty($fechaEvento)) {
        echo '<p style="color: red; text-align: center;">Todos los campos son obligatorios.</p>';
        exit;
    }

    // Insertar el evento en la tabla 'eventos'
    $stmt = $mysqli->prepare("INSERT INTO eventos (titulo, evento, fecha) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $titulo, $descripcion, $fechaEvento);
    
    if (!$stmt->execute()) {
        echo '<p style="color: red; text-align: center;">Error al insertar el evento: ' . $stmt->error . '</p>';
        $stmt->close();
        exit;
    }

    $stmt->close();

    // Verificar archivo de credenciales Firebase
    $firebaseKeyFile = "pvkey.json";
    if (!file_exists($firebaseKeyFile)) {
        echo '<p style="color: red; text-align: center;">Archivo de credenciales de Firebase no encontrado.</p>';
        exit;
    }

    try {
        $mysqli = new MySQL(); // Asumiendo que la clase MySQL maneja la conexión a la base de datos

        // Obtener credenciales para Firebase
        $credential = new ServiceAccountCredentials(
            "https://www.googleapis.com/auth/firebase.messaging",
            json_decode(file_get_contents($firebaseKeyFile), true)
        );
        $token = $credential->fetchAuthToken(HttpHandlerFactory::build());

        function enviarNotificacionFCM($tokenDestino, $titulo, $descripcion, $token) {
            $url = "https://fcm.googleapis.com/v1/projects/aplicacion-cet-3/messages:send";
            $data = [
                "message" => [
                    "token" => $tokenDestino,
                    "notification" => [
                        "title" => $titulo,
                        "body" => $descripcion,
                    ],
                    "webpush" => [
                        "fcm_options" => [
                            "link" => "https://cuaderno.cet3.ar"
                        ]
                    ]
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo '<p style="color: red; text-align: center;">Error al enviar notificación: ' . curl_error($ch) . '</p>';
            }
            curl_close($ch);

            return $response;
        }

        // Obtener todos los usuarios
        $sqlUsuarios = "SELECT dni FROM usuarios";
        $result = $mysqli->query($sqlUsuarios);

        if (!$result) {
            echo '<p style="color: red; text-align: center;">Error al obtener usuarios: ' . $mysqli->error . '</p>';
            exit;
        }

        while ($usuario = $result->fetch_assoc()) {
            $dni = $usuario['dni'];

            // Obtener correos asociados
            $stmtCorreos = $mysqli->prepare("SELECT td.mail FROM tutordatos td WHERE td.dni = ?");
            $stmtCorreos->bind_param('s', $dni);
            $stmtCorreos->execute();
            $resultCorreos = $stmtCorreos->get_result();

            while ($correo = $resultCorreos->fetch_assoc()) {
                if (!empty($correo['mail'])) {
                    enviarCorreo([$correo['mail']], $titulo, $descripcion, 'evento');
                }
            }
            $stmtCorreos->close();

            // Obtener tokens FCM asociados
            $stmtTokens = $mysqli->prepare("SELECT tf.token FROM token_fcm tf WHERE tf.dni = ?");
            $stmtTokens->bind_param('s', $dni);
            $stmtTokens->execute();
            $resultTokens = $stmtTokens->get_result();

            while ($tokenFCM = $resultTokens->fetch_assoc()) {
                if (!empty($tokenFCM['token'])) {
                    enviarNotificacionFCM($tokenFCM['token'], $titulo, $descripcion, $token['access_token']);
                }
            }
            $stmtTokens->close();
        }

        echo '<p style="color: green; text-align: center;">Evento agregado y notificaciones enviadas.</p>';
    } catch (Exception $e) {
        echo '<p style="color: red; text-align: center;">Ocurrió un error: ' . $e->getMessage() . '</p>';
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - Cuaderno de Comunicaciones</title>
    <style>
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            max-width: 500px;
            margin: 0 auto;
        }
        label, input, textarea {
            margin: 10px 0;
            padding: 10px;
        }
        input, textarea {
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<header>
    <center><h1>Eventos - Cuaderno de Comunicaciones Virtual</h1></center>
</header>

<section>
    <h2>Agregar Evento</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="evento-titulo">Título del Evento:</label>
        <input type="text" id="evento-titulo" name="evento-titulo" placeholder="Escribe el título del evento" required>

        <label for="evento-fecha">Fecha del Evento:</label>
        <input type="date" id="evento-fecha" name="evento-fecha" required>

        <label for="evento-descripcion">Descripción:</label>
        <textarea id="evento-descripcion" name="evento-descripcion" rows="4" placeholder="Escribe la descripción del evento" required></textarea>

        <button type="submit">Agregar Evento</button>
    </form>
</section>


</body>
</html>
