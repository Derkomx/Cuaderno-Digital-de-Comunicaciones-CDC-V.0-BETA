<html>

<head>
    <title>Iniciar Sesion</title>
    <!-- Entradas css -->
    <link rel="stylesheet" href="librerias/notiflix-2.4.0.min.css" />
    <link rel="stylesheet" href="css/solid.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="librerias/fontawesome-free/css/all.min.css">
    <!-- Entradas js -->
    <script src="librerias/notiflix-2.4.0.min.js"></script>
    <script src="librerias/jQuery.js"></script>
    <script src="librerias/jquery.maskedinput.js"></script>
    <script src="js/Ingreso.js"></script>
    <script>
    $(document).ready(function() {
        if (localStorage.getItem('Recordardni')) {
            $('#dni').val(localStorage.getItem('Recordardni'));
            $('#Recordar').prop('checked', true);
        }
        $('#Recordar').change(function() {
            if ($(this).is(':checked')) {
                localStorage.setItem('Recordardni', $('#dni').val());
            } else {
                localStorage.removeItem('Recordardni');
            }
        });
    });
    </script>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
</head>

<body>
    <div class="login-page">
        <p class="aligncenter">
            <img src="./media/logo.webp" width="180" height="180" />
        </p>
        <div class="form">
            <p class="login-box-msg">Ingrese su Usuario y Contraseña</p>
            <div class="input-group mb-0">
                <input id="dni" name="User" type="text" maxlength="8" class="form-control" placeholder="Ingrese DNI"
                    autocomplete="off" onkeypress="return enterKeyPressed(event)" inputmode="numeric" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <form class="input-group mb-0">
                <input id="clave" name="Pass" class="form-control" type="password" placeholder="Ingrese Contraseña"
                    autocomplete="off" onkeypress="return enterKeyPressed(event)" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </form>

            <label class="new-checks">Recordar usuario
                <div>
                    <input type="checkbox" id="Recordar">
                    <span class="checkmark"></span>
                </div>
            </label>
            <button type="submit" onclick="InicioSesion()">Iniciar sesión</button>
            <p></p>
            <div class="tab-custom-content"></div>
            <p class="mb-1">
                <a href="index.php?Enlace=Recuperar" class="link">Olvidé mi clave</a>
            </p>
            <p class="mb-0">
                <a href="index.php?Enlace=Registro" class="link">Registrarme</a>
            </p>
        </div>
    </div>
</body>
<script src="js/Ingreso.js"></script>

<script src="librerias/bootbox.min.js"></script>
<script>
// Obtener datos de sesión desde PHP
var sessionData = <?php echo json_encode($_SESSION); ?>;

// Imprimir datos de sesión en la consola
console.log("Datos de la sesión:", sessionData);
</script>

</html>
