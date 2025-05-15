<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
//trae el lvl de usuario
$lvlusr = $_SESSION['nivel'];
//muestra errores en pantalla si usuario es lvl administrador
if ($lvlusr == 4) {
	error_reporting(0);
}
$id_usuario = $_SESSION['dni'];
// Menú basado en el nivel de usuario
$MenuArr[2] = [
	"Inicio" => [
		"Icono" => "nav-icon fas fa-home",
		"Seccion" => "Inicio",
		"Archivo" => "Inicio.php",
	],
	"Vincular DNI" => [
		"Icono" => "nav-icon fas fa-user",
		"Seccion" => "vinculardni",
		"Archivo" => "",
	],
	"Vinculados" => [
		"Icono" => "nav-icon fas fa-user",
		"Seccion" => "vinculados",
		"Archivo" => "",
	],
	"Email" => [
		"Icono" => "nav-icon fas fa-user",
		"Seccion" => "Email",
		"Archivo" => "",
	],
];
$MenuArr[3] = [
	"Inicio" => [
		"Icono" => "nav-icon fas fa-home",
		"Seccion" => "Inicio",
		"Archivo" => "Inicio.php",
	],
];
$MenuArr[4] = [
	"Inicio" => [
		"Icono" => "nav-icon fas fa-home",
		"Seccion" => "Inicio",
		"Archivo" => "Inicio.php",
	],
	"Administrador" => [
		"Icono" => "nav-icon fa fa-user",
		"Tipo" => "Sub-menu",
		"Menu" => [
			"Estadisticas" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "datos",
				"Archivo" => "datos.php",
			],
			"Crear Usuario" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "crearusuario",
				"Archivo" => "Perfil.php",
			],
			"Lista de Usuarios" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "usuarios",
				"Archivo" => "Perfil.php",
			],
			"Lista de Usuarios Comedor" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "usuarioscomedor",
				"Archivo" => "Perfil.php",
			],
			"Pasar de año" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "pasardeaño",
				"Archivo" => "Perfil.php",
			],
			"Ver Tutor" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "admvertutor1",
				"Archivo" => "Perfil.php",
			],
		],
	],
	"Funciones Tutor" => [
		"Icono" => "nav-icon fa fa-user",
		"Tipo" => "Sub-menu",
		"Menu" => [
			"Vincular DNI" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "vinculardni",
				"Archivo" => "",
			],
			"Vinculados" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "vinculados",
				"Archivo" => "",
			],
			"Email" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "Email",
				"Archivo" => "",
			],
		],
	],
	"Funciones Prece/Profe" => [
		"Icono" => "nav-icon fa fa-user",
		"Tipo" => "Sub-menu",
		"Menu" => [
			"Ver Cuaderno" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "cuaderno31",
				"Archivo" => "",
			],
			"Enviar Nota" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "cuaderno11",
				"Archivo" => "",
			],
			"Asistencia" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "asistencia",
				"Archivo" => "Perfil.php",
			],
			"Ver Asistencia" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "verasistencia",
				"Archivo" => "Perfil.php",
			],
				"Registro Tutor" => [
        		"Icono" => "nav-icon fa fa-caret-right",
        		"Seccion" => "registrotutor",
        		"Archivo" => "",
        	],
		],
	],
];
$MenuArr[5] = [
	"Inicio" => [
		"Icono" => "nav-icon fas fa-home",
		"Seccion" => "Inicio",
		"Archivo" => "Inicio.php",
	],
		"Registro Tutor" => [
		"Icono" => "nav-icon fas fa-user",
		"Seccion" => "registrotutor",
		"Archivo" => "",
	],
	"Ver Cuaderno" => [
				"Icono" => "nav-icon fa fa-user",
				"Seccion" => "cuaderno31",
				"Archivo" => "",
			],
	"Enviar Nota" => [
		"Icono" => "nav-icon fa fa-user",
		"Seccion" => "cuaderno11",
		"Archivo" => "",
	],		
	"Funciones Tutor" => [
		"Icono" => "nav-icon fa fa-user",
		"Tipo" => "Sub-menu",
		"Menu" => [
			"Vincular DNI" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "vinculardni",
				"Archivo" => "",
			],
			"Vinculados" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "vinculados",
				"Archivo" => "",
			],
			"Email" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "Email",
				"Archivo" => "",
			],
		],
	],		
];
$MenuArr[6] = [
	"Inicio" => [
		"Icono" => "nav-icon fas fa-home",
		"Seccion" => "Inicio",
		"Archivo" => "Inicio.php",
	],
];
$MenuArr[7] = [
	"Inicio" => [
		"Icono" => "nav-icon fas fa-home",
		"Seccion" => "Inicio",
		"Archivo" => "Inicio.php",
	],
	"Ver Cuaderno" => [
				"Icono" => "nav-icon fas fa-address-book",
				"Seccion" => "cuaderno31",
				"Archivo" => "",
	],
	"Enviar Nota" => [
		"Icono" => "nav-icon fa fa-paper-plane",
		"Seccion" => "cuaderno11",
		"Archivo" => "",
	],
	"Asistencia" => [
		"Icono" => " nav-icon fas fa-chalkboard-teacher",
		"Seccion" => "asistencia",
		"Archivo" => "Perfil.php",
	],
	"Ver Asistencia" => [
		"Icono" => "nav-icon fa fa-user",
		"Seccion" => "verasistencia",
		"Archivo" => "Perfil.php",
	],
	"Registro Tutor" => [
		"Icono" => "nav-icon fas fa-user",
		"Seccion" => "registrotutor",
		"Archivo" => "",
	],
	"Ver Tutor" => [
		"Icono" => "nav-icon fas fa-user-friends",
		"Seccion" => "admvertutor1",
		"Archivo" => "Perfil.php",
	],
	"Funciones Tutor" => [
			"Icono" => "nav-icon fa fa-user",
			"Tipo" => "Sub-menu",
			"Menu" => [
				"Vincular DNI" => [
					"Icono" => "nav-icon fa fa-caret-right",
					"Seccion" => "vinculardni",
					"Archivo" => "",
				],
				"Vinculados" => [
					"Icono" => "nav-icon fa fa-caret-right",
					"Seccion" => "vinculados",
					"Archivo" => "",
				],
				"Email" => [
					"Icono" => "nav-icon fa fa-caret-right",
					"Seccion" => "Email",
					"Archivo" => "",
				],
			],
		],		
];
$MenuArr[8] = [
	"Inicio" => [
		"Icono" => "nav-icon fas fa-home",
		"Seccion" => "Inicio",
		"Archivo" => "Inicio.php",
	],
];
$MenuArr[9] = [
	"Inicio" => [
		"Icono" => "nav-icon fas fa-home",
		"Seccion" => "Inicio",
		"Archivo" => "Inicio.php",
	],
		"Funciones Mutual" => [
		"Icono" => "nav-icon fa fa-user",
		"Tipo" => "Sub-menu",
		"Menu" => [
			"Listado" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "listadomutual",
				"Archivo" => "",
			],
			"Carnet" => [
				"Icono" => "nav-icon fa fa-caret-right",
				"Seccion" => "carnetmutual",
				"Archivo" => "",
			],
		],
	],
];
// Clase del menú
class Menu
{
	var $ArrayLst;
	var $Seccion;
	var $Archivo;

	function __construct($ArrayLst)
	{
		$this->ArrayLst = $ArrayLst;
		$this->Seccion = isset($_GET['Seccion']) ? $_GET['Seccion'] : "";
	}

	function crearItem($SubArray, $Key)
	{
		echo '<li class="nav-item">' .
			'<a href="' . (null == $SubArray['Seccion'] ? '#' : '?Seccion=' . $SubArray['Seccion']) . '" class="nav-link ' . (isset($SubArray['Activo']) ? 'active' : '') . '">' .
			(null == $SubArray['Icono'] ? '' : '<i class="' . $SubArray['Icono'] . '"></i>') .
			'<p>' . $Key . '</p>' .
			'</a>
        </li>';
	}

	function crearSubMenu($SubArray, $Key)
	{
		echo '<li class="nav-item has-treeview ' . (isset($SubArray['Activo']) ? 'menu-open' : '') . '">
            <a href="#" class="nav-link ' . (isset($SubArray['Activo']) ? 'active' : '') . '">' .
			(null == $SubArray['Icono'] ? '' : '<i class="' . $SubArray['Icono'] . '"></i>') . '
                <p>' . $Key . '<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">';
	}

	function crearSeparador($SubArray, $Key)
	{
		echo '<li class="nav-header">' . $Key . '</li>';
	}

	function obtenerArchivo()
	{
		return $this->Archivo;
	}

	function crearMenu()
	{
		foreach ($this->ArrayLst as $Key => $Valor) {
			if (isset($Valor['Tipo'])) {
				if ($Valor['Tipo'] == "Sub-menu") {
					foreach ($Valor['Menu'] as $SubKey => $SubMenu) {
						if (null !== $SubMenu['Seccion'] && $SubMenu['Seccion'] == $this->Seccion) {
							$this->ArrayLst[$Key]['Menu'][$SubKey]['Activo'] = true;
							$this->ArrayLst[$Key]['Activo'] = true;
							$this->Archivo = $SubMenu['Archivo'];
						}
					}
				}
			} else {
				if (null !== $Valor['Seccion'] && $Valor['Seccion'] == $this->Seccion) {
					$this->ArrayLst[$Key]['Activo'] = true;
					if (null !== $Valor['Archivo']) {
						$this->Archivo = $Valor['Archivo'];
					}
				}
			}
		}

		foreach ($this->ArrayLst as $Key => $Valor) {
			if (isset($Valor['Tipo'])) {
				if ($Valor['Tipo'] == "Sub-menu") {
					$this->crearSubMenu($Valor, $Key);
					foreach ($Valor['Menu'] as $SubKey => $SubMenu) {
						if (null == $SubKey) {
						} else {
							$this->crearItem($SubMenu, $SubKey);
						}
					}
					echo '</ul></li>';
				} elseif ($Valor['Tipo'] == "Separador") {
					$this->crearSeparador($Valor, $Key);
				}
			} else {
				$this->crearItem($Valor, $Key);
			}
		}
	}
}

// Incluye las secciones aparte del menú
include "vistas/Secciones.php";
?>
<?php
/*
Copyright (c) 2025 Armas Juan Manuel (Derkomx) - http://ezsoft.com.ar
Este archivo forma parte de un software de uso NO COMERCIAL bajo una Licencia MIT modificada.
*/
setlocale(LC_CTYPE, 'es');
date_default_timezone_set('America/Argentina/Buenos_Aires');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>CET N° 3</title>
    <link rel="shortcut icon" href="media/logo.webp" type="image/x-icon">
    <link rel="icon" href="media/logo.webp" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="librerias/ionicons/ionicons.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="librerias/ionicons/css.css" rel="stylesheet">
    <!-- Librería jQuery -->
    <script src="librerias/jQuery.js"></script>
    <!-- Librería jQuery - maskedinput -->
    <script src="librerias/jquery.maskedinput.js"></script>
    <!-- Librería Notiflix -->
    <link rel="stylesheet" href="librerias/Notiflix/notiflix-2.6.0.min.css" />
    <script src="librerias/Notiflix/notiflix-2.6.0.min.js"></script>
    <!-- Librería CropperJS -->
    <link href="librerias/CropperJS/cropper.css" rel="stylesheet">
    <script src="librerias/CropperJS/cropper.js"></script>
    <!-- Librería PSWmeter -->
    <script src="librerias/pswmeter.min.js"></script>
    <!-- Bootstrap -->
    <link href="librerias/Bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
    <!-- Owl Carousel -->
    <link href="librerias/OwlCarousel/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="librerias/OwlCarousel/owl.theme.default.css" rel="stylesheet" type="text/css" />
    <!-- Modernizr JS -->
    <script src="librerias/modernizr-3.5.0.min.js"></script>
    <!-- SweetAlert 2 -->
    <script src="librerias/sweetalert2.all.min.js"></script>
    <!-- Librería Moment -->
    <script src="plugins/moment/moment-with-locales.min.js"></script>
    <link rel="stylesheet" href="librerias/flatpickr.min.css">
    <script src="librerias/flatpickr.min.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed single">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Proyectos del CET 3</a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <?php
				echo '<img src="media/logo.webp" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">';
				?>

                <span class="brand-text font-weight-light">APP CET 3 Ver. 1.1.0</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <?php
						$nMenu = new Menu($MenuArr[$lvlusr]);
						$nMenu->crearMenu();
						?>
                        <li class="nav-item">
                            <a href="?Seccion=token" class="nav-link">
                                <i class="nav-icon fa fa-paper-plane"></i>
                                <p>Notificacion en Dispositivo</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="includes/Logout.php" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Cerrar sesión</p>
                            </a>
                        </li>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php
			 
			// Verifica si 'Seccion' está definido en GET y si $Secciones está definido
			$Seccion = isset($_GET['Seccion']) ? $_GET['Seccion'] : null;
			$archivo = null;

			if ($Seccion !== null && isset($Secciones[$lvlusr][$Seccion])) {
				$archivo = $Secciones[$lvlusr][$Seccion];
			}

			// Si no hay archivo definido por el menú, usa el archivo basado en $Secciones
			if ($archivo === null && $nMenu->obtenerArchivo() !== null) {
				$archivo = $nMenu->obtenerArchivo();
			}

			// Si no se encuentra un archivo, establece 'Inicio.php' como el archivo por defecto
			if ($archivo === null) {
				$archivo = './vistas/Inicio.php';
			}

			// Incluye el archivo adecuado o muestra un 404
			if (file_exists('./vistas/' . $archivo)) {
				include './vistas/' . $archivo;
			} else {
				include './vistas/Inicio.php';
			}
			?>
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            Copyright &copy; 2024 <a href="https://generalrocacet3.blogspot.com/" target="_Blank">Cet N° 3</a>.
            <div class="float-right d-none d-sm-inline-block">
                <b> Ver.</b> 1.0.0
            </div>
        </footer>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <script src="plugins/summernote/lang/summernote-es-ES.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>

    <script src="librerias/Bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="librerias/bootbox.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

    <script src="librerias/OwlCarousel/owl.carousel.min.js"></script>
</body>

</html>
