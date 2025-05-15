
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario con Selects Dinámicos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>

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
    $(document).ready(function() {
        // Manejar el evento de clic para el botón "Enviar"
        $('#btnEnviar').on('click', function(e) {
            e.preventDefault(); // Previene el envío del formulario por defecto

            const cursoSeleccionado = $('#anio').find(':selected').data('curso');
            const divisionSeleccionada = $('#anio').find(':selected').data('division');
            const turnoSeleccionado = $('#turno').val(); // Obtener el valor del select de turno

            // Redirigir a la página con los datos
            const actionUrl = `index.php?Seccion=asistencia2&anio=${cursoSeleccionado}&division=${divisionSeleccionada}&turno=${turnoSeleccionado}`;
            window.location.href = actionUrl;
        });

        // Habilitar el botón "Enviar" cuando ambos selects tienen un valor
        $('#anio, #turno').on('change', function() {
            const anioVal = $('#anio').val();
            const turnoVal = $('#turno').val();
            $('#btnEnviar').prop('disabled', !(anioVal && turnoVal)); // Habilitar solo si ambos tienen valor
        });

        // Resaltar los select cuando se selecciona algo
        $('select').on('focus', function() {
            $(this).addClass('highlight');
        }).on('blur', function() {
            $(this).removeClass('highlight');
        });
    });
</script>
</head>

<body>
    <h1 style="text-align: center;">Asistencia</h1>
    <form id="formulario-escolar" action="index.php" method="get">
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
        
        <label for="turno">Seleccione Turno:</label>
        <select id="turno" name="turno" required>
            <option value="" disabled selected>Seleccione Turno</option>
            <option value="clase">Clase</option>
            <option value="taller">Taller</option>
        </select>
        
        <button type="submit" id="btnEnviar" disabled>Aceptar</button>
    </form>
</body>

</html>
