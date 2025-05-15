<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Archivo: calendario_eventos.php

include 'includes/MySQL.php'; // Conexión a la base de datos

// Consulta para obtener los eventos
$sql = "SELECT  titulo, evento, fecha FROM eventos ORDER BY fecha ASC";
$resultado = $mysqli->query($sql);

$eventos = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $eventos[] = $fila;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Eventos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .category {
            font-weight: bold;
            color: #007bff;
        }
        .highlight {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h1>Calendario de Eventos Importantes</h1>

<?php if (!empty($eventos)): ?>
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Categoría</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventos as $index => $evento): ?>
                <tr class="<?php echo $index % 2 === 0 ? 'highlight' : ''; ?>">
                    <td><?php echo htmlspecialchars($evento['fecha']); ?></td>
                    <td class="category"><?php echo htmlspecialchars($evento['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($evento['evento']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
    <p style="text-align: center;">No hay eventos disponibles.</p>
<?php endif; ?>
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/

</body>
</html>
