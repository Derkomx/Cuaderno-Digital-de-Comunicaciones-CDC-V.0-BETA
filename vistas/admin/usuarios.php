<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Archivo: usuarios.php

include 'includes/MySQL.php';

// Manejar la actualización o eliminación del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $mysqli->real_escape_string($_POST['dni']);

    // Verificar si se debe eliminar el usuario
    if (isset($_POST['delete_user'])) {
        // Preparar la consulta de eliminación
        $sql_delete_user = $mysqli->prepare("DELETE FROM usuarios WHERE dni = ?");
        $sql_delete_user->bind_param("s", $dni);

        // Ejecutar y verificar si se eliminó el usuario
        if ($sql_delete_user->execute()) {
            echo "<script>
                    alert('Usuario eliminado correctamente');
                    window.location.href = 'index.php?Seccion=usuarios';
                  </script>";
        } else {
            echo "<script>
                    alert('Error al eliminar el usuario: {$sql_delete_user->error}');
                    window.location.href = 'index.php?Seccion=usuarios';
                  </script>";
        }

        $sql_delete_user->close(); // Cerrar la consulta preparada
        exit();
    }

    // Verificar si se debe actualizar el usuario
if (isset($_POST['update_user'])) {
    session_start();
    $dni_modificador = $_SESSION['dni']; // Usuario que realiza la modificación
    $dni_modificado = $mysqli->real_escape_string($_POST['dni']); // Usuario que es modificado

    $apellido = $mysqli->real_escape_string($_POST['apellido']);
    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $curso = $mysqli->real_escape_string($_POST['curso']);
    $division = $mysqli->real_escape_string($_POST['division']);
    $nuevo_nivel = $mysqli->real_escape_string($_POST['nivel']);

    // Obtener datos actuales del usuario antes de actualizar
    $query_old_data = $mysqli->prepare("SELECT apellido, nombre, curso, division, nivel FROM usuarios WHERE dni = ?");
    $query_old_data->bind_param("s", $dni_modificado);
    $query_old_data->execute();
    $result = $query_old_data->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $apellido_anterior = $row['apellido'];
        $nombre_anterior = $row['nombre'];
        $curso_anterior = $row['curso'];
        $division_anterior = $row['division'];
        $nivel_anterior = $row['nivel'];

        // Insertar en historial_actualizaciones
        $sql_insert_historial = $mysqli->prepare("INSERT INTO historial_actualizaciones 
            (dni_usuario_modificador, dni_usuario_modificado, 
            apellido_anterior, apellido_nuevo, 
            nombre_anterior, nombre_nuevo, 
            curso_anterior, curso_nuevo, 
            division_anterior, division_nuevo, 
            nivel_anterior, nivel_nuevo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $sql_insert_historial->bind_param("ssssssssssss",
            $dni_modificador, $dni_modificado,
            $apellido_anterior, $apellido,
            $nombre_anterior, $nombre,
            $curso_anterior, $curso,
            $division_anterior, $division,
            $nivel_anterior, $nuevo_nivel
        );
        $sql_insert_historial->execute();
    }

    // Actualizar datos del usuario
    $sql_update_user = $mysqli->prepare("UPDATE usuarios SET apellido = ?, nombre = ?, curso = ?, division = ?, nivel = ? WHERE dni = ?");
    $sql_update_user->bind_param("ssssss", $apellido, $nombre, $curso, $division, $nuevo_nivel, $dni_modificado);
    if ($sql_update_user->execute()) {
            
        echo "<script>
                alert('Datos de usuario actualizados correctamente');
                window.location.href = 'index.php?Seccion=usuarios';
              </script>";
    } else {
        echo "<script>
                alert('Error al actualizar el usuario: {$sql_update_user->error}');
                window.location.href = 'index.php?Seccion=usuarios';
              </script>";
    }

    $sql_update_user->close(); // Cerrar la consulta preparada
    exit();
}
    ///
}

// Obtener el criterio de orden y el orden ascendente/descendente
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'apellido';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';
$next_order_dir = $order_dir === 'asc' ? 'desc' : 'asc';

// Obtener los usuarios de la base de datos ordenados por el criterio
$sql = "SELECT dni, apellido, nombre, curso, division, nivel FROM usuarios ORDER BY $order_by $order_dir";
$resultado = $mysqli->query($sql);
$usuarios = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $usuarios[] = $fila;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <div class="container">
        <br>
        <h2>Gestión de Usuarios</h2>
        <br>

        <!-- Barra de búsqueda -->
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Buscar por DNI, apellido, nombre..." onkeyup="filterUsers()">
        </div>

        <table id="usuarios-table" class="table table-striped">
            <thead>
                <tr>
                    <th><a href="?Seccion=usuarios&order_by=dni&order_dir=<?php echo $next_order_dir; ?>">DNI</a></th>
                    <th><a href="?Seccion=usuarios&order_by=apellido&order_dir=<?php echo $next_order_dir; ?>">Apellido</a></th>
                    <th><a href="?Seccion=usuarios&order_by=nombre&order_dir=<?php echo $next_order_dir; ?>">Nombre</a></th>
                    <th><a href="?Seccion=usuarios&order_by=curso&order_dir=<?php echo $next_order_dir; ?>">Curso</a></th>
                    <th><a href="?Seccion=usuarios&order_by=division&order_dir=<?php echo $next_order_dir; ?>">División</a></th>
                    <th><a href="?Seccion=usuarios&order_by=nivel&order_dir=<?php echo $next_order_dir; ?>">Nivel</a></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="user-list">
                <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $usuario): ?>
                <tr class="user-row">
                    <form method="POST">
                        <td><?php echo htmlspecialchars($usuario['dni']); ?></td>
                        <td><input type="text" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>"></td>
                        <td><input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>"></td>
                        <td><input type="text" name="curso" value="<?php echo htmlspecialchars($usuario['curso']); ?>"></td>
                        <td><input type="text" name="division" value="<?php echo htmlspecialchars($usuario['division']); ?>"></td>
                        <td>
                            <?php
                            // Definir roles basados en el nivel
                            $roles = [
                                2 => "Tutor",
                                3 => "Estudiante",
                                4 => "Admin",
                                5 => "Profesor",
                                6 => "Portero",
                                7 => "Preceptor",
                                8 => "Cocina",
                                9 => "Música"
                            ];
                            ?>
                            <!-- Mostrar rol actual en un select -->
                            <input type="hidden" name="dni" value="<?php echo htmlspecialchars($usuario['dni']); ?>">
                            <select name="nivel">
                                <?php foreach ($roles as $nivel => $rol): ?>
                                    <option value="<?php echo $nivel; ?>" <?php echo ($usuario['nivel'] == $nivel) ? 'selected' : ''; ?>>
                                        <?php echo $rol; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <button type="submit" name="update_user" class="btn btn-warning btn-sm">Editar</button>
                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</button>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7">No hay usuarios para mostrar.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function filterUsers() {
            const searchQuery = document.getElementById("search-input").value.toLowerCase();
            const rows = document.querySelectorAll(".user-row");
            
            rows.forEach(row => {
                const dni = row.cells[0].textContent.toLowerCase();
                const apellido = row.cells[1].querySelector('input').value.toLowerCase();
                const nombre = row.cells[2].querySelector('input').value.toLowerCase();

                if (dni.includes(searchQuery) || apellido.includes(searchQuery) || nombre.includes(searchQuery)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
</body>
</html>
