<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include_once 'includes/Funciones.php';
session_start();
// Se chequea si hay una función especial
if (isset($_GET['Recovery'])) {
	include 'Scripts.php';
	die();
}
// Verificar si el usuario está logueado
if (!isLogged()) {
    // Se chequea si hay un enlace
    if (isset($_GET['Enlace'])) {
        $Enlace = $_GET['Enlace'];
        $Enlace = filter_input(INPUT_GET, 'Enlace', FILTER_SANITIZE_STRING);
        if ($Enlace == 'Registro') {
            // Se lo envía a la página de registro
            include 'vistas/Registro.php';
            die();
        }
        // Se chequea si quiere acceder a 'Olvidé mi clave'
		elseif ($Enlace == 'Recuperar') {
			// Se lo envía a la pagina para recuperación de claves
			include 'vistas/recuperar.php';
			die();
		}elseif ($Enlace == 'Ingreso') {
            include 'vistas/Ingreso.php';
            die();
        }
        // De otra forma, si los enlaces son incorrectos, se carga el ingreso
        
    } else {
        include 'vistas/Ingreso.php';
        die();
    }
} else{
// Si el usuario está logueado, carga la vista principal
$Nivel = isLogged();
include 'vistas/principal.php';
}
?>
