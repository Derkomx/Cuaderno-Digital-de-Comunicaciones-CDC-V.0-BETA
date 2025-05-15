<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Archivo: comedoraldia.php

include 'includes/MySQL.php';

// Obtenemos la fecha actual en formato Y-m-d
$fecha_actual = date("Y-m-d");

// Consulta SQL para obtener las inscripciones del día actual
$sql = "SELECT u.nombre, u.apellido, u.curso, u.division, i.hora 
        FROM inscripciones AS i
        JOIN usuarios AS u ON i.dni = u.dni
        WHERE i.fecha = '$fecha_actual'
        ORDER BY i.hora ASC, u.curso ASC, u.division ASC, u.apellido ASC";
        
$resultado = $mysqli->query($sql);

$inscripciones = [];

if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $inscripciones[] = $fila;
    }
}

// Consultas adicionales para obtener la cantidad de anotados
$totalSql = "SELECT COUNT(*) AS total FROM inscripciones WHERE fecha = '$fecha_actual'";
$totalResult = $mysqli->query($totalSql);
$totalCount = $totalResult->fetch_assoc()['total'];

$hora12Sql = "SELECT COUNT(*) AS count FROM inscripciones WHERE fecha = '$fecha_actual' AND hora = '12:00:00'";
$hora12Result = $mysqli->query($hora12Sql);
$hora12Count = $hora12Result->fetch_assoc()['count'];

$hora1230Sql = "SELECT COUNT(*) AS count FROM inscripciones WHERE fecha = '$fecha_actual' AND hora = '12:30:00'";
$hora1230Result = $mysqli->query($hora1230Sql);
$hora1230Count = $hora1230Result->fetch_assoc()['count'];

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones para el día de hoy</title>
</head>
<body>
<div class="container">
    <br>
    <h2>Inscripciones para el día de hoy</h2>
    <br>

    <!-- Información de la cantidad de inscripciones -->
    <div>
        <p><strong>Total de anotados:</strong> <?php echo $totalCount; ?></p>
        <p><strong>Anotados en el horario de 12:00:</strong> <?php echo $hora12Count; ?></p>
        <p><strong>Anotados en el horario de 12:30:</strong> <?php echo $hora1230Count; ?></p>
    </div>
    
    <!-- Select para elegir horario -->
    <div class="form-group">
        <label for="hora-select">Elegir horario:</label>
        <select id="hora-select" class="form-control">
            <option value="">Elegir horario</option>
            <option value="12:00:00">12:00</option>
            <option value="12:30:00">12:30</option>
        </select>
    </div>
    <br>
    <table id="inscripciones-table" class="table table-striped">
        <thead>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Curso</th>
                <th>División</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($inscripciones)): ?>
                <?php foreach ($inscripciones as $inscripcion): ?>
                    <tr class="inscripcion-row" data-hora="<?php echo htmlspecialchars($inscripcion['hora']); ?>">
                        <td><?php echo htmlspecialchars($inscripcion['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($inscripcion['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($inscripcion['curso']); ?></td>
                        <td><?php echo htmlspecialchars($inscripcion['division']); ?></td>
                        <td><?php echo htmlspecialchars($inscripcion['hora']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay inscripciones para el día de hoy.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#hora-select').change(function() {
        var selectedHora = $(this).val();
        if (selectedHora) {
            $('.inscripcion-row').each(function() {
                var rowHora = $(this).data('hora');
                if (rowHora === selectedHora) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('.inscripcion-row').show();
        }
    });
});
</script>

</body>
</html>
