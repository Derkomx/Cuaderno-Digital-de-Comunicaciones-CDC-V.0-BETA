<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Incluye la conexión a la base de datos
include 'includes/MySQL.php';

// Cargar todos los estudiantes inicialmente
$sql = "SELECT apellido, nombre, dni, curso, division FROM usuarios ORDER BY apellido ASC, nombre ASC";
$resultado = $mysqli->query($sql);

$allStudents = []; // Para almacenar todos los estudiantes
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $allStudents[] = [
            'apellido' => $fila['apellido'],
            'nombre' => $fila['nombre'],
            'dni' => $fila['dni'],
            'curso' => $fila['curso'],
            'division' => $fila['division']
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
    <title>Formulario con Selects Dinámicos</title>
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
        // Cargar todos los estudiantes en una variable global
        var allStudents = <?php echo json_encode($allStudents); ?>;

        $(document).ready(function() {
            $('#anio').on('change', function() {
                const curso = $(this).find(':selected').data('curso');
                const division = $(this).find(':selected').data('division');

                // Filtrar estudiantes
                const filteredStudents = allStudents.filter(student => 
                    student.curso == curso && student.division == division
                );

                // Actualiza el dropdown de alumnos
                $('#alumno').empty().append(
                    '<option value="todos" selected>Enviar a todos</option>'
                );

                filteredStudents.forEach(alumno => {
                    $('#alumno').append(
                        `<option value="${alumno.dni}">${alumno.apellido}, ${alumno.nombre}</option>`
                    );
                });
            });

            // Manejar el evento de clic para el botón "Enviar"
            $('#btnEnviar').on('click', function(e) {
                e.preventDefault(); // Previene el envío del formulario por defecto

                const cursoSeleccionado = $('#anio').find(':selected').data('curso');
                const divisionSeleccionada = $('#anio').find(':selected').data('division');
                const alumnoSeleccionado = $('#alumno').val(); // Valor seleccionado en el dropdown

                // Redirigir a la página con los datos
                const actionUrl = `index.php?Seccion=cuaderno22&anio=${cursoSeleccionado}&division=${divisionSeleccionada}&alumno=${alumnoSeleccionado}`;
                window.location.href = actionUrl;
            });

            // Habilitar el botón "Enviar" cuando ambos selects tienen un valor
            $('#anio, #alumno').on('change', function() {
                $('#btnEnviar').prop('disabled', !($('#anio').val() && $('#alumno').val()));
            });

            // Resaltar los select cuando se selecciona algo
            $('#anio, #alumno').on('focus', function() {
                $(this).addClass('highlight');
            }).on('blur', function() {
                $(this).removeClass('highlight');
            });
        });
    </script>
</head>

<body>
    <h1 style="text-align: center;">Formulario Escolar</h1>
    <form id="formulario-escolar" action="index.php?Seccion=cuaderno22" method="post">
        <label for="anio">Seleccione Año y División:</label>
        <select id="anio" name="anio" required>
            <option value="" disabled selected>Seleccione Año</option>
            <?php
                for ($i = 1; $i <= 6; $i++) { 
                    $nombreCurso = ($i <= 2) ? "{$i}° CB" : (($i - 2) . "° CS");
        
                    for ($j = 1; $j <= 4; $j++) { 
                        echo "<option value='{$i}_{$j}' data-curso='{$i}' data-division='{$j}'>{$nombreCurso} {$j}</option>";
                    }
                }
            ?>
        </select>

        <label for="alumno">Seleccione Alumno:</label>
        <select id="alumno" name="alumno" required>
            <option value="todos" selected>Enviar a todos</option>
        </select>

        <button type="submit" id="btnEnviar" disabled>Aceptar</button>
    </form>
</body>

</html>
