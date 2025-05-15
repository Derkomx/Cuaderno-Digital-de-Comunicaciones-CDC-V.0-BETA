<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include 'includes/MySQL.php';

if (isset($_POST['idArchivo'])) {
    $idArchivo = $_POST['idArchivo'];
    $mensajes = []; // Array para almacenar los mensajes de depuración

    // Llamar a la función que obtendrá los usuarios compartidos
    $usuarios = obtenerUsuariosCompartidos($idArchivo, $mysqli, $mensajes);

    if (!empty($usuarios)) {
        echo json_encode(['success' => true, 'usuarios' => $usuarios, 'mensajes' => $mensajes]);
    } else {
        echo json_encode(['success' => false, 'usuarios' => [], 'mensajes' => $mensajes]);
    }
} else {
    echo json_encode(['success' => false, 'usuarios' => [], 'mensajes' => ['ID de archivo no proporcionado']]);
}

function obtenerUsuariosCompartidos($idArchivo, $mysqli, &$mensajes) {
    $sql = "SELECT u.apellido, u.nombre, u.dni
            FROM archivos_compartidos ac
            INNER JOIN usuarios u ON ac.dni_destino = u.dni
            WHERE ac.id_archivo = $idArchivo";
    $resultado = $mysqli->query($sql);

    if ($resultado === false) {
        $mensajes[] = "Error en la consulta: " . $mysqli->error;
        return [];
    } else {
        $mensajes[] = "Consulta ejecutada correctamente.";
    }

    $usuarios = array();
    if ($resultado->num_rows > 0) {
        while ($usuario = $resultado->fetch_assoc()) {
            $usuarios[] = $usuario;
        }
    } else {
        $mensajes[] = "No se encontraron usuarios compartidos para este archivo.";
    }

    return $usuarios;
}
?>
