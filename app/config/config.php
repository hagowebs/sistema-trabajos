<?php // Información del proyecto

function info($value) {
    switch ($value) {
        case 'name': echo 'Trabajos PIP'; break;
        case 'url': return 'https://localhost/sistema'; break;
        case 'version': echo '2025-09-10 3.0'; break;
        case 'updates': echo '2025-09-10 3.0 Actualizar de AdminLte2 a AdminLte3'; break;
    }
}

// Rutas validas

$ruta_actual = $_GET['ruta'] ?? 'trabajos';
$rutas_validas = [
    'usuarios' => ['nombre' => 'Usuarios', 'icono' => 'fas fa-users'],
    'clientes' => ['nombre' => 'Clientes', 'icono' => 'fas fa-user-tie'],
    'trabajos' => ['nombre' => 'Trabajos', 'icono' => 'fas fa-briefcase'],
    'cxc' => ['nombre' => 'CXC', 'icono' => 'fas fa-file-invoice-dollar'],
    'dtfs' => ['nombre' => 'DTFs', 'icono' => 'fas fa-print'],
    'dtfuvs' => ['nombre' => 'DTFUVs', 'icono' => 'fas fa-layer-group'],
    'lonas' => ['nombre' => 'Lonas', 'icono' => 'fas fa-image'],
    'vinilos' => ['nombre' => 'Vinilos', 'icono' => 'fas fa-palette'],
    'placas' => ['nombre' => 'Placas', 'icono' => 'fas fa-square'],
    'productos' => ['nombre' => 'Productos', 'icono' => 'fas fa-box'],
    'requisics' => ['nombre' => 'Requisiciones', 'icono' => 'fas fa-clipboard-list'],
    'ayuda' => ['nombre' => 'Ayuda', 'icono' => 'fas fa-question-circle']
];

// Partes de plantilla

function get_sidebar() {
    global $ruta_actual, $rutas_validas;
    echo '<ul class="nav nav-pills nav-sidebar flex-column">';
    foreach ($rutas_validas as $ruta => $datos) {
        if ($_SESSION["perfil"] !== "Administrador" || $_SESSION["usuario"] !== "emilio") {
            if (in_array($ruta, ['usuarios', 'cxc', 'requisics'])) {
                continue;
            }
        }

        $active = ($ruta === $ruta_actual) ? 'active' : '';
        echo '
                    <li class="nav-item">
                        <a href="'.$ruta.'" class="nav-link '.$active.'">
                            <i class="nav-icon '.$datos['icono'].'"></i>
                            <p>'.$datos['nombre'].'</p>
                        </a>
                    </li>';
    }
            echo '
                </ul>
            ';
}
function get_view() {
    global $ruta_actual, $rutas_validas;
    if (array_key_exists($ruta_actual, $rutas_validas)) {
        require_once '../public/header.php';
        require_once '../app/views/'.$ruta_actual.'.php';
        require_once '../public/footer.php';
    } elseif ($ruta_actual == 'logout') {
        require_once 'logout.php';
    } else {
        require_once '../public/header.php';
        require_once '../public/404.php';
        require_once '../public/footer.php';
    }
}
function get_login() {
    require_once '../public/login.php';
}

// Estilos y Scripts

function get_styles() {
    echo '<link rel="stylesheet" href="'.info("url").'/public/assets/css/custom.css">
    ';
}
function get_scripts() {
    echo '<script src="'.info("url").'/public/assets/js/custom.js"></script>
';
    global $ruta_actual, $rutas_validas;
    if (array_key_exists($ruta_actual, $rutas_validas)) {
    echo '<script src="'.info("url").'/app/views/js/'.$ruta_actual.'.js"></script>';
    }
}

// Zona horaria e idioma

date_default_timezone_set('America/Mexico_City');
function language_attributes() {
    echo 'lang="es-MX" dir="ltr"';
}

// Opciones de seguridad

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

// Control de errores

define('APP_ENV', 'desarrollo');
if (APP_ENV === 'desarrollo') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}