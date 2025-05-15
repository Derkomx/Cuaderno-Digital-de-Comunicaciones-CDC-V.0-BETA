<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Archivo: cuaderno_comunicaciones.php

include 'includes/MySQL.php'; // Conexión a la base de datos

// Verificar si es una solicitud AJAX para actualizar el estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['estado'])) {
    $id = $_POST['id'];
    $estado = $_POST['estado'];
    $usuario = $_SESSION['dni']; // DNI del usuario en sesión

    // Actualizar el estado de la notificación y guardar el usuario que actualiza
    $sql = "UPDATE notificaciones SET estado = ?, usuario = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("isi", $estado, $usuario, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Éxito";
    } else {
        echo "Error al actualizar";
    }

    $stmt->close();
    $mysqli->close();
    exit;
}

// Obtener datos de sesión y otras variables
$id_usuario = $_SESSION['dni'];
$nivel = $_SESSION['nivel'];
$fecha_actual = date("Y-m-d");

$comunicados = [];

// Para usuarios de nivel Tutor (y otros niveles que manejan varios alumnos)
if ($nivel == 2 || $nivel == 4 || $nivel == 5 || $nivel == 7) {
    // Buscar los usuarios asociados en la tabla tutor
    $sql_tutor = "SELECT dni_asoc FROM tutor WHERE dni = '$id_usuario'";
    $resultado_tutor = $mysqli->query($sql_tutor);

    if ($resultado_tutor->num_rows > 0) {
        $usuarios_asociados = [];
        while ($fila = $resultado_tutor->fetch_assoc()) {
            $usuarios_asociados[] = $fila['dni_asoc'];
        }

        // Crear la lista de DNIs separados por comas
        $usuarios_asociados_str = implode(',', array_map('intval', $usuarios_asociados));

        // Obtener los nombres y apellidos de los estudiantes asociados (opcional para mostrar)
        $sql_estudiantes = "SELECT dni, nombre, apellido FROM usuarios WHERE dni IN ($usuarios_asociados_str)";
        $resultado_estudiantes = $mysqli->query($sql_estudiantes);

        $estudiantes = [];
        if ($resultado_estudiantes->num_rows > 0) {
            while ($fila = $resultado_estudiantes->fetch_assoc()) {
                $estudiantes[$fila['dni']] = $fila['apellido'] . ', ' . $fila['nombre'];
            }
        }

        // Consulta principal para tutores: se incluye LEFT JOIN para traer el usuario que realizó la acción
        $sql = "SELECT n.id, n.fecha, n.titulo, n.cuerpo, n.receptor, n.usuario,
                       u.nombre AS emisor_nombre, u.apellido AS emisor_apellido,
                       n.estado, n.tipo, n.archivo,
                       u_notif.nombre AS notif_nombre, u_notif.apellido AS notif_apellido
                FROM notificaciones AS n
                JOIN usuarios AS u ON n.emisor = u.dni
                LEFT JOIN usuarios AS u_notif ON n.usuario = u_notif.dni
                WHERE n.receptor IN ($usuarios_asociados_str)
                ORDER BY n.estado = 0 ASC, n.fecha DESC";
    }
} else {
    // Para usuario normal: consulta con LEFT JOIN para obtener el usuario que realizó la acción
    $sql = "SELECT n.id, n.fecha, n.titulo, n.cuerpo, n.receptor, n.usuario,
                   u.nombre AS emisor_nombre, u.apellido AS emisor_apellido,
                   n.estado, n.tipo, n.archivo,
                   u_notif.nombre AS notif_nombre, u_notif.apellido AS notif_apellido
            FROM notificaciones AS n
            JOIN usuarios AS u ON n.emisor = u.dni
            LEFT JOIN usuarios AS u_notif ON n.usuario = u_notif.dni
            WHERE n.receptor = '$id_usuario'
            ORDER BY n.estado = 0 ASC, n.fecha DESC";
}

$resultado = $mysqli->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $comunicados[] = $fila;
    }
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuaderno de Comunicaciones Digital</title>
    <style>
        .comunicado {
            background-color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .botones, .estado {
            margin-top: 10px;
            font-weight: bold;
        }
        button {
            margin-right: 10px;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .informado, .acepto, .no-acepto {
            color: white;
        }
        .informado { background-color: #4CAF50; }
        .acepto { background-color: #2196F3; }
        .no-acepto { background-color: #f44336; }
        .estado-notificado { color: #4CAF50; }
        .estado-acepto { color: #2196F3; }
        .estado-no-acepto { color: #f44336; }
    </style>
    <script>
        function actualizarEstado(idNotificacion, estado) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php?Seccion=cuaderno2", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    location.reload();
                }
            };
            xhr.send("id=" + idNotificacion + "&estado=" + estado);
        }
    </script>
</head>
<body>

<h1>Cuaderno de Comunicaciones</h1>

<!-- Se muestra el emisor (notificante) y, en el estado, se muestra el usuario que realizó la acción -->
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
            <?php if ($nivel == 2 || $nivel == 4 || $nivel == 5 || $nivel == 7): ?>
                <?php if (isset($estudiantes[$comunicado['receptor']])): ?>
                    <p><strong>Estudiante:</strong> <?php echo htmlspecialchars($estudiantes[$comunicado['receptor']]); ?></p>
                <?php endif; ?>
            <?php endif; ?>
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
                <?php if ($comunicado['estado'] == 0 && $nivel != 3): ?>
                    <div class="botones">
                        <?php if ($comunicado['tipo'] == 1): ?>
                            <button class="informado" onclick="actualizarEstado(<?php echo $comunicado['id']; ?>, 1)">Notificado</button>
                        <?php elseif ($comunicado['tipo'] == 2): ?>
                            <button class="acepto" onclick="actualizarEstado(<?php echo $comunicado['id']; ?>, 1)">Acepto</button>
                            <button class="no-acepto" onclick="actualizarEstado(<?php echo $comunicado['id']; ?>, 2)">No acepto</button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p>
                        <?php if ($comunicado['estado'] == 1): ?>
                            <?php if ($comunicado['tipo'] == 1): ?>
                                <span class="estado-notificado">&#10004; Notificado por: <?php echo htmlspecialchars($comunicado['notif_apellido'] . ', ' . $comunicado['notif_nombre']); ?></span>
                            <?php else: ?>
                                <span class="estado-acepto">&#10004; Aceptado por: <?php echo htmlspecialchars($comunicado['notif_apellido'] . ', ' . $comunicado['notif_nombre']); ?></span>
                            <?php endif; ?>
                        <?php elseif ($comunicado['estado'] == 2): ?>
                            <span class="estado-no-acepto">&#10060; No aceptado por: <?php echo htmlspecialchars($comunicado['notif_apellido'] . ', ' . $comunicado['notif_nombre']); ?></span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
    <p>No hay comunicados disponibles.</p>
<?php endif; ?>
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/

</body>
</html>
