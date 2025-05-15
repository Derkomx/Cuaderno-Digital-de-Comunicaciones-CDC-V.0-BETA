<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
include 'includes/MySQL.php'; // Conexión a la base de datos

$usuarios = [];

if (isset($_GET['busqueda'])) {
    $busqueda = trim($_GET['busqueda']);

    $query = "SELECT u.dni, u.nombre, u.apellido, s.fecha_vencimiento, s.estado 
              FROM usuarios u
              LEFT JOIN suscripciones_activas s ON u.dni = s.dni
              WHERE u.dni LIKE ? OR CONCAT(u.nombre, ' ', u.apellido) LIKE ?";
    
    $likeBusqueda = "%$busqueda%";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $likeBusqueda, $likeBusqueda);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dni']) && isset($_POST['accion'])) {
    $dni = trim($_POST['dni']);
    $accion = $_POST['accion'];
    $fecha_actual = date("Y-m-d");

    if ($accion == "alta" && isset($_POST['meses'])) {
        $meses = intval($_POST['meses']);
        if ($meses <= 0) {
            echo "<script>alert('Cantidad de meses inválida'); window.location='index.php?Seccion=listadomutual';</script>";
            exit;
        }

        $stmt = $mysqli->prepare("SELECT fecha_vencimiento FROM suscripciones_activas WHERE dni = ?");
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $stmt->bind_result($fecha_vencimiento);
        $stmt->fetch();
        $stmt->close();

        if ($fecha_vencimiento && strtotime($fecha_vencimiento) > strtotime($fecha_actual)) {
            $nueva_fecha_vencimiento = date("Y-m-d", strtotime("+$meses months", strtotime($fecha_vencimiento)));
        } else {
            $nueva_fecha_vencimiento = date("Y-m-d", strtotime("+$meses months", strtotime($fecha_actual)));
        }

        $stmt = $mysqli->prepare("INSERT INTO suscripciones_movimientos (dni, fecha_movimiento, meses_agregados, nueva_fecha_vencimiento) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $dni, $fecha_actual, $meses, $nueva_fecha_vencimiento);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("REPLACE INTO suscripciones_activas (dni, fecha_inicio, fecha_vencimiento, estado) VALUES (?, ?, ?, 'activo')");
        $stmt->bind_param("sss", $dni, $fecha_actual, $nueva_fecha_vencimiento);
        $stmt->execute();
        $stmt->close();
    }

    if ($accion == "baja") {
        $stmt = $mysqli->prepare("DELETE FROM suscripciones_activas WHERE dni = ?");
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $stmt->close();
    }
    echo "<script>window.location='index.php?Seccion=listadomutual';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
   <script>
    $(document).ready(function () {
        $("#tablaUsuarios").DataTable({
            "dom": "t", // Oculta búsqueda y selector de cantidad de registros
            "paging": false // Deshabilita la paginación
        });

        $(".btn-baja").click(function (e) {
            if (!confirm("¿Está seguro de eliminar la suscripción?")) {
                e.preventDefault();
            }
        });
    });
</script>

</head>
<body>
    <h2>Buscar Suscripción</h2>
    <form method="GET" action="index.php">
        <input type="hidden" name="Seccion" value="listadomutual">
        <input type="text" name="busqueda" placeholder="Ingrese DNI o Nombre">
        <button type="submit">Buscar</button>
    </form>
    
    <?php if (!empty($usuarios)) : ?>
        <table id="tablaUsuarios" border="1">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Estado</th>
                    <th>Vigencia</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario) : ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                        <td><?= htmlspecialchars($usuario['dni']) ?></td>
                        <td><?= htmlspecialchars($usuario['estado'] ?? 'No registrado') ?></td>
                        <td><?= htmlspecialchars($usuario['fecha_vencimiento'] ?? 'N/A') ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="dni" value="<?= htmlspecialchars($usuario['dni']) ?>">
                                <?php if (empty($usuario['estado'])) : ?>
                                    <label>Meses: <input type="number" name="meses" min="1" required></label>
                                    <button type="submit" name="accion" value="alta">Activar</button>
                                <?php else : ?>
                                    <label>Meses: <input type="number" name="meses" min="1"></label>
                                    <button type="submit" name="accion" value="alta">Ampliar</button>
                                    <button type="submit" name="accion" value="baja" class="btn-baja">Eliminar</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No se encontraron resultados.</p>
    <?php endif; ?>
</body>
</html>
