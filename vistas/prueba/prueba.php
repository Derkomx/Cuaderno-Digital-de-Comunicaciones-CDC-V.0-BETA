<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Incluye la conexión a la base de datos
include 'includes/MySQL.php';

// Cargar todos los preceptores y profesores
$sql = "SELECT apellido, nombre, dni, nivel FROM usuarios WHERE nivel IN (5, 7) ORDER BY apellido ASC, nombre ASC";
$resultado = $mysqli->query($sql);

$usuarios = []; // Para almacenar los preceptores y profesores
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $usuarios[] = [
            'apellido' => $fila['apellido'],
            'nombre' => $fila['nombre'],
            'dni' => $fila['dni'],
            'nivel' => $fila['nivel'] // 5 = Profesor, 7 = Preceptor
        ];
    }
    $resultado->free();
} else {
    throw new Exception('Error en la consulta SQL: ' . $mysqli->error);
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Destinatario</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .highlight {
            border: 2px solid #4CAF50;
        }
    </style>
    <script>
        var usuarios = <?php echo json_encode($usuarios); ?>;

        $(document).ready(function() {
            $('#nivel').on('change', function() {
                const nivelSeleccionado = $(this).val();

                // Filtrar usuarios por nivel seleccionado
                const usuariosFiltrados = usuarios.filter(user => user.nivel == nivelSeleccionado);

                // Actualizar el select de destinatarios
                $('#destinatario').empty().append(
                    '<option value="todos" selected>Enviar a todos</option>'
                );

                usuariosFiltrados.forEach(usuario => {
                    $('#destinatario').append(
                        `<option value="${usuario.dni}">${usuario.apellido}, ${usuario.nombre}</option>`
                    );
                });
            });

            // Manejar el clic en el botón "Enviar"
            $('#btnEnviar').on('click', function(e) {
                e.preventDefault();

                const nivelSeleccionado = $('#nivel').val();
                const destinatarioSeleccionado = $('#destinatario').val();

                // Redirigir con los datos seleccionados
                const actionUrl = `index.php?Seccion=prueba2&nivel=${nivelSeleccionado}&destinatario=${destinatarioSeleccionado}`;
                window.location.href = actionUrl;
            });

            // Habilitar el botón "Enviar" cuando ambos selects tienen un valor
            $('#nivel, #destinatario').on('change', function() {
                $('#btnEnviar').prop('disabled', !($('#nivel').val() && $('#destinatario').val()));
            });

            // Resaltar select cuando se enfoca
            $('#nivel, #destinatario').on('focus', function() {
                $(this).addClass('highlight');
            }).on('blur', function() {
                $(this).removeClass('highlight');
            });
        });
    </script>
</head>

<body>
    <h1 style="text-align: center;">Seleccionar Destinatario</h1>
    <form id="formulario-usuarios" action="index.php?Seccion=prueba2" method="post">
        <label for="nivel">Seleccione Tipo de Usuario:</label>
        <select id="nivel" name="nivel" required>
            <option value="" disabled selected>Seleccione una opción</option>
            <option value="7">Preceptores</option>
            <option value="5">Profesores</option>
        </select>

        <label for="destinatario">Seleccione Destinatario:</label>
        <select id="destinatario" name="destinatario" required>
            <option value="todos" selected>Enviar a todos</option>
        </select>

        <button type="submit" id="btnEnviar" disabled>Aceptar</button>
    </form>
</body>
</html>
