<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
session_start();

if (!isset($_SESSION['dni'])) {
    die('Acceso no autorizado. Sesión no iniciada.');
}

include 'includes/Email.php';
include 'includes/MySQL.php';
require 'vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

$mensaje = '';

// Se reciben los parámetros GET para destinatario y nivel (en caso de ser "todos")
$destinatarioParam = $_GET['destinatario'] ?? '';
$nivelParam = $_GET['nivel'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $titulo = trim($_POST['notificacion-titulo'] ?? '');
    $descripcion = trim($_POST['notificacion-descripcion'] ?? '');
    $tipo = intval($_POST['tipo'] ?? 0);
    $directorioSubida = 'Comunicaciones/';
    $archivoSubido = '';

    if (empty($titulo) || empty($descripcion) || !$destinatarioParam || !$tipo) {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        $fecha = date('Y-m-d');
        $emisor = $_SESSION['dni'];

        // Procesar la carga del archivo (si se adjunta alguno)
        if (!empty($_FILES['archivo']['name'])) {
            $archivo = $_FILES['archivo'];
            $nombreArchivo = basename($archivo['name']);
            $rutaArchivo = $directorioSubida . $nombreArchivo;
            $tipoArchivo = strtolower(pathinfo($rutaArchivo, PATHINFO_EXTENSION));

            $tiposPermitidos = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
            if (in_array($tipoArchivo, $tiposPermitidos) && $archivo['size'] <= 5000000) {
                if (move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
                    $archivoSubido = $rutaArchivo;
                } else {
                    $mensaje = 'Error al subir el archivo.';
                }
            } else {
                $mensaje = 'Tipo de archivo no permitido o el tamaño es demasiado grande.';
            }
        }

        // Función para obtener el correo y los tokens de un usuario (buscado por DNI)
        function obtenerCorreoYTokensUsuario($dni, $mysqli) {
            $datos = [];
            $sql = "SELECT td.mail, tf.token 
                    FROM tutordatos td 
                    LEFT JOIN token_fcm tf ON td.dni = tf.dni 
                    WHERE td.dni = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('s', $dni);
            $stmt->execute();
            $resultado = $stmt->get_result();
            while ($fila = $resultado->fetch_assoc()) {
                if (!isset($datos['mail'])) {
                    $datos['mail'] = $fila['mail'];
                    $datos['tokens'] = [];
                }
                if (!empty($fila['token'])) {
                    $datos['tokens'][] = $fila['token'];
                }
            }
            $stmt->close();
            return $datos;
        }

        // Función para enviar notificación FCM
        function enviarNotificacionFCM($tokenDestino, $titulo, $descripcion, $accessToken) {
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
                'Authorization: Bearer ' . $accessToken
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        }

        // Obtener token de acceso para FCM mediante las credenciales de servicio
        $credential = new ServiceAccountCredentials(
            "https://www.googleapis.com/auth/firebase.messaging",
            json_decode(file_get_contents("pvkey.json"), true)
        );
        $tokenAuth = $credential->fetchAuthToken(HttpHandlerFactory::build());

        if ($destinatarioParam === 'todos') {
            // Se debe enviar la notificación a todos los usuarios del nivel solicitado
            if (empty($nivelParam)) {
                $mensaje = 'El parámetro nivel es obligatorio para enviar a todos.';
            } else {
                // Consulta para obtener todos los usuarios (tabla usuarios) que coincidan con el nivel,
                // junto con su email (tabla tutordatos) y token(s) (tabla token_fcm)
                $sql = "SELECT u.dni, td.mail, tf.token 
                        FROM usuarios u 
                        JOIN tutordatos td ON u.dni = td.dni 
                        LEFT JOIN token_fcm tf ON u.dni = tf.dni 
                        WHERE u.nivel = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('i', $nivelParam);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $usuarios = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $dni = $fila['dni'];
                    if (!isset($usuarios[$dni])) {
                        $usuarios[$dni] = [
                            'mail' => $fila['mail'],
                            'tokens' => []
                        ];
                    }
                    if (!empty($fila['token'])) {
                        $usuarios[$dni]['tokens'][] = $fila['token'];
                    }
                }
                $stmt->close();

                // Para cada usuario se inserta la notificación, se envía el correo y la notificación FCM
                foreach ($usuarios as $dni => $info) {
                    $insertSql = "INSERT INTO notificaciones (emisor, receptor, fecha, titulo, cuerpo, tipo, archivo) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmtInsert = $mysqli->prepare($insertSql);
                    $stmtInsert->bind_param('sssssis', $emisor, $dni, $fecha, $titulo, $descripcion, $tipo, $archivoSubido);
                    $stmtInsert->execute();
                    $stmtInsert->close();

                    if (!empty($info['mail'])) {
                        enviarCorreo([$info['mail']], $titulo, $descripcion, 'personal', $archivoSubido);
                    }
                    if (!empty($info['tokens'])) {
                        foreach ($info['tokens'] as $tokenFCM) {
                            enviarNotificacionFCM($tokenFCM, $titulo, $descripcion, $tokenAuth['access_token']);
                        }
                    }
                }
                $mensaje = "Notificación enviada a todos los usuarios de nivel $nivelParam.";
            }
        } else {
            // Se envía a un usuario específico (se recibe el DNI en el parámetro destinatario)
            $dniUsuario = $destinatarioParam;
            $insertSql = "INSERT INTO notificaciones (emisor, receptor, fecha, titulo, cuerpo, tipo, archivo) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtInsert = $mysqli->prepare($insertSql);
            $stmtInsert->bind_param('sssssis', $emisor, $dniUsuario, $fecha, $titulo, $descripcion, $tipo, $archivoSubido);
            $stmtInsert->execute();
            $stmtInsert->close();

            $datosUsuario = obtenerCorreoYTokensUsuario($dniUsuario, $mysqli);
            if (!empty($datosUsuario['mail'])) {
                enviarCorreo([$datosUsuario['mail']], $titulo, $descripcion, 'personal', $archivoSubido);
            }
            if (!empty($datosUsuario['tokens'])) {
                foreach ($datosUsuario['tokens'] as $tokenFCM) {
                    enviarNotificacionFCM($tokenFCM, $titulo, $descripcion, $tokenAuth['access_token']);
                }
            }
            $mensaje = "Notificación enviada al usuario con DNI $dniUsuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificaciones - Cuaderno de Comunicaciones Virtual</title>
    <style>
        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 1rem;
        }
        section {
            margin: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
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
        label,
        input,
        textarea {
            margin: 10px 0;
            padding: 10px;
        }
        input,
        textarea {
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
        .mensaje {
            text-align: center;
            margin-top: 20px;
            color: red;
        }
    </style>
    <script>
        function establecerTipo(tipo) {
            document.getElementById('tipo').value = tipo;
            document.getElementById('notificacion-form').submit();
        }
        function mostrarMensaje(mensaje) {
            Notiflix.Report.Success(
                '¡Notificación enviada!',
                mensaje,
                'Aceptar',
                function() {
                    window.location.href = 'index.php';
                }
            );
        }
    </script>
</head>
<body>
    <header>
        <h1>Notificaciones - Cuaderno de Comunicaciones Virtual</h1>
    </header>
    <section>
        <h2>Agregar Notificación</h2>
        <form id="notificacion-form" method="post" enctype="multipart/form-data">
            <input type="hidden" id="tipo" name="tipo" value="">
            <label for="notificacion-titulo">Título de la Notificación:</label>
            <input type="text" id="notificacion-titulo" name="notificacion-titulo" placeholder="Escribe el título de la notificación" required>
            <label for="notificacion-descripcion">Descripción:</label>
            <textarea id="notificacion-descripcion" name="notificacion-descripcion" rows="4" placeholder="Escribe la descripción de la notificación" required></textarea>
            <label for="archivo">Adjuntar Archivo:</label>
            <input type="file" id="archivo" name="archivo">
            <label>Selecciona el tipo de notificación:</label>
            <button type="button" onclick="establecerTipo(1)">Respuesta de Notificado</button>
            <br>
            <button type="button" onclick="establecerTipo(2)">Respuesta de Aceptación</button>
        </form>
        <?php if (!empty($mensaje)): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    mostrarMensaje("<?php echo htmlspecialchars($mensaje); ?>");
                });
            </script>
        <?php endif; ?>
    </section>
</body>
</html>
