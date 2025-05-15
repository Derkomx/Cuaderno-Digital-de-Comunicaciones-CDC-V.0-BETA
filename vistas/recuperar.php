<!DOCTYPE html>
<html>

<head>
    <title>Recuperar Clave</title>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">

    <!-- Entradas css -->
    <link rel="stylesheet" href="librerias/notiflix-2.4.0.min.css" />
    <link rel="stylesheet" href="css/solid.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="librerias/fontawesome-free/css/all.min.css">
    <!-- Entradas js -->
    <script src="librerias/notiflix-2.4.0.min.js"></script>
    <script src="librerias/jQuery.js"></script>
    <script src="librerias/jquery.maskedinput.js"></script>
    <script src="js/Recuperar.js"></script>
</head>

<body>
    <div class="login-page">
        <p class="aligncenter">
            <img src="./media/logo.webp" width="180" height="180" />
        </p>
        <div class="form">
            <h2 style="text-align: center;">Olvidé mi clave</h2>

            <p style="padding: 0; font-size: 16px; line-height: 20px; text-align: center;">Si no recuerdas la contraseña
                de tu cuenta o perdiste la misma, introduce el DNI con el que te registraste y a continuación le enviaremos 
                a tu tutor las instrucciones para recuperar la misma</p>
            <div class="input-group mb-0">
                <input id="dni" name="dni" placeholder="DNI" autocomplete="off" class="form-control" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>

            <button type="submit" onclick="Recupera()">Recuperar</button>

            <div class="tab-custom-content"></div>

            <p class="mb-1">

                <a href="index.php" class="link">Iniciar sesion</a>

            </p>

            <p class="mb-1">

                <a href="index.php?Enlace=Registro" class="link">Registrarme</a>

            </p>
        </div>
    </div>
</body>

</html>
