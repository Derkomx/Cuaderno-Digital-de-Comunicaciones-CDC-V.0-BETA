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
                <input id="dni" name="dni" type="text" maxlength="19" placeholder="DNI" autocomplete="off" class="form-control" inputmode="numeric"/>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-0">
                <input id="nombre" name="nombre" type="text" placeholder="Nombre" autocomplete="off" class="form-control"/>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-0">
                <input id="apellido" name="apellido" type="text" placeholder="Apellido" autocomplete="off" class="form-control"/>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>

            <!-- Campo para seleccionar rol -->
            <div class="input-group mb-0">
                <select id="rol" name="rol" class="form-control">
                    <option value="">Selecciona el rol</option>
                    <option value="2">Tutor</option>
                    <option value="3">Usuario</option>
                    <option value="4">Admin</option>
                    <option value="5">Profesor</option>
                    <option value="6">Porteros</option>
                    <option value="7">Preceptores</option>
                    <option value="8">Cocina</option>
                    <option value="9">Musica</option>
                </select>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user-tag"></span>
                    </div>
                </div>
            </div>

            <form class="input-group mb-0">
                <input id="contraseña" name="contraseña" type="password" maxlength="19" placeholder="Contraseña" autocomplete="off" class="form-control"/>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </form>
            <!-- Información de seguridad de contraseña -->
            <div style="display: none;" id="pswmeter" class="mt-3"></div>
            <div style="display: none; padding-top: 8px; padding-bottom: 6px; text-align: center;" id="pswmeter-message" class="mt-3"></div>

            <form class="input-group mb-0">
                <input id="Ccontraseña" name="Ccontraseña" type="password" maxlength="19" placeholder="Confirmar contraseña" autocomplete="off" class="form-control"/>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </form> 

            <button type="submit" onclick="Registro()">Registrarme</button>
            <p></p>
            <div class="tab-custom-content"></div>
        </div>
    </div>
    <script src="./js/Registro2.js"></script>
</body>
</html>
