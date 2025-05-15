<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include 'includes/MySQL.php';

// Activar errores de MySQLi para depuración (solo en desarrollo)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Mostrar errores en pantalla (solo para desarrollo, desactivar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Recibir los datos del formulario por GET
$fecha = $_GET['fecha'] ?? '';
$curso = $_GET['curso'] ?? '';  
$division = $_GET['division'] ?? '';
$turno = $_GET['turno'] ?? '';
$turno = mb_convert_encoding($turno, 'UTF-8', 'auto');

if (!$fecha || !$curso || !$division || !$turno) {
    die("Faltan datos en la solicitud.");
}

try {
    // Consulta para obtener la asistencia considerando el turno
    $sql = "SELECT u.dni, u.apellido, u.nombre, a.presente 
            FROM asistencia a
            JOIN usuarios u ON a.dni = u.dni
            WHERE a.fecha = ? AND a.curso = ? AND a.division = ? AND a.turno = ?";

    if (!$stmt = $mysqli->prepare($sql)) {
        throw new Exception("Error en la preparación de la consulta: " . $mysqli->error);
    }

    if (!$stmt->bind_param('siis', $fecha, $curso, $division, $turno)) {
        throw new Exception("Error al enlazar parámetros: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Error en la ejecución de la consulta: " . $stmt->error);
    }

    $resultado = $stmt->get_result();
    $hayDatos = $resultado->num_rows > 0;
} catch (Exception $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia del <?php echo htmlspecialchars($fecha); ?></title>
    <style>
    .texto {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ccc;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    .presente {
        color: green;
        font-weight: bold;
    }

    .ausente {
        color: red;
        font-weight: bold;
    }

    /* Responsividad */
    @media (max-width: 600px) {
        table {
            font-size: 14px;
        }

        th, td {
            padding: 8px;
        }
    }
</style>
</head>
<body>
    <div class="texto">
    <h1>Asistencia del <?php echo htmlspecialchars($fecha); ?></h1>
    <h2>Curso: <?php echo $curso; ?>° - División: <?php echo $division; ?> - Turno: <?php echo ucfirst($turno); ?></h2>

    <table>
        <tr>
            <th>DNI</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>Asistencia</th>
        </tr>
        <?php if ($hayDatos): ?>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['dni']; ?></td>
                    <td><?php echo $row['apellido']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td class="<?php echo $row['presente'] ? 'presente' : 'ausente'; ?>">
                        <?php echo $row['presente'] ? 'Presente' : 'Ausente'; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align: center; font-weight: bold; color: gray;">
                    No existen datos para este día y turno.
                </td>
            </tr>
        <?php endif; ?>
    </table>
    </div>

</body>
</html>
