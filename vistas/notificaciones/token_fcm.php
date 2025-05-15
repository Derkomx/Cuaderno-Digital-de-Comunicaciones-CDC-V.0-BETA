<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
    $dni = $_SESSION['dni'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vincular Dispositivo</title>
</head>
<body>
    <div class="container d-flex flex-column align-items-center justify-content-center vh-100">
        <button id="vincularBtn" class="btn btn-primary mb-3">Vincular dispositivo</button>
        <button id="probarbtn" class="btn btn-secondary">Probar Notificación</button>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "",
            authDomain: "",
            projectId: "",
            storageBucket: "",
            messagingSenderId: "",
            appId: "",
            measurementId: ""
        };

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        function solicitarPermiso() {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    console.log('Permiso concedido, obteniendo token...');
                    obtenerToken();
                } else {
                    Notiflix.Report.Failure('Permiso denegado', 'Activa las notificaciones en la configuración del navegador.', 'Aceptar');
                }
            }).catch(error => {
                console.error('Error al solicitar permiso de notificaciones:', error);
            });
        }

        function obtenerToken() {
            navigator.serviceWorker.register("vistas/notificaciones/sw.js").then((registration) => {
                getToken(messaging, {
                    serviceWorkerRegistration: registration,
                    vapidKey: ''
                }).then((currentToken) => {
                    if (currentToken) {
                        guardarToken(currentToken);
                    }
                }).catch((err) => {
                    console.log('Ocurrió un error al obtener el token. ', err);
                });
            });
        }

        function guardarToken(token) {
            var dni = '<?php echo $dni ?>';
            $.ajax({
                type: 'POST',
                url: 'Inyector.php',
                data: {
                    Archivo: 'guardar_token.php',
                    token: token,
                    dni: dni,
                },
                success: function(response) {
                    let Resultado = JSON.parse(response);
                    if (Resultado.success) {
                        Notiflix.Report.Success('¡Éxito!', '<center>Dispositivo agregado con éxito</center>', 'Aceptar');
                    } else {
                        Notiflix.Report.Failure('Error', 'Error al vincular dispositivo.', 'Aceptar');
                    }
                },
                error: function() {
                    Notiflix.Report.Failure('Error', 'Error en la solicitud AJAX.', 'Aceptar');
                }
            });
        }

        document.getElementById('vincularBtn').addEventListener('click', solicitarPermiso);

        document.getElementById('probarbtn').addEventListener('click', function() {
            $.ajax({
                type: 'POST',
                url: 'Inyector.php',
                data: {
                    Archivo: 'enviar_notificacion.php',
                    probar_notificacion: true,
                    notificaciontitulo: 'Prueba de Notificación'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Notiflix.Notify.Success('Notificación enviada con éxito.');
                    } else {
                        Notiflix.Report.Failure('Error', response.message, 'Aceptar');
                    }
                },
                error: function() {
                    Notiflix.Report.Failure('Error', 'Error en la solicitud AJAX.', 'Aceptar');
                }
            });
        });
    </script>
</body>
</html>

