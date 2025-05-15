/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Función al pulsar el botón "Iniciar sesión" para ingresar a una cuenta
function InicioSesion() {
    // Se obtienen el dni y la clave
    var dni = document.getElementById("dni").value;
    var clave = document.getElementById("clave").value;

    // Si no se escribió un dni, se notifica
    if (dni.length == 0) {
        Notiflix.Notify.Failure("Debes ingresar un DNI!");
        return;
    }

    // Si no se escribió una clave, se notifica
    if (clave.length == 0) {
        Notiflix.Notify.Failure("Debes ingresar tu Contraseña!");
        return;
    }

    // Activa la pantalla de carga
    Notiflix.Loading.Circle('Cargando...');
    $.ajax({
        type: 'POST',
        url: 'Inyector.php',
        data: { Archivo: 'Ingreso.php', dni: dni, clave: clave }, // Envío sin cifrar
        dataType: 'html',
        success: function(data) {
            var Resultado = JSON.parse(data);
            Notiflix.Loading.Remove();

            if (Resultado.error) {
                Notiflix.Notify.Failure(Resultado.error);
                return;
            }

            if (Resultado.inactive) {
                Notiflix.Report.Warning(
                    '¡Cuenta inactiva!',
                    'Para ingresar a esta cuenta, debes activarla mediante el email que recibiste en tu correo electrónico al momento de registrarte. ¿No recibiste el correo? Intenta enviar nuevamente el mensaje.',
                    'Aceptar',
                    function() {
                        location.reload();
                    }
                );
                return;
            }

            if (Resultado.location) {
                // Se borran los datos almacenados si existen
                localStorage.setItem("Recordar", 0);
                localStorage.setItem("dni", false);

                // Recordar datos
                if (document.getElementById("Recordar").checked) {
                    // Si está seleccionado "Recordar dni", se almacena para usarlo la próxima vez
                    localStorage.setItem("Recordar", 1);
                    localStorage.setItem("dni", dni);
                }
               
                location.reload();
                return;
            }

            Notiflix.Notify.Failure("Acaba de ocurrir un error muy raro...");
            return;
        },
        error: function(data) {
            var Resultado = JSON.parse(data);
            Notiflix.Loading.Remove();
            Notiflix.Notify.Failure("¡No se pudo recibir una respuesta del servidor!");
            console.log(data);
            if (Resultado.error) {
                console.log(Resultado.error);
                return;
            }

            return;
        }
    });
}

$(document).ready(function() {
    /* Obtener datos almacenados */
    var Recordar = localStorage.getItem("Recordar");
    var sdni = localStorage.getItem("dni");

    if (Recordar == 1) {
        document.getElementById("dni").value = sdni; // Corregido: debe ser "dni", no "dni"
        document.getElementById("Recordar").checked = true;
    }
});

function enterKeyPressed(event) {
    if (event.keyCode == 13) {
        InicioSesion();
        return false;
    }
}
