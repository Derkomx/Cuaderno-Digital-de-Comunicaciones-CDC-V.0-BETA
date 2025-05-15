<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Archivo: cuaderno_comunicaciones.php

include 'includes/MySQL.php'; // Conexión a la base de datos

// Obtener valores desde GET y asegurarnos de que están definidos
$id_usuario = $_GET['alumno'] ?? '';
$curso = $_GET['anio'] ?? '';
$division = $_GET['division'] ?? '';

// Inicializar array de comunicados
$comunicados = [];

// Construir la consulta de comunicados con los nombres de los receptores
$sql = "SELECT n.id, n.fecha, n.titulo, n.cuerpo, n.receptor, 
               u_receptor.nombre AS receptor_nombre, u_receptor.apellido AS receptor_apellido,
               u.nombre AS emisor_nombre, u.apellido AS emisor_apellido, 
               n.estado, n.tipo, n.archivo, 
               u_notif.nombre AS notif_nombre, u_notif.apellido AS notif_apellido
        FROM notificaciones AS n
        JOIN usuarios AS u ON n.emisor = u.dni
        LEFT JOIN usuarios AS u_notif ON n.usuario = u_notif.dni
        LEFT JOIN usuarios AS u_receptor ON n.receptor = u_receptor.dni";

if ($id_usuario === "todos") {
    // Filtrar por curso y división si se selecciona "Ver de todos"
    $sql .= " WHERE n.receptor IN (SELECT dni FROM usuarios WHERE curso = ? AND division = ?)";
} else {
    // Filtrar por un alumno específico
    $sql .= " WHERE n.receptor = ?";
}

$sql .= " ORDER BY n.fecha DESC";

// Preparar la consulta
$stmt = $mysqli->prepare($sql);

// Asignar parámetros según el caso
if ($id_usuario === "todos") {
    $stmt->bind_param('ii', $curso, $division);
} else {
    $stmt->bind_param('s', $id_usuario);
}

// Ejecutar consulta
$stmt->execute();
$resultado = $stmt->get_result();
$comunicados = $resultado->fetch_all(MYSQLI_ASSOC);

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuaderno de Comunicaciones</title>
    <style>
        .comunicado {
            background-color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .estado-notificado { color: #4CAF50; }
        .estado-no-notificado { color: #2196F3; }
        .estado-acepto { color: #4CAF50; }
        .estado-no-acepto { color: #f44336; }
    </style>
</head>
<body>

<h1>Cuaderno de Comunicaciones</h1>

<?php if (!empty($comunicados)): ?>
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
    <?php foreach ($comunicados as $comunicado): ?>
        <div class="comunicado">
            <h2><?php echo htmlspecialchars($comunicado['titulo']); ?></h2>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($comunicado['fecha']); ?></p>
            <p><strong>Notificante:</strong> <?php echo htmlspecialchars($comunicado['emisor_apellido'] . ', ' . $comunicado['emisor_nombre']); ?></p>
            <p><strong>Alumno:</strong> <?php echo htmlspecialchars($comunicado['receptor_apellido'] . ', ' . $comunicado['receptor_nombre']); ?></p>
            <p><?php echo nl2br(htmlspecialchars($comunicado['cuerpo'])); ?></p>

            <!-- Mostrar enlace de archivo si existe -->
            <?php if (!empty($comunicado['archivo'])): ?>
                <?php
                $archivo = $comunicado['archivo'];
                $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
                ?>
                <p><strong>Archivo adjunto:</strong>
                    <?php if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <br>
                        <img src="<?php echo htmlspecialchars($archivo); ?>" alt="Imagen adjunta" style="max-width: 50%; height: auto;">
                    <?php else: ?>
                        <a href="<?php echo htmlspecialchars($archivo); ?>" target="_blank" download>Descargar archivo adjunto</a>
                    <?php endif; ?>
                </p>
            <?php endif; ?>

            <div class="estado">
                <?php if ($comunicado['tipo'] == 1): ?>
                    <?php if ($comunicado['estado'] == 0): ?>
                        <span class="estado-no-notificado">&#10008; No notificado</span>
                    <?php elseif ($comunicado['estado'] == 1): ?>
                        <span class="estado-notificado">&#10004; Notificado</span>
                        <p>Notificado por: <?php echo htmlspecialchars($comunicado['notif_apellido'] . ', ' . $comunicado['notif_nombre']); ?></p>
                    <?php endif; ?>
                <?php elseif ($comunicado['tipo'] == 2): ?>
                    <?php if ($comunicado['estado'] == 1): ?>
                        <span class="estado-acepto">&#10004; Aceptado</span>
                        <p>Aceptado por: <?php echo htmlspecialchars($comunicado['notif_apellido'] . ', ' . $comunicado['notif_nombre']); ?></p>
                    <?php elseif ($comunicado['estado'] == 2): ?>
                        <span class="estado-no-acepto">&#10008; No aceptado</span>
                        <p>No aceptado por: <?php echo htmlspecialchars($comunicado['notif_apellido'] . ', ' . $comunicado['notif_nombre']); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
    <p>No hay comunicados para mostrar.</p>
<?php endif; ?>
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/

</body>
</html>
