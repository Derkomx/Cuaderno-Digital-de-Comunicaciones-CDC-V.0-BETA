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

// Función para obtener correos y tokens FCM de los tutores asociados a un alumno
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

// Procesamiento del formulario de asistencia
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curso    = intval($_GET['anio'] ?? 0);
    $division = intval($_GET['division'] ?? 0);
    $fecha    = date('Y-m-d');
    $turno    = $_GET['turno'] ?? '';

    if (!in_array($turno, ['clase', 'taller'])) {
        echo "<script>Notiflix.Report.Failure('Error', 'Turno inválido.', 'Aceptar');</script>";
        exit;
    }

    // Verificar registro existente
    $sqlCheck = "SELECT COUNT(*) as total FROM asistencia WHERE curso = ? AND division = ? AND fecha = ? AND turno = ?";
    $stmtCheck = $mysqli->prepare($sqlCheck);
    $stmtCheck->bind_param('iiss', $curso, $division, $fecha, $turno);
    $stmtCheck->execute();
    $resultadoCheck = $stmtCheck->get_result();
    $filaCheck = $resultadoCheck->fetch_assoc();
    $stmtCheck->close();
    
    if ($filaCheck['total'] > 0) {
        echo "<script>Notiflix.Report.Failure('Error', 'La asistencia para esta fecha ya ha sido registrada.', 'Aceptar');</script>";
        exit;
    }

    // Obtener alumnos
    $sql = "SELECT dni, apellido, nombre FROM usuarios WHERE curso = ? AND division = ? ORDER BY apellido ASC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ii', $curso, $division);
    $stmt->execute();
    $alumnos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Registrar asistencias primero
    $presentes = [];
    foreach ($alumnos as $alumno) {
        $dni = $alumno['dni'];
        $presente = isset($_POST['asistencia'][$dni]) ? 1 : 0;
        $presentes[$dni] = $presente;

        $stmtInsert = $mysqli->prepare("INSERT INTO asistencia (dni, curso, division, fecha, turno, presente) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param('siissi', $dni, $curso, $division, $fecha, $turno, $presente);
        $stmtInsert->execute();
        $stmtInsert->close();
    }

    $errores = [];
    $accessToken = null;

    // Obtener token FCM
    try {
        $credential = new ServiceAccountCredentials(
            "https://www.googleapis.com/auth/firebase.messaging",
            json_decode(file_get_contents("pvkey.json"), true)
        );
        $accessToken = $credential->fetchAuthToken(HttpHandlerFactory::build())['access_token'];
    } catch (Exception $e) {
        $errores[] = "Error al conectar con el servicio de notificaciones";
    }

    // Procesar notificaciones
    foreach ($alumnos as $alumno) {
        if ($presentes[$alumno['dni']] === 0) {
            $tutores = obtenerCorreosYTokensTutores($alumno['dni'], $mysqli);
            $mensaje = "El estudiante {$alumno['apellido']}, {$alumno['nombre']} no asistió " . ($turno == 'taller' ? 'al taller' : 'a clase');

            foreach ($tutores as $mail => $datos) {
                // Envío de correos
                if (!empty($mail)) {
                    try {
                        enviarCorreo([$mail], 'Asistencia del estudiante', $mensaje, 'nota', '');
                    } catch (Exception $e) {
                        $errores[] = "Fallo al enviar a: $mail";
                    }
                }

                // Envío de notificaciones push
                if (!empty($datos['tokens']) && $accessToken) {
                    foreach ($datos['tokens'] as $token) {
                        try {
                            enviarNotificacionFCM($token, 'Falta de asistencia', $mensaje, $accessToken);
                        } catch (Exception $e) {
                            // Error específico no registrado
                        }
                    }
                }
            }
        }
    }

    // Mostrar resultados
    if (!empty($errores)) {
        $listaErrores = implode("\\n", array_unique($errores));
        echo "<script>
            Notiflix.Report.Warning(
                'Aviso',
                `Asistencia guardada correctamente.\\n\\nErrores de notificación:\\n${listaErrores}`,
                'Aceptar'
            );
        </script>";
    } else {
        echo "<script>
            Notiflix.Report.Success(
                'Éxito',
                'Asistencia y notificaciones registradas correctamente',
                'Aceptar'
            );
        </script>";
    }
    exit;
}

// Carga inicial del formulario
$curso = intval($_GET['anio'] ?? 1);
$division = intval($_GET['division'] ?? 1);
$turno = $_GET['turno'] ?? '';

$stmt = $mysqli->prepare("SELECT dni, apellido, nombre FROM usuarios WHERE curso = ? AND division = ? ORDER BY apellido ASC");
$stmt->bind_param('ii', $curso, $division);
$stmt->execute();
$alumnos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Asistencia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3/dist/notiflix-3.2.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
        }
        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 1rem;
        }
        section {
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            max-width: 800px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        tr:hover { background-color: #f2f2f2; cursor: pointer; }
        .mensaje {
            padding: 10px; background: #4CAF50; color: white; margin-top: 10px;
            text-align: center; display: <?php echo ($mensaje != '') ? 'block' : 'none'; ?>;
        }
        button {
            padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Registro de Asistencia</h1>
        <p><?php echo "Curso: " . $curso . "º, División: " . $division . ", Turno: " . ucfirst($turno); ?></p>
    </header>

    <form method="post">
 <table>
                <tr><th>Apellido</th><th>Nombre</th><th>Presente</th></tr>
                <?php foreach ($alumnos as $alumno): ?>
                <tr onclick="toggleCheckbox(this)">
                    <td><?php echo htmlspecialchars($alumno['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($alumno['nombre']); ?></td>
                    <td>
                        <input type="checkbox" name="asistencia[<?php echo $alumno['dni']; ?>]">
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <button type="submit">Guardar Asistencia</button>
    </form>
    <br>

    <script src="https://cdn.jsdelivr.net/npm/notiflix@3/dist/notiflix-aio-3.2.min.js"></script>
    <script>
        Notiflix.Notify.init({ position: 'right-top' });
        
        function toggleCheckbox(row) {
            const checkbox = row.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
        }
    </script>
</body>
</html>
