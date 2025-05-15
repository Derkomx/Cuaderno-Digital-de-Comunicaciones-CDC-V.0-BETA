<!DOCTYPE html>
<html>

<head>
    <title>Vincular Usuario</title>
    <!-- Entradas css -->
    <link rel="stylesheet" href="librerias/notiflix-2.4.0.min.css" />
    <link rel="stylesheet" href="css/solid.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="librerias/fontawesome-free/css/all.min.css">
    <!-- Entradas js -->
    <script src="librerias/notiflix-2.4.0.min.js"></script>
    <script src="librerias/jQuery.js"></script>
    <script src="librerias/jquery.maskedinput.js"></script>
    <script src="librerias/pswmeter.min.js"></script>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
</head>

<body>
    <div class="login-page">
        <div class="form">
            <h2>Vincular Usuario</h2>

            <div class="input-group mb-0">
                <input id="dni_asoc" name="dni_asoc" type="text" placeholder="DNI a vincular" autocomplete="off"
                    class="form-control" />
            </div>

            <button type="button" onclick="vincular()">Vincular</button>

            <div id="resultado"></div>
        </div>
    </div>

    <script>
        function vincular() {
            var dni_asoc = document.getElementById("dni_asoc").value;

            $.ajax({
                type: 'POST',
                url: 'Inyector.php',
                data: { Archivo: 'vincular.php', dni_asoc: dni_asoc },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        Notiflix.Notify.Success(response.message);
                    } else {
                        Notiflix.Notify.Failure(response.error);
                    }
                },
                error: function () {
                    Notiflix.Notify.Failure('Error en la conexión con el servidor.');
                }
            });
        }
    </script>
</body>

</html>
