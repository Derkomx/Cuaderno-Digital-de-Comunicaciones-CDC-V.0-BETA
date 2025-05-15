/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/

function mostrarFormularioSubida() {
    document.getElementById('dropzone-container').style.display = 'block';

    // Configurar Dropzone
    Dropzone.options.myDropzone = {
        paramName: 'file', // El nombre del parámetro que será enviado al servidor
        maxFilesize: 2, // Tamaño máximo del archivo en MB
        acceptedFiles: 'image/*,application/pdf', // Tipos de archivos aceptados
        dictDefaultMessage: 'Arrastra tus archivos aquí para subirlos',
        accept: function(file, done) {
            if (file.name == "justinbieber.jpg") {
              done("Naha, you don't.");
            }
            else { done(); }
          }
        //init: function() {
        //    this.on('sending', function(file) {
        //        Notiflix.Loading.Circle('Subiendo archivo...');
        //    });

        //    this.on('success', function(file, response) {
        //        Notiflix.Loading.Remove(); // Eliminar el indicador de carga
        //        Notiflix.Notify.Success('Archivo subido exitosamente.');
        //        setTimeout(function() {
        //            location.reload();
        //        }, 2000); // Recargar la página después de 2 segundos
        //    });

        //    this.on('error', function(file, response) {
        //        Notiflix.Loading.Remove(); // Eliminar el indicador de carga
        //        Notiflix.Notify.Failure('Error al subir el archivo.');
        //    });
        //}
    };
}

function confirmarEliminar(id) {
    Notiflix.Confirm.Show(
        'Eliminar Archivo',
        '¿Estás seguro de que deseas eliminar este archivo?',
        'Sí', 'No',
        function() {
            window.location.href = '?Seccion=Drive&eliminar=true&id=' + id;
        },
        function() {
            Notiflix.Notify.Failure('Eliminación cancelada.');
        }
    );
}
