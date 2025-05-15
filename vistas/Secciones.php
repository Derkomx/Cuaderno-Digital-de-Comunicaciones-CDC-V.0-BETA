<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*
	Archivo: Secciones.php
	Autor: Armas, Juan Manuel
	Proposito: Funcionamiento de las secciones aparte del menú
	Fecha: 07/01/2021
	Ultima edición: 08/01/2021
*/

// Array limpio
$Secciones = [];
// Secciones de nivel 2 (Tutor)
$Secciones[2] = [
	"registrotutor" => "tutor/registro.php",
	"vinculardni" => "tutor/vincular.php",
	"vinculados" => "tutor/vinculados.php",
	"Email" => "tutor/email.php",
	"cuaderno1" => "cuadernoestudiante/index.html",
	"cuaderno2" => "cuadernoestudiante/notificaciones.php",
	"cuaderno3" => "cuadernoestudiante/eventos.php",
	"token" => "notificaciones/token_fcm.php",
];
// Secciones de nivel 3 (Alumno)
$Secciones[3] = [
	"comedoraldia1" => "comedoraldia/dias.html",
	"comedoraldia2" => "comedoraldia/horario.html",
	"cuaderno1" => "cuadernoestudiante/index.html",
	"cuaderno2" => "cuadernoestudiante/notificaciones.php",
	"cuaderno3" => "cuadernoestudiante/eventos.php",
	"token" => "notificaciones/token_fcm.php",
];
// Secciones de nivel 4 (ADMIN)
$Secciones[4] = [
    "prueba" => "prueba/prueba.php",
    "prueba2" => "prueba/prueba2.php",
	"comedoraldia1" => "comedoraldia/dias.html",
	"comedoraldia2" => "comedoraldia/horario.html",
	"cuaderno1" => "cuadernoestudiante/index.html",
	"cuaderno2" => "cuadernoestudiante/notificaciones.php",
	"cuaderno3" => "cuadernoestudiante/eventos.php",
	//funciones comedor
	"comedoraldia3" => "cocina/comedoraldia/comedoraldia.php",
	//funciones administrador
	"crearusuario" => "admin/usuarionuevo.php",
	"usuarios"=> "admin/usuarios.php",
	"usuarioscomedor"=> "admin/usuarioscomedor.php",
	"pasardeaño"=> "admin/pasardeaño.php",
	"datos"=> "admin/datos.php",
	"admvertutor1" => "admin/vertutor1.php",
	"admvertutor2" => "admin/vertutor2.php",
	//funciones cuaderno envio
	"cuaderno11" => "cuadernoprece/index.html",
	"cuaderno12" => "cuadernoprece/notificaciones.php",
	"cuaderno13" => "cuadernoprece/eventos.php",
	"cuaderno22" => "cuadernoprece/alumno.php",
	"cuaderno31" => "cuadernoprece/elegir.php",
	"cuaderno32" => "cuadernoprece/notas.php",
	//funciones preceptor
	"registrotutor" => "tutor/registro.php",
	//funciones tutor
	"vinculardni" => "tutor/vincular.php",
	"vinculados" => "tutor/vinculados.php",
	"Email" => "tutor/email.php",
	"token" => "notificaciones/token_fcm.php",
	//asistencia
	"asistencia" => "asistencia/asistenciab.php",
	"asistencia2" => "asistencia/asistenciac.php",
	"verasistencia" => "asistencia/seleccionar.php",
	"verasistencia2" => "asistencia/ver_asistencia.php",
	//Mutual
	"listadomutual" => "mutual/listado.php",
	"carnetmutual" => "mutual/usuario.php",
];
// Secciones de nivel 5 (PROFESOR)
$Secciones[5] = [
	"comedoraldia1" => "comedoraldia/dias.html",
	"comedoraldia2" => "comedoraldia/horario.html",
	"cuaderno1" => "cuadernoestudiante/index.html",
	"cuaderno2" => "cuadernoestudiante/notificaciones.php",
	"cuaderno3" => "cuadernoestudiante/eventos.php",
	//funciones comedor
	"comedoraldia3" => "cocina/comedoraldia/comedoraldia.php",
	//funciones cuaderno envio
	"cuaderno11" => "cuadernoprece/index.html",
	"cuaderno12" => "cuadernoprece/notificaciones.php",
	"cuaderno13" => "cuadernoprece/eventos.php",
	"cuaderno22" => "cuadernoprece/alumno.php",
	"cuaderno31" => "cuadernoprece/elegir.php",
	"cuaderno32" => "cuadernoprece/notas.php",
	//funciones tutor
	"registrotutor" => "tutor/registro.php",
	"vinculardni" => "tutor/vincular.php",
	"vinculados" => "tutor/vinculados.php",
	"Email" => "tutor/email.php",
	"token" => "notificaciones/token_fcm.php",
];
// Secciones de nivel 7 (PRECEPTOR)
$Secciones[7] = [
	"comedoraldia1" => "comedoraldia/dias.html",
	"comedoraldia2" => "comedoraldia/horario.html",
	"cuaderno1" => "cuadernoestudiante/index.html",
	"cuaderno2" => "cuadernoestudiante/notificaciones.php",
	"cuaderno3" => "cuadernoestudiante/eventos.php",
	//funciones comedor
	"comedoraldia3" => "cocina/comedoraldia/comedoraldia.php",
	//funciones cuaderno envio
	"cuaderno11" => "cuadernoprece/index.html",
	"cuaderno12" => "cuadernoprece/notificaciones.php",
	"cuaderno13" => "cuadernoprece/eventos.php",
	"cuaderno22" => "cuadernoprece/alumno.php",
	"cuaderno31" => "cuadernoprece/elegir.php",
	"cuaderno32" => "cuadernoprece/notas.php",
	//funciones tutor
	"registrotutor" => "tutor/registro.php",
	"vinculardni" => "tutor/vincular.php",
	"vinculados" => "tutor/vinculados.php",
	"Email" => "tutor/email.php",
	"token" => "notificaciones/token_fcm.php",
			//asistencia
	"asistencia" => "asistencia/asistenciab.php",
	"asistencia2" => "asistencia/asistenciac.php",
	"verasistencia" => "asistencia/seleccionar.php",
	"verasistencia2" => "asistencia/ver_asistencia.php",
	// ver tutor
	"admvertutor1" => "admin/vertutor1.php",
	"admvertutor2" => "admin/vertutor2.php",
];
// Secciones de nivel 8 (COCINA)
$Secciones[8] = [
	"calendariocet3" => "calendariocet3/index.html",
	"comedoraldia1" => "comedoraldia/dias.html",
	"comedoraldia2" => "comedoraldia/horario.html",
	"comedoraldia3" => "cocina/comedoraldia/comedoraldia.php",

	"token" => "notificaciones/token_fcm.php",
];
// Secciones de nivel 9 (MUSICA)
$Secciones[9] = [
	"token" => "notificaciones/token_fcm.php",
		//Mutual
	"listadomutual" => "mutual/listado.php",
	"carnetmutual" => "mutual/usuario.php",
];

?>
