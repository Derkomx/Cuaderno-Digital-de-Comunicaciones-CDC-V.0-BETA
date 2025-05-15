<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos CET N° 3</title>
    <link rel="stylesheet" href="librerias\Bootstrap\css\bootstrap.css">
    <!-- Asegúrate de enlazar Bootstrap si es necesario -->
    <style>
    /* CSS para dar tamaño uniforme a las imágenes */
    .custom-img-size {
        width: 376px;
        height: 211px;
    }
    </style>
</head>

<body>

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="dist/img/logo.webp" alt="AdminLTELogo" height="60" width="60">
    </div>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1></h1>
                </div>
            </div>
        </div>
    </section>

    <br>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Proyectos CET N° 3</h3>
                        </div>
                        <div class="card-body">
                            <!-- Fila 1 -->
                            <div class="row">
                                <!-- Bloque 2 -->
                                <div class="col-sm-4 mb-4">
                                    <div class="position-relative">
                                        <?php
                                            echo '<a href="index.php?Seccion=cuaderno1" class="d-block">';
                                        ?>
                                        <img src="media/cuaderno.webp" alt="Cuaderno de Comunicaciones"
                                            class="img-fluid custom-img-size" />
                                        <div class="ribbon-wrapper ribbon-xl">
                                            <div class="ribbon bg-secondary">Cuaderno de Comunicaciones</div>
                                        </div>
                                        </a>
                                    </div>
                                </div>
                                <!-- Bloque 3 -->
                                <div class="col-sm-4 mb-4">
                                    <div class="position-relative">
                                        <?php
                                          if ($_SESSION['nivel'] == 4 || $_SESSION['nivel'] == 8 || $_SESSION['nivel'] == 7) {
                                            echo '<a href="index.php?Seccion=comedoraldia3" class="d-block">';
                                          } else {
                                            echo '<a href="index.php?Seccion=comedoraldia1" class="d-block">';
                                          }
                                        ?>
                                        <img src="media/comedor.webp" alt="Comedor al Día"
                                            class="img-fluid custom-img-size" />
                                        <div class="ribbon-wrapper ribbon-xl">
                                            <div class="ribbon bg-secondary">Comedor al Día</div>
                                        </div>
                                        </a>
                                    </div>
                                </div>
                            </div> <!-- Fin Fila 1 -->
                        </div> <!-- Fin card-body -->
                    </div> <!-- Fin card -->
                </div> <!-- Fin col-12 -->
            </div> <!-- Fin row -->
        </div> <!-- Fin container-fluid -->
    </section>

</body>

</html>
