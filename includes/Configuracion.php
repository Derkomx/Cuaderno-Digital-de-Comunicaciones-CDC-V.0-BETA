<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
    // Archivo: Configuracion.php
    // Propósito: Definir las configuraciones del sistema

    // Configuración de MySQL
    define("HOST", "");          // Servidor
    define("USER", "");               // Usuario
    define("PASSWORD", ""); // Contraseña
    define("DATABASE", "");          // Base de datos

    // Configuraciones adicionales
    define("CAN_REGISTER", "any");
    define("DEFAULT_ROLE", "member");

    // SSL habilitado si está disponible (para entornos en producción)
    define("SECURE", false);              // Cambiar a true en producción para SSL
?>
