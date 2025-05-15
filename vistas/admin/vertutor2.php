<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Incluye la conexión a la base de datos
include 'includes/MySQL.php';

// Recupera el parámetro "alumno" (DNI del alumno)
$alumno = $_GET['alumno'] ?? '';

if (empty($alumno)) {
    echo "No se ha seleccionado un alumno.";
    exit;
}

// 1. Obtener nombre y apellido del alumno para el título
$sqlAlumno = "SELECT apellido, nombre FROM usuarios WHERE dni = '$alumno' LIMIT 1";
$resAlumno = $mysqli->query($sqlAlumno);
if ($resAlumno && $resAlumno->num_rows > 0) {
    $datosAlumno = $resAlumno->fetch_assoc();
    $nombreAlumno   = $datosAlumno['nombre'];
    $apellidoAlumno = $datosAlumno['apellido'];
} else {
    $nombreAlumno = "Alumno";
    $apellidoAlumno = "";
}
$resAlumno->free();

// 2. Buscar en la tabla "tutor" los registros donde dni_asoc coincide con el DNI del alumno,
// y obtener los datos del tutor (apellido, nombre y mail)
$sqlTutores = "
    SELECT u.apellido, u.nombre, td.mail
    FROM tutor t
    JOIN usuarios u ON t.dni = u.dni
    JOIN tutordatos td ON t.dni = td.dni
    WHERE t.dni_asoc = '$alumno'
";
$resTutores = $mysqli->query($sqlTutores);

$tutores = [];
if ($resTutores) {
    while ($row = $resTutores->fetch_assoc()) {
        $tutores[] = $row;
    }
    $resTutores->free();
} else {
    echo "Error en la consulta de tutores: " . $mysqli->error;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- El título incluye el nombre del alumno -->
    <title><?php echo $apellidoAlumno . ', ' . $nombreAlumno; ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4"><?php echo $apellidoAlumno . ', ' . $nombreAlumno; ?></h1>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Tutor Apellido</th>
                <th>Tutor Nombre</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($tutores) > 0): ?>
                <?php foreach ($tutores as $tutor): ?>
                    <tr>
                        <td><?php echo $tutor['apellido']; ?></td>
                        <td><?php echo $tutor['nombre']; ?></td>
                        <td><?php echo $tutor['mail']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No se encontraron tutores para este alumno.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<!-- Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
