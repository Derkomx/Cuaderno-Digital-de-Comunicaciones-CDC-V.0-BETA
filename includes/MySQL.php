<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
    // Archivo: MySQL.php
    // Propósito: Maneja la conexión a MySQL

    // Incluye una sola vez el archivo de configuración
    include_once 'Configuracion.php';

    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

    if ($mysqli->connect_error) {
        // Mensaje de error más claro y redirección a página de error
        header("Location: ../error.php?err=No se pudo conectar a la base de datos.");
        exit();
    }

    // Forzar el uso de UTF-8 para evitar problemas de codificación
    if (!$mysqli->set_charset("utf8")) {
        header("Location: ../error.php?err=Error al configurar la codificación de caracteres.");
        exit();
    }
?>
