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
include 'includes/MySQL.php';
require 'vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

$response = ["success" => false, "message" => ""];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['probar_notificacion'])) {
    $dni = $_SESSION['dni'] ?? '';
    $titulo = trim($_POST['notificaciontitulo'] ?? '');

    if (empty($dni) || empty($titulo)) {
        $response["message"] = "Datos incompletos.";
        echo json_encode($response);
        exit;
    }

    function enviarNotificacionFCM($tokenDestino, $token) {
        $url = "https://fcm.googleapis.com/v1/projects/aplicacion-cet-3/messages:send";
        $data = [
            "message" => [
                "token" => $tokenDestino,
                "notification" => [
                    "title" => "Notificación",
                    "body" => "Esta es una notificación de prueba",
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
        curl_close($ch);

        return $response;
    }

    // Obtener token de autenticación de Firebase
    try {
        $credential = new ServiceAccountCredentials(
            "https://www.googleapis.com/auth/firebase.messaging",
            json_decode(file_get_contents("pvkey.json"), true)
        );
        $token = $credential->fetchAuthToken(HttpHandlerFactory::build());

        if (!isset($token['access_token'])) {
            throw new Exception("No se pudo obtener el token de autenticación.");
        }

        // Obtener el token FCM del usuario actual
        $sql = "SELECT token FROM token_fcm WHERE dni = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $dni);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $enviado = false;
        while ($fila = $resultado->fetch_assoc()) {
            $res = enviarNotificacionFCM($fila['token'], $token['access_token']);
            $resData = json_decode($res, true);
            if (isset($resData['name'])) {
                $enviado = true;
            }
        }
        $stmt->close();

        if ($enviado) {
            $response["success"] = true;
            $response["message"] = "Notificación enviada con éxito.";
        } else {
            $response["message"] = "No se pudieron enviar las notificaciones.";
        }
    } catch (Exception $e) {
        $response["message"] = "Error: " . $e->getMessage();
    }
}

echo json_encode($response);
?>
