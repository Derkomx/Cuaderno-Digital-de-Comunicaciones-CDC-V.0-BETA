<html>

<head>
    <title>Recuperar Contraseña</title>

    <!-- Librería jQuery - maskedinput -->
    <script src="librerias/jQuery.js"></script>
    <script src="librerias/jquery.maskedinput.js"></script>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />

    <!-- Librería Notiflix -->
    <link rel="stylesheet" href="librerias/notiflix-2.4.0.min.css" />
    <script src="librerias/notiflix-2.4.0.min.js"></script>

    <!-- Estilos y Font Awesome -->
    <link rel="stylesheet" href="css/solid.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
</head>

<body>
    <div class="recover-page" style="display: none;">
        <p class="aligncenter">
            <img src="media/logo.webp" width="180" height="180" />
        </p>
        <div class="form">
            <p class="login-box-msg">Ingrese una nueva contraseña</p>
            <div class="input-group mb-0">
                <input id="Clave" name="Clave" type="password" class="form-control" placeholder="Contraseña"
                    autocomplete="off" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-0">
                <input id="CClave" name="CClave" class="form-control" type="password" placeholder="Repita Contraseña"
                    autocomplete="off" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>

            <button type="submit" onclick="Recuperar()">Confirmar</button>
        </div>
    </div>
</body>

<script>
// Configuración de Notiflix
Notiflix.Report.Init({
    plainText: false,
    svgSize: "50px",
});

// Función para obtener los parámetros de la URL actual
function obtenerParametro(Parametro) {
    var sPaginaURL = window.location.search.substring(1);
    var sURLVariables = sPaginaURL.split('&');

    for (var i = 0; i < sURLVariables.length; i++) {
        var sParametro = sURLVariables[i].split('=');
        if (sParametro[0] == Parametro) {
            return sParametro[1];
        }
    }
    return null;
}

function Inicio() {
    Notiflix.Loading.Circle('Cargando...');

    // Verifica si el parámetro "Recovery" está en la URL
    if (!obtenerParametro('Recovery')) {
        document.location = 'index.php';
        return;
    }

    // Recuperación de cuenta
    $.ajax({
        type: 'POST',
        url: 'Inyector.php',
        data: {
            Archivo: 'Recuperar.php',
            Recovery: obtenerParametro('Recovery')
        },
        dataType: 'html',
        success: function(data) {
            console.log(data);
            var Resultado = JSON.parse(data);
            Notiflix.Loading.Remove();

            if (Resultado.error) {
                Notiflix.Report.Failure('¡Error!', Resultado.error, 'Aceptar', function() {
                    document.location = "index.php";
                });
            }

            if (Resultado.success) {
                document.getElementsByClassName('recover-page')[0].style.display = 'block';
            }
        },
        error: function() {
            Notiflix.Report.Failure(
                '¡Ocurrió un Problema!',
                'Tu clave no fue modificada',
                'Aceptar',
                function() {
                    document.location = "index.php";
                }
            );
        }
    });
}

function Recuperar() {
    var Clave = document.getElementById("Clave").value;
    var Confirmacion = document.getElementById("CClave").value;

    if (Clave.length === 0) {
        Notiflix.Notify.Failure("Debes ingresar una contraseña!");
        return;
    }

    if (Confirmacion.length === 0) {
        Notiflix.Notify.Failure("Debes confirmar tu contraseña!");
        return;
    }

    if (Clave === Confirmacion) {
        $.ajax({
            type: 'POST',
            url: 'Inyector.php',
            data: {
                Archivo: 'Recuperar.php',
                tipo: 'recuperar',
                hash: obtenerParametro('Recovery'),
                Clave: Clave // Asegúrate de que esté encriptada antes de enviarla
            },
            dataType: 'html',
            success: function(data) {
                var Resultado = JSON.parse(data);
                console.log(Resultado);
                Notiflix.Loading.Remove();

                if (Resultado.error) {
                    Notiflix.Report.Failure('¡Error!', Resultado.error, 'Aceptar');
                }

                if (Resultado.success) {
                    Notiflix.Report.Success(
                        '¡Cambio exitoso!',
                        'Tu contraseña fue modificada con éxito!',
                        'Aceptar',
                        function() {
                            document.location = "index.php";
                        }
                    );
                }
            },
            error: function() {
                Notiflix.Report.Failure(
                    '¡Ocurrió un Problema!',
                    'Su contraseña no fue modificada',
                    'Aceptar',
                    function() {
                        document.location = "index.php";
                    }
                );
            }
        });
    }
}

Inicio();
</script>

</html>
