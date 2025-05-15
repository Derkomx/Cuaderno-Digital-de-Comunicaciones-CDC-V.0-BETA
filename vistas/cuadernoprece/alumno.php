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
$errores = [];

function obtenerCorreosYTokensTutores($dniAlumno, $mysqli) {
    $tutores = [];
    $sqlTutores = "SELECT td.mail, tf.token 
                   FROM tutor t 
                   LEFT JOIN tutordatos td ON t.dni = td.dni 
                   LEFT JOIN token_fcm tf ON t.dni = tf.dni 
                   WHERE t.dni_asoc = ?";
    $stmtTutores = $mysqli->prepare($sqlTutores);
    $stmtTutores->bind_param('s', $dniAlumno);
    $stmtTutores->execute();
    $resultado = $stmtTutores->get_result();

    while ($fila = $resultado->fetch_assoc()) {
        if (!array_key_exists($fila['mail'], $tutores)) {
            $tutores[$fila['mail']] = ['tokens' => []];
        }
        if (!empty($fila['token'])) {
            $tutores[$fila['mail']]['tokens'][] = $fila['token'];
        }
    }
    $stmtTutores->close();
    return $tutores;
}

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
    curl_close($ch);
    return $response;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['notificacion-titulo'] ?? '');
    $descripcion = trim($_POST['notificacion-descripcion'] ?? '');
    $curso = intval($_GET['anio'] ?? 0);
    $division = intval($_GET['division'] ?? 0);
    $receptor = $_GET['alumno'] ?? '';
    $tipo = intval($_POST['tipo'] ?? 0);
    $directorioSubida = 'Comunicaciones/';
    $archivoSubido = '';

    // Validación básica
    if (empty($titulo) || empty($descripcion) || !$curso || !$division || !$receptor || !$tipo) {
        $errores[] = 'Todos los campos son obligatorios';
    } else {
        // Procesar archivo primero
        if (!empty($_FILES['archivo']['name'])) {
            $archivo = $_FILES['archivo'];
            $nombreArchivo = basename($archivo['name']);
            $rutaArchivo = $directorioSubida . $nombreArchivo;
            $tipoArchivo = strtolower(pathinfo($rutaArchivo, PATHINFO_EXTENSION));

            $tiposPermitidos = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
            if (in_array($tipoArchivo, $tiposPermitidos) && $archivo['size'] <= 5000000) {
                if (!move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
                    $errores[] = 'Error al subir el archivo';
                } else {
                    $archivoSubido = $rutaArchivo;
                }
            } else {
                $errores[] = 'Archivo no válido (max 5MB, formatos permitidos: jpg, png, pdf, docx)';
            }
        }

        // Obtener token FCM
        $accessToken = null;
        try {
            $credential = new ServiceAccountCredentials(
                "https://www.googleapis.com/auth/firebase.messaging",
                json_decode(file_get_contents("pvkey.json"), true)
            );
            $token_data = $credential->fetchAuthToken(HttpHandlerFactory::build());
            $accessToken = $token_data['access_token'];
        } catch (Exception $e) {
            $errores[] = "Error en servicio de notificaciones push";
        }

        // Proceso principal sin errores críticos
        if (empty($errores)) {
            $fecha = date('Y-m-d');
            $emisor = $_SESSION['dni'];

            if ($receptor === 'todos') {
                // Obtener todos los alumnos del curso
                $stmt = $mysqli->prepare("SELECT dni FROM usuarios WHERE curso = ? AND division = ?");
                $stmt->bind_param('ii', $curso, $division);
                $stmt->execute();
                $alumnos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();

                // Procesar cada alumno individualmente
                foreach ($alumnos as $alumno) {
                    $dniAlumno = $alumno['dni'];
                    
                    // Guardar en base de datos primero
                    $stmtInsert = $mysqli->prepare("INSERT INTO notificaciones 
                        (emisor, receptor, fecha, titulo, cuerpo, tipo, archivo) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmtInsert->bind_param('sssssis', 
                        $emisor, 
                        $dniAlumno, 
                        $fecha, 
                        $titulo, 
                        $descripcion, 
                        $tipo, 
                        $archivoSubido
                    );
                    $stmtInsert->execute();
                    $stmtInsert->close();

                    // Obtener tutores del alumno
                    $tutores = obtenerCorreosYTokensTutores($dniAlumno, $mysqli);

                    // Enviar notificaciones
                    foreach ($tutores as $mail => $info) {
                        // Envío de correos electrónicos
                        if (!empty($mail)) {
                            try {
                                enviarCorreo([$mail], $titulo, $descripcion, 'nota', $archivoSubido);
                            } catch (Exception $e) {
                                $errores[] = "Error enviando a: $mail";
                            }
                        }

                        // Envío de notificaciones push
                        if (!empty($info['tokens']) && $accessToken) {
                            foreach ($info['tokens'] as $tokenFCM) {
                                try {
                                    enviarNotificacionFCM(
                                        $tokenFCM, 
                                        $titulo, 
                                        $descripcion, 
                                        $accessToken
                                    );
                                } catch (Exception $e) {
                                    // Error específico no registrado
                                }
                            }
                        }
                    }
                }

                // Mensaje final
                if (empty($errores)) {
                    $mensaje = "Notificaciones enviadas a todos los tutores";
                } else {
                    $erroresUnicos = array_unique($errores);
                    $mensaje = "Notificaciones guardadas. Errores:\\n" . implode("\\n", $erroresUnicos);
                }
            } else {
                // Envío individual
                $stmtInsert = $mysqli->prepare("INSERT INTO notificaciones 
                    (emisor, receptor, fecha, titulo, cuerpo, tipo, archivo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmtInsert->bind_param('sssssis', 
                    $emisor, 
                    $receptor, 
                    $fecha, 
                    $titulo, 
                    $descripcion, 
                    $tipo, 
                    $archivoSubido
                );
                $stmtInsert->execute();
                $stmtInsert->close();

                $tutores = obtenerCorreosYTokensTutores($receptor, $mysqli);
                $erroresIndividuales = [];

                foreach ($tutores as $mail => $info) {
                    if (!empty($mail)) {
                        try {
                            enviarCorreo([$mail], $titulo, $descripcion, 'nota', $archivoSubido);
                        } catch (Exception $e) {
                            $erroresIndividuales[] = $mail;
                        }
                    }

                    if (!empty($info['tokens']) && $accessToken) {
                        foreach ($info['tokens'] as $tokenFCM) {
                            try {
                                enviarNotificacionFCM($tokenFCM, $titulo, $descripcion, $accessToken);
                            } catch (Exception $e) {
                                // Error no registrado
                            }
                        }
                    }
                }

                if (empty($erroresIndividuales)) {
                    $mensaje = 'Notificación enviada correctamente';
                } else {
                    $mensaje = 'Notificación guardada. Fallos en: ' . implode(', ', $erroresIndividuales);
                }
            }
        }
    }

    // Preparar respuesta final
    if (!empty($errores)) {
        $mensaje = implode("\\n", array_unique($errores));
        echo "<script>
            Notiflix.Report.Failure(
                'Error',
                `$mensaje`,
                'Aceptar'
            );
        </script>";
    } else {
        echo "<script>
            Notiflix.Report.Success(
                'Éxito',
                `$mensaje`,
                'Aceptar',
                function() {
                    window.location.href = 'index.php';
                }
            );
        </script>";
    }
    exit;
}
?>

<!-- Mantener el HTML original -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificaciones - Cuaderno de Comunicaciones Virtual</title>
    <!-- Estilos y scripts se mantienen igual -->
</head>
<body>
    <!-- Mantener la estructura HTML original -->
</body>
</html>

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
                            '¡Nota enviada!',
                            'La nota fue enviada con exito!',
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
            <input type="text" id="notificacion-titulo" name="notificacion-titulo"
                placeholder="Escribe el título de la notificación" required>

            <label for="notificacion-descripcion">Descripción:</label>
            <textarea id="notificacion-descripcion" name="notificacion-descripcion" rows="4"
                placeholder="Escribe la descripción de la notificación" required></textarea>

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
