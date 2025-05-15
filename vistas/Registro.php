<!DOCTYPE html>
<html>

<head>
    <title>Registro</title>
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
    <script src="librerias/pswmeter.min.js"></script>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
</head>

<body>
    <div class="login-page">
        <!-- Imagen superior -->
        <p class="aligncenter">
            <img src="./media/logo.webp" width="180" height="180" />
        </p>

        <div class="form">
            <h2 aligncenter>Registrarme</h2>

            <div class="input-group mb-0">
                <input id="dni" name="dni" type="text" maxlength="8" placeholder="DNI" autocomplete="off"
                    class="form-control" inputmode="numeric" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-0">
                <input id="nombre" name="nombre" type="text" placeholder="Nombre" autocomplete="off"
                    class="form-control" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-0">
                <input id="apellido" name="apellido" type="text" placeholder="Apellido" autocomplete="off"
                    class="form-control" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-0">
                <select id="curso" name="curso" class="form-control">
                    <option value="">Selecciona el año</option>
                    <option value="1">1 CB</option>
                    <option value="2">2 CB</option>
                    <option value="3">1 CS</option>
                    <option value="4">2 CS</option>
                    <option value="5">3 CS</option>
                    <option value="6">4 CS</option>
                </select>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-0">
                <select id="division" name="division" class="form-control">
                    <option value="">Selecciona la división</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <form class="input-group mb-0">
                <input id="contraseña" name="contraseña" type="password" maxlength="19" placeholder="Contraseña"
                    autocomplete="off" class="form-control" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </form>
            <!-- Información de seguridad de contraseña -->
            <div style="display: none;" id="pswmeter" class="mt-3"></div>
            <div style="display: none; padding-top: 8px; padding-bottom: 6px; text-align: center;" id="pswmeter-message"
                class="mt-3"></div>

            <form class="input-group mb-0">
                <input id="Ccontraseña" name="Ccontraseña" type="password" maxlength="19"
                    placeholder="Confirmar contraseña" autocomplete="off" class="form-control" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </form>

            <label class="new-checks">Acepto los
                <a class="link" onclick="Terminos()">Términos y Condiciones</a></p>

                <div>
                    <input type="checkbox" id="checkterm">
                    <span class="checkmark"></span>
                </div>
            </label>
            <button type="submit" onclick="Registro()">Registrarme</button>
            <p></p>
            <div class="tab-custom-content"></div>
            <p class="mb-1">
                <a href="index.php?Enlace=Recuperar" class="link">Olvidé mi contraseña</a>
            </p>
            <p class="mb-0">
                <a href="index.php" class="link">Iniciar sesión</a>
            </p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Aplica la máscara para que solo acepte 8 dígitos sin puntos
            $('#dni').mask("99999999");

            // Elimina los puntos automáticamente al perder el foco
            $('#dni').on('blur', function() {
                let dniVal = $(this).val().replace(/\./g, ''); // Elimina cualquier punto
                $(this).val(dniVal); // Asigna el valor limpio
            });
        });
    </script>
    <script src="./js/Registro.js"></script>
</body>

</html>
