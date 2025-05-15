<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
// Archivo: gestionar_anio.php

include 'includes/MySQL.php';

// Función para actualizar el curso y eliminar registros según los criterios
function actualizarCurso($accion, $mysqli) {
    // Solicitar confirmación de DNI del usuario en sesión
    session_start();
    $dni_sesion = $_SESSION['dni'];
    
    // Verificar que el usuario haya ingresado el DNI correcto
    $dni_ingresado = filter_input(INPUT_POST, 'dni_confirmacion', FILTER_SANITIZE_STRING);
    if ($dni_ingresado !== $dni_sesion) {
        echo "<script>alert('DNI incorrecto. Operación cancelada.');</script>";
        return;
    }
    
    // Ajuste de curso basado en la acción
    $ajusteCurso = ($accion === 'pasar') ? 1 : -1;
    
    // Actualizar cursos en la tabla `usuarios` para nivel 3
    $sql_update_usuarios = "UPDATE usuarios SET curso = curso + ($ajusteCurso) WHERE nivel = 3 AND curso BETWEEN 1 AND 6";
    $mysqli->query($sql_update_usuarios);
    
    // Eliminar usuarios que se gradúan si estamos "pasando" de año y alcanzan curso 7
    if ($accion === 'pasar') {
        $sql_eliminar_graduados = "DELETE FROM usuarios WHERE nivel = 3 AND curso > 6";
        $mysqli->query($sql_eliminar_graduados);
        $sql_eliminar_graduados2 = "DELETE FROM comedor_ver WHERE curso > 6";
        $mysqli->query($sql_eliminar_graduados2);
        
        // También eliminar los registros correspondientes en `comedor_ver`
        $sql_eliminar_comedor = "DELETE FROM comedor_ver 
                                 WHERE (nombre, apellido, curso, division) IN 
                                 (SELECT nombre, apellido, 6, division FROM usuarios WHERE nivel = 3)";
        $mysqli->query($sql_eliminar_comedor);
    }
    
    // Eliminar datos adicionales para graduados si pasan de curso a 7
    if ($accion === 'pasar') {
        $sql_eliminar_datos = "DELETE archivos, archivos_compartidos, fichajes, inscripciones, notificaciones, registro 
                               FROM archivos
                               LEFT JOIN archivos_compartidos ON archivos.dni = archivos_compartidos.dni
                               LEFT JOIN fichajes ON archivos.dni = fichajes.dni
                               LEFT JOIN inscripciones ON archivos.dni = inscripciones.dni
                               LEFT JOIN notificaciones ON archivos.dni = notificaciones.dni
                               LEFT JOIN registro ON archivos.dni = registro.dni
                               WHERE usuarios.curso > 6";
        $mysqli->query($sql_eliminar_datos);
    }
    
    // Actualizar el curso en `comedor_ver`
    $sql_update_comedor = "UPDATE comedor_ver SET curso = curso + ($ajusteCurso) WHERE curso BETWEEN 1 AND 6";
    $mysqli->query($sql_update_comedor);
    
    // Confirmación
    echo "<script>alert('Operación realizada correctamente');</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pasar_anio'])) {
        actualizarCurso('pasar', $mysqli);
    } elseif (isset($_POST['retroceder_anio'])) {
        actualizarCurso('retroceder', $mysqli);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Año Escolar</title>
</head>
<body>
    <h2>Gestión de Año Escolar</h2>
    <form method="POST" onsubmit="return confirmarAccion();">
        <label for="dni_confirmacion">Ingrese su DNI para confirmar:</label>
        <input type="text" id="dni_confirmacion" name="dni_confirmacion" required>
        
        <button type="submit" name="pasar_anio" onclick="return confirm('¿Está seguro de que desea pasar de año?')">Pasar de Año</button>
        <button type="submit" name="retroceder_anio" onclick="return confirm('¿Está seguro de que desea retroceder año?')">Retroceder Año</button>
    </form>
</body>
</html>

<script>
    function confirmarAccion() {
        const dni = document.getElementById("dni_confirmacion").value;
        if (!dni) {
            alert("Por favor, ingrese su DNI para confirmar.");
            return false;
        }
        return true;
    }
</script>
