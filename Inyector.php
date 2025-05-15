<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Función para sanitizar el nombre del archivo y permitir rutas controladas
function sanitizeFileName($file) {
    // Elimina cualquier "../" para prevenir acceso a carpetas fuera de "includes/"
    $file = preg_replace('/\.\.\//', '', $file);
    
    // Solo permite letras, números, guiones bajos, guiones, puntos y barras para subcarpetas
    return preg_replace('/[^a-zA-Z0-9_\-\.\/]/', '', $file);
}

// Lista de archivos permitidos
$allowedFiles = [
    'Ingreso.php',
    'Registro.php',
    'Recuperar.php',
    'Registroadm.php',
    'listados_com.php',
    'dejar_compartir.php',
    'guardar_evento24.php',
    'cargar_eventos24.php',
    'eliminar_eventos24.php',
    'comedoraldia_inscribirse.php',
    'comedoraldia_cancelar.php',
    'Registrotutor.php',
    'vincular.php',
    'chat_enviar.php',
    'chat_cargar.php',
    'actualizar_email.php',
    'guardar_token.php',
    'enviar_notificacion.php',
    // Agrega aquí los nombres de archivos válidos
];

// Se chequea si la ejecución es mediante POST (Ajax)
if (isset($_POST["Archivo"])) {
    $archivo = sanitizeFileName($_POST["Archivo"]);

    // Verifica que el archivo esté en la lista blanca y tenga la extensión .php
    if (in_array($archivo, $allowedFiles) && pathinfo($archivo, PATHINFO_EXTENSION) === 'php') {
        $filePath = "includes/" . $archivo;

        // Chequea que el archivo exista antes de incluirlo
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            http_response_code(404); // Archivo no encontrado
        }
    } else {
        http_response_code(403); // Acceso prohibido
    }
    exit();
}

// Procesamiento de php://input para solicitudes JSON
$InputContent = file_get_contents('php://input');
if ($InputContent) {
    // Decodifica el JSON
    $JSONContent = json_decode($InputContent, true);
    $archivo = sanitizeFileName($JSONContent['Archivo']);

    // Verifica que el archivo esté en la lista blanca y tenga la extensión .php
    if (in_array($archivo, $allowedFiles) && pathinfo($archivo, PATHINFO_EXTENSION) === 'php') {
        $filePath = "includes/" . $archivo;

        // Chequea que el archivo exista antes de incluirlo
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            http_response_code(404); // Archivo no encontrado
        }
    } else {
        http_response_code(403); // Acceso prohibido
    }
    exit();
}
?>
