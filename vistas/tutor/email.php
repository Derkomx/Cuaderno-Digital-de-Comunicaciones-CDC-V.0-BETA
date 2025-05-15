<?php 
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
session_start();
include 'includes/MySQL.php';

$dni = $_SESSION['dni'] ?? '';
$email = '';

if (!empty($dni)) {
    $sql = "SELECT mail FROM tutordatos WHERE dni = '$dni' LIMIT 1";
    $resultado = $mysqli->query($sql);
    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $email = $fila['mail'];
    }
    $resultado->free();
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <style>
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            justify-content: center;
            align-items: center;
            margin: 50px auto;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .message {
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
        }
    </style>
    <!-- Asegúrate de incluir jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="form-container">
    <h2>Actualizar Email</h2>
    <form id="emailForm">
        <!-- Se muestra el correo recuperado en el value del input -->
        <input type="email" id="email" name="email" placeholder="Ingresa tu email" value="<?php echo htmlspecialchars($email); ?>" required>
        <button type="submit">Aceptar</button>
        <div class="message" id="message"></div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#emailForm').on('submit', function (e) {
            e.preventDefault(); // Evitar que se recargue la página

            const email = $('#email').val(); // Obtener el email ingresado
            const dni = '<?php echo $_SESSION["dni"]; ?>'; // Obtener el DNI desde la sesión

            // Mostrar la pantalla de carga
            Notiflix.Loading.Circle('Cargando...');

            $.ajax({
                type: 'POST',
                url: 'Inyector.php',
                data: { 
                    Archivo: 'actualizar_email.php', 
                    dni: dni, 
                    email: email 
                },
                dataType: 'json', // Esperamos una respuesta JSON
                success: function(Resultado) {
                    Notiflix.Loading.Remove(); // Eliminar la pantalla de carga

                    if (Resultado.error) {
                        Notiflix.Notify.Failure(Resultado.error); // Mostrar error
                        return;
                    }

                    if (Resultado.success) {
                        Notiflix.Report.Success(
                            '¡Email Actualizado!',
                            'Tu email fue actualizado correctamente.',
                            'Aceptar',
                            function() {
                                document.location.reload(); // Recargar la página
                            }
                        );
                    }
                },
                error: function() {
                    Notiflix.Loading.Remove();
                    Notiflix.Notify.Failure('Error en la conexión con el servidor.');
                }
            });
        });
    });
</script>

</body>
</html>
