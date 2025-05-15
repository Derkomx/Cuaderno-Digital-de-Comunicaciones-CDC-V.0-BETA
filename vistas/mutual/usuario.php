<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include 'includes/MySQL.php'; // Conexión a la base de datos

session_start();

if (!isset($_SESSION['dni'])) {
    echo "Acceso no autorizado.";
    exit;
}

$dni = $_SESSION['dni'];

// Obtener datos de la suscripción desde suscripciones_activas
$query = "SELECT u.apellido, u.nombre, s.fecha_vencimiento, s.estado
          FROM usuarios u 
          LEFT JOIN suscripciones_activas s ON u.dni = s.dni
          WHERE u.dni = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $dni);
$stmt->execute();
$stmt->bind_result($apellido, $nombre, $fecha_vencimiento, $estado);
$stmt->fetch();
$stmt->close();

if (!$apellido) {
    echo "Usuario no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnet de Asociación</title>
    <style>
        .posicion {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
            margin: 0;
        }
        .credencial {
            color: #00b4e1;
            font-family: "Source Sans Pro", sans-serif;
            background-color: #002e4c;
            width: 8.56cm;
            height: 5.398cm;
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            z-index: 0;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .heading {
            text-align: left;
            flex: 1;
            padding-left: 10px;
        }
        .heading .logo {
            font-size: 1cm;
            font-weight: bold;
        }
        .heading .subtitle {
            font-size: 2.6mm;
            font-weight: 600;
            color: #009cc3;
            list-style: none;
            padding: 0;
        }
        .foto {
            height: 3cm;
            width: 2.66cm;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-right: 10px;
        }
        .foto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .datos {
            color: #b0bbc8;
            font-size: 0.29cm;
            font-weight: 600;
            list-style: none;
            padding: 0;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php if ($estado === "activo"): ?>
    <div class="posicion">
        <div class="credencial">
            <div class="heading">
                <h1 class="logo"><strong>Mutual CET 3</strong></h1>
                <ul class="subtitle">
                    <li><?= htmlspecialchars($dni) ?></li>
                    <li><?= htmlspecialchars($nombre) . " " . htmlspecialchars($apellido) ?></li>
                    <li>Estado: <?= htmlspecialchars($estado) ?></li>
                    <li>Vence: <?= htmlspecialchars($fecha_vencimiento) ?></li>
                </ul>
            </div>
            <div class="foto">
                <img src="media/logo.webp">
            </div>
        </div>
    </div>
    <?php else: ?>
        <p class="mensaje">No tiene dada de alta la suscripción en la mutual.</p>
    <?php endif; ?>
</body>
</html>
