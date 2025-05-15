<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Archivo: usuarioscomedor.php

include 'includes/MySQL.php';

// Manejar la creación, actualización o eliminación de registros en la tabla comedor_ver
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        // Agregar nuevo usuario
        $apellido = $mysqli->real_escape_string($_POST['apellido']);
        $nombre = $mysqli->real_escape_string($_POST['nombre']);
        $curso = $mysqli->real_escape_string($_POST['curso']);
        $division = $mysqli->real_escape_string($_POST['division']);

        $sql_add_user = "INSERT INTO comedor_ver (apellido, nombre, curso, division) VALUES ('$apellido', '$nombre', '$curso', '$division')";
        
        if ($mysqli->query($sql_add_user) === TRUE) {
            echo "<script>
                    alert('Usuario agregado correctamente');
                    window.location.href = 'index.php?Seccion=usuarioscomedor';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Error al agregar el usuario: " . $mysqli->error . "');
                    window.location.href = 'index.php?Seccion=usuarioscomedor';
                  </script>";
            exit();
        }
    } elseif (isset($_POST['update_user'])) {
        // Actualización de datos del usuario
        $id = $mysqli->real_escape_string($_POST['id']);
        $apellido = $mysqli->real_escape_string($_POST['apellido']);
        $nombre = $mysqli->real_escape_string($_POST['nombre']);
        $curso = $mysqli->real_escape_string($_POST['curso']);
        $division = $mysqli->real_escape_string($_POST['division']);

        $sql_update_user = "UPDATE comedor_ver SET apellido = '$apellido', nombre = '$nombre', curso = '$curso', division = '$division' WHERE id = '$id'";
        
        if ($mysqli->query($sql_update_user) === TRUE) {
            echo "<script>
                    alert('Datos actualizados correctamente');
                    window.location.href = 'index.php?Seccion=usuarioscomedor';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Error al actualizar: " . $mysqli->error . "');
                    window.location.href = 'index.php?Seccion=usuarioscomedor';
                  </script>";
            exit();
        }
    } elseif (isset($_POST['delete_user'])) {
        // Eliminación de usuario
        $id = $mysqli->real_escape_string($_POST['id']);
        $sql_delete_user = "DELETE FROM comedor_ver WHERE id = '$id'";
        
        if ($mysqli->query($sql_delete_user) === TRUE) {
            echo "<script>
                    alert('Usuario eliminado correctamente');
                    window.location.href = 'index.php?Seccion=usuarioscomedor';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Error al eliminar: " . $mysqli->error . "');
                    window.location.href = 'index.php?Seccion=usuarioscomedor';
                  </script>";
            exit();
        }
    }
}

// Obtener el criterio de orden y el orden ascendente/descendente
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'apellido';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';
$next_order_dir = $order_dir === 'asc' ? 'desc' : 'asc';

// Obtener los usuarios de la base de datos ordenados por el criterio
$sql = "SELECT id, apellido, nombre, curso, division FROM comedor_ver ORDER BY $order_by $order_dir";
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
    <title>Gestión de Usuarios en Comedor</title>
    <link rel="stylesheet" href="vistas/mixedsongs/styles.css">
</head>
<body>
    <div class="container">
        <h2>Gestión de Usuarios en Comedor</h2>

        <!-- Barra de búsqueda -->
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Buscar por apellido o nombre..." onkeyup="filterUsers()">
        </div>
        <br><br>
        <!-- Botón para agregar un nuevo usuario -->
        <button onclick="document.getElementById('add-user-form').style.display='block'" class="btn btn-primary">Agregar Nuevo Usuario</button>
        <br><br>

        <!-- Formulario para agregar nuevo usuario -->
        <div id="add-user-form" style="display: none;">
            <form method="POST">
                <input type="text" name="apellido" placeholder="Apellido" required>
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="text" name="curso" placeholder="Curso" required>
                <input type="text" name="division" placeholder="División" required>
                <button type="submit" name="add_user" class="btn btn-success">Guardar</button>
                <button type="button" onclick="document.getElementById('add-user-form').style.display='none'" class="btn btn-secondary">Cancelar</button>
            </form>
            <br>
        </div>

        <table id="usuarios-table" class="table table-striped">
            <thead>
                <tr>
                    <th><a href="?Seccion=usuarioscomedor&order_by=apellido&order_dir=<?php echo $next_order_dir; ?>">Apellido</a></th>
                    <th><a href="?Seccion=usuarioscomedor&order_by=nombre&order_dir=<?php echo $next_order_dir; ?>">Nombre</a></th>
                    <th><a href="?Seccion=usuarioscomedor&order_by=curso&order_dir=<?php echo $next_order_dir; ?>">Curso</a></th>
                    <th><a href="?Seccion=usuarioscomedor&order_by=division&order_dir=<?php echo $next_order_dir; ?>">División</a></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="user-list">
                <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $usuario): ?>
                <tr class="user-row">
                    <form method="POST">
                        <td><input type="text" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>"></td>
                        <td><input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>"></td>
                        <td><input type="text" name="curso" value="<?php echo htmlspecialchars($usuario['curso']); ?>"></td>
                        <td><input type="text" name="division" value="<?php echo htmlspecialchars($usuario['division']); ?>"></td>
                        <td>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['id']); ?>">
                            <button type="submit" name="update_user" class="btn btn-warning btn-sm">Editar</button>
                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</button>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5">No hay usuarios para mostrar.</td>
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
                const apellido = row.cells[0].querySelector('input').value.toLowerCase();
                const nombre = row.cells[1].querySelector('input').value.toLowerCase();

                if (apellido.includes(searchQuery) || nombre.includes(searchQuery)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
</body>
</html>
