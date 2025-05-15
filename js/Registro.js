/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Run pswmeter with options
const myPassMeter = passwordStrengthMeter({
    containerElement: '#pswmeter',
    passwordInput: '#contraseña',
    showMessage: true,
    messageContainer: '#pswmeter-message',
    messagesList: [
        '',
        'Muy fácil!',
        'Normal',
        'Segura',
        'Muy segura'
    ],

    height: 6,
    borderRadius: 0,
    pswMinLength: 8,
    colorScore1: '#FF0000',
    colorScore2: '#fc7703',
    colorScore3: '#36ba2f',
    colorScore4: '#2f97ba'
});

// Función al pulsar el botón "Registrarse" para crear una cuenta nueva
function Registro() {
    // Se obtienen los datos ingresados
    var dni = document.getElementById("dni").value;
    var nombre = document.getElementById("nombre").value;
    var apellido = document.getElementById("apellido").value;
    var contraseña = document.getElementById("contraseña").value;
    var Ccontraseña = document.getElementById("Ccontraseña").value;
    var curso = document.getElementById("curso").value;
    var division = document.getElementById("division").value;
    var Seguridad = myPassMeter.getScore();
    var Check = document.getElementById("checkterm").checked;

    // Variable para verificar si completó todos los campos
    var Incompleto = false;

    if (dni.length == 0) {
        Incompleto = true;
        document.getElementById("dni").style.borderColor = "LightCoral";
        setTimeout(function() { document.getElementById("dni").style.borderColor = "LightGray"; }, 3000);
    }

    if (nombre.length == 0) {
        Incompleto = true;
        document.getElementById("nombre").style.borderColor = "LightCoral";
        setTimeout(function() { document.getElementById("nombre").style.borderColor = "LightGray"; }, 3000);
    }

    if (apellido.length == 0) {
        Incompleto = true;
        document.getElementById("apellido").style.borderColor = "LightCoral";
        setTimeout(function() { document.getElementById("apellido").style.borderColor = "LightGray"; }, 3000);
    }

    // Si no seleccionó un curso
    if (curso === "") {
        Incompleto = true;
        document.getElementById("curso").style.borderColor = "LightCoral";
        setTimeout(function() { document.getElementById("curso").style.borderColor = "LightGray"; }, 3000);
        Notiflix.Notify.Failure("Debe seleccionar un curso.");
    }

    // Si no seleccionó una división
    if (division === "") {
        Incompleto = true;
        document.getElementById("division").style.borderColor = "LightCoral";
        setTimeout(function() { document.getElementById("division").style.borderColor = "LightGray"; }, 3000);
        Notiflix.Notify.Failure("Debe seleccionar una división.");
    }

    if (contraseña.length == 0) {
        Incompleto = true;
        document.getElementById("contraseña").style.borderColor = "LightCoral";
        setTimeout(function() { document.getElementById("contraseña").style.borderColor = "LightGray"; }, 3000);
    }

    if (Ccontraseña.length == 0) {
        Incompleto = true;
        document.getElementById("Ccontraseña").style.borderColor = "LightCoral";
        setTimeout(function() { document.getElementById("Ccontraseña").style.borderColor = "LightGray"; }, 3000);
    }

    if (Incompleto) {
        Notiflix.Notify.Failure("¡Debes completar todos los campos correctamente!");
        return;
    }

    if (Ccontraseña != contraseña) {
        Notiflix.Notify.Failure("Las contraseñas ingresadas son diferentes!");
        document.getElementById("contraseña").value = "";
        document.getElementById("Ccontraseña").value = "";
        return;
    }

    if (Seguridad <= 1) {
        Notiflix.Notify.Failure("¡La contraseña es muy insegura!");
        return;
    }

    if (!Check) {
        Notiflix.Notify.Failure("Debe Aceptar los Términos y condiciones!");
        return;
    }

    // Activa la pantalla de carga
    Notiflix.Loading.Circle('Cargando...');
    $.ajax({
        type: 'POST',
        url: 'Inyector.php',
        data: { Archivo: 'Registro.php', dni: dni, contraseña: contraseña, nombre: nombre, apellido: apellido, curso: curso, division: division },
        dataType: 'html',
        success: function(data) {
            console.log(data);
            var Resultado = JSON.parse(data);
            Notiflix.Loading.Remove();

            if (Resultado.error) {
                Notiflix.Notify.Failure(Resultado.error);
                return;
            }

            if (Resultado.success) {
                document.getElementsByClassName('login-page')[0].style.display = "none";
                Notiflix.Report.Success(
                    '¡Registrado con éxito!',
                    'Tu usuario fue registrado correctamente.',
                    'Aceptar',
                    function() {
                        document.location = "./";
                    }
                );
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}


// Funcion que se ejecutará al escribir caracteres en el campo 'contraseña'
document.getElementById('contraseña').addEventListener('input', function() {
    // Obtiene lo escrito en el campo
    Valor = event.target.value;

    // Obtiene los elementos que mostrarán la seguridad de la contraseña
    var Barra = document.getElementById("pswmeter");
    var Texto = document.getElementById("pswmeter-message");

    // Chequea si hay más de un caracter escrito
    if (Valor.length > 0) {
        // Chequea si la barra está oculta
        if (Barra.style.display == 'none') {
            // Muestra los elementos
            Barra.style.display = 'block';
            Texto.style.display = 'block';
        }
    } else {
        // Chequea si la barra está visible
        if (Barra.style.display == 'block') {
            // Oculta los elementos
            Barra.style.display = 'none';
            Texto.style.display = 'none';
        }
    }
});
