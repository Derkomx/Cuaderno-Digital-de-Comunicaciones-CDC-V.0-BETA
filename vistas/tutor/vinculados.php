<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include 'includes/MySQL.php';

// Obtener el DNI del tutor desde la sesión
$dni_tutor = $_SESSION['dni'];

// Si se recibe un DNI asociado para eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_dni'])) {
    $dni_asociado = $_POST['eliminar_dni'];

    // Eliminar el vínculo en la tabla 'tutor'
    $sql_eliminar = "DELETE FROM tutor WHERE dni = '$dni_tutor' AND dni_asoc = '$dni_asociado'";
    $mysqli->query($sql_eliminar);
}

// Consulta SQL para obtener los usuarios vinculados
$sql = "SELECT u.dni AS dni_asociado, u.apellido, u.nombre, u.curso, u.division 
        FROM tutor AS t
        JOIN usuarios AS u ON t.dni_asoc = u.dni
        WHERE t.dni = '$dni_tutor'";

$resultado = $mysqli->query($sql);

$vinculos = [];

if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $vinculos[] = $fila;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Vinculados</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="librerias/bootstrap.min.css">
</head>
<body>
<div class="container">
    <br>
    <h2>Usuarios Vinculados</h2>
    <br>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>DNI Asociado</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Curso</th>
                <th>División</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($vinculos)): ?>
                <?php foreach ($vinculos as $vinculo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($vinculo['dni_asociado']); ?></td>
                        <td><?php echo htmlspecialchars($vinculo['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($vinculo['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($vinculo['curso']); ?></td>
                        <td><?php echo htmlspecialchars($vinculo['division']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="eliminar_dni" value="<?php echo htmlspecialchars($vinculo['dni_asociado']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No hay usuarios vinculados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
