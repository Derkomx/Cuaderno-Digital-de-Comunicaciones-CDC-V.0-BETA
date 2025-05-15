<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
	ob_start();
	include_once 'functions.php';

	session_start();

	$_SESSION = array();

	$params = session_get_cookie_params();

	setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

	session_destroy();
	
	echo json_encode(array("location" => "index.php"));
	header('Location: ../index.php');
	exit();
?>
