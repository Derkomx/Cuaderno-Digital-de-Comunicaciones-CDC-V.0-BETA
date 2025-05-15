<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Asistencia</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        select, input[type="date"] {
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
    </style>
    <script>
        $(document).ready(function() {
            // Habilitar el botón "Enviar" solo si todos los campos están seleccionados
            function verificarCampos() {
                const anioVal = $('#anio').val();
                const turnoVal = $('#turno').val();
                const fechaVal = $('#fecha').val();
                $('#btnEnviar').prop('disabled', !(anioVal && turnoVal && fechaVal));
            }

            $('#anio, #turno, #fecha').on('change', verificarCampos);

            // Manejar el envío
            $('#btnEnviar').on('click', function(e) {
                e.preventDefault(); // Evita el envío por defecto
                
                const curso = $('#anio').find(':selected').data('curso');
                const division = $('#anio').find(':selected').data('division');
                const turno = $('#turno').val();
                const fecha = $('#fecha').val();

                if (!curso || !division || !turno || !fecha) return;

                // Redirigir con los datos en la URL
                const url = `index.php?Seccion=verasistencia2&curso=${curso}&division=${division}&turno=${turno}&fecha=${fecha}`;
                window.location.href = url;
            });
        });
    </script>
</head>
<body>
    <h1 style="text-align: center;">Consultar Asistencia</h1>
    <form id="formulario-asistencia" action="index.php" method="get">
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

        <label for="fecha">Seleccione Fecha:</label>
        <input type="date" id="fecha" name="fecha" required>

        <button type="submit" id="btnEnviar" disabled>Consultar</button>
    </form>
</body>
</html>
