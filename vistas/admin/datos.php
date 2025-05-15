<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Incluir las dependencias necesarias
include 'includes/MySQL.php';
require 'vendor/autoload.php';

// Consultas SQL para estadísticas

// 1. Usuarios por nivel con correo registrado y sin correo
$sqlUsuariosCorreo = "SELECT u.nivel, 
    COUNT(CASE WHEN td.mail IS NOT NULL THEN 1 END) AS con_mail, 
    COUNT(CASE WHEN td.mail IS NULL THEN 1 END) AS sin_mail
FROM usuarios u
LEFT JOIN tutordatos td ON u.dni = td.dni
WHERE u.nivel IN (2, 5, 7)
GROUP BY u.nivel";

// 2. Usuarios por nivel con tokens registrados
$sqlUsuariosToken = "SELECT u.nivel, 
    COUNT(CASE WHEN tf.token IS NOT NULL THEN 1 END) AS con_token, 
    COUNT(CASE WHEN tf.token IS NULL THEN 1 END) AS sin_token
FROM usuarios u
LEFT JOIN token_fcm tf ON u.dni = tf.dni
WHERE u.nivel IN (2, 5, 7)
GROUP BY u.nivel";

// 3. Notificaciones enviadas y respuestas
$sqlNotificaciones = "SELECT 
    u.curso, 
    u.division, 
    COUNT(*) AS total_enviadas,
    COUNT(CASE WHEN n.estado IS NOT NULL THEN 1 END) AS respondidas,
    COUNT(CASE WHEN n.estado IS NULL THEN 1 END) AS no_respondidas
FROM notificaciones n
LEFT JOIN usuarios u ON n.receptor = u.dni
GROUP BY u.curso, u.division";

// 4. Comensales promedio por día y hora
$sqlComensales = "SELECT 
    i.dia, 
    i.hora, 
    ROUND(COUNT(*) / COUNT(DISTINCT i.fecha), 2) AS promedio
FROM inscripciones i
WHERE i.fecha IS NOT NULL
GROUP BY i.dia, i.hora
ORDER BY FIELD(i.dia, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'), i.hora;";

// Ejecución de las consultas y manejo de datos
$usuariosCorreo = $mysqli->query($sqlUsuariosCorreo)->fetch_all(MYSQLI_ASSOC);
$usuariosToken = $mysqli->query($sqlUsuariosToken)->fetch_all(MYSQLI_ASSOC);
$notificaciones = $mysqli->query($sqlNotificaciones)->fetch_all(MYSQLI_ASSOC);
$comensales = $mysqli->query($sqlComensales)->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .section {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh; /* Una "pantalla completa" */
            padding: 20px;
            box-sizing: border-box;
            gap: 20px;
        }
        .section-title {
            font-size: 2em;
            margin-bottom: 10px;
            text-align: center;
        }
        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        canvas {
            width: 100% !important;
            height: auto !important;
        }
    </style>
</head>
<body>
    <!-- Sección Comedor -->
    <div class="section" id="comedor">
        <h1 class="section-title">Comedor</h1>
        <div class="box">
            <h2>Comensales Promedio por Día y Hora</h2>
            <canvas id="comensalesChart"></canvas>
        </div>
    </div>

    <!-- Sección Notificaciones -->
    <div class="section" id="notificaciones">
        <h1 class="section-title">Notificaciones</h1>
        <div class="box">
            <h2>Notificaciones Enviadas y Respondidas</h2>
            <canvas id="notificacionesChart"></canvas>
        </div>
        <br>
        <div class="box">
            <h2>Usuarios con/sin Correo asociado</h2>
            <canvas id="usuariosCorreoChart"></canvas>
        </div>
        <br>
        <div class="box">
            <h2>Usuarios con/sin Token asociado</h2>
            <canvas id="usuariosTokenChart"></canvas>
        </div>
    </div>

    <script>
        // Comensales
        const comensalesData = <?php echo json_encode($comensales); ?>;
        const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        const horas = ['12:00:00', '12:30:00'];

        const promedios12 = dias.map(dia => {
            const entry = comensalesData.find(item => item.dia === dia && item.hora === '12:00:00');
            return entry ? entry.promedio : 0;
        });

        const promedios1230 = dias.map(dia => {
            const entry = comensalesData.find(item => item.dia === dia && item.hora === '12:30:00');
            return entry ? entry.promedio : 0;
        });

        new Chart(document.getElementById('comensalesChart'), {
            type: 'bar',
            data: {
                labels: dias,
                datasets: [
                    {
                        label: '12:00 hs',
                        data: promedios12,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    },
                    {
                        label: '12:30 hs',
                        data: promedios1230,
                        backgroundColor: 'rgba(255, 206, 86, 0.6)'
                    }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } } }
        });

        // Usuarios Correo
        const usuariosCorreoData = <?php echo json_encode($usuariosCorreo); ?>;
        const nivelesCorreo = usuariosCorreoData.map(item => item.nivel == 2 ? 'Tutor' : item.nivel == 5 ? 'Profesor' : 'Preceptor');
        const conMail = usuariosCorreoData.map(item => item.con_mail);
        const sinMail = usuariosCorreoData.map(item => item.sin_mail);

        new Chart(document.getElementById('usuariosCorreoChart'), {
            type: 'bar',
            data: {
                labels: nivelesCorreo,
                datasets: [
                    { label: 'Con Correo', data: conMail, backgroundColor: 'rgba(75, 192, 192, 0.6)' },
                    { label: 'Sin Correo', data: sinMail, backgroundColor: 'rgba(255, 99, 132, 0.6)' }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } } }
        });

        // Usuarios Token
        const usuariosTokenData = <?php echo json_encode($usuariosToken); ?>;
        const nivelesToken = usuariosTokenData.map(item => item.nivel == 2 ? 'Tutor' : item.nivel == 5 ? 'Profesor' : 'Preceptor');
        const conToken = usuariosTokenData.map(item => item.con_token);
        const sinToken = usuariosTokenData.map(item => item.sin_token);

        new Chart(document.getElementById('usuariosTokenChart'), {
            type: 'bar',
            data: {
                labels: nivelesToken,
                datasets: [
                    { label: 'Con Token', data: conToken, backgroundColor: 'rgba(75, 192, 192, 0.6)' },
                    { label: 'Sin Token', data: sinToken, backgroundColor: 'rgba(255, 99, 132, 0.6)' }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } } }
        });

        // Notificaciones
        const notificacionesData = <?php echo json_encode($notificaciones); ?>;
        const cursos = notificacionesData.map(item => `Curso ${item.curso} División ${item.division}`);
        const enviadas = notificacionesData.map(item => item.total_enviadas);
        const respondidas = notificacionesData.map(item => item.respondidas);
        const noRespondidas = notificacionesData.map(item => item.no_respondidas);

        new Chart(document.getElementById('notificacionesChart'), {
            type: 'bar',
            data: {
                labels: cursos,
                datasets: [
                    { label: 'Enviadas', data: enviadas, backgroundColor: 'rgba(54, 162, 235, 0.6)' },
                    { label: 'Respondidas', data: respondidas, backgroundColor: 'rgba(75, 192, 192, 0.6)' },
                    { label: 'No Respondidas', data: noRespondidas, backgroundColor: 'rgba(255, 99, 132, 0.6)' }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } } }
        });
    </script>
</body>
</html>


