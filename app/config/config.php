<?php // Información del proyecto

function info($value) {
    switch ($value) {
        case 'name': echo 'Trabajos PIP'; break;
        case 'url': return 'https://localhost/sistema'; break;
        case 'version': echo '2025-09-10 3.0'; break;
        case 'version': echo '
        2025-09-10 3.0 Actualizar de AdminLte2 a AdminLte3 e incluir Ajax en DataTables eficientando las consutas
        '; break;
    }
}

// Rutas validas

$nivelUsuario = $_SESSION['nivel'] ?? 3;
$ruta_actual = $_GET['ruta'] ?? 'pruebas';
$rutas_validas = [
    'usuarios' => ['nombre' => 'Usuarios', 'icono' => 'fas fa-users', 'visible' => true, 'acceso' => 3],
    'clientes' => ['nombre' => 'Clientes', 'icono' => 'fas fa-user-tie', 'visible' => true, 'acceso' => 2],
    'trabajos' => ['nombre' => 'Trabajos', 'icono' => 'fas fa-briefcase', 'visible' => true, 'acceso' => 2],
    'cxc' => ['nombre' => 'CXC', 'icono' => 'fas fa-file-invoice-dollar', 'visible' => true, 'acceso' => 2],
    'dtfs' => ['nombre' => 'DTFs', 'icono' => 'fas fa-print', 'visible' => true, 'acceso' => 2],
    'dtfuvs' => ['nombre' => 'DTFUVs', 'icono' => 'fas fa-layer-group', 'visible' => true, 'acceso' => 2],
    'lonas' => ['nombre' => 'Lonas', 'icono' => 'fas fa-image', 'visible' => true, 'acceso' => 2],
    'vinilos' => ['nombre' => 'Vinilos', 'icono' => 'fas fa-palette', 'visible' => true, 'acceso' => 2],
    'placas' => ['nombre' => 'Placas', 'icono' => 'fas fa-square', 'visible' => true, 'acceso' => 2],
    'productos' => ['nombre' => 'Productos', 'icono' => 'fas fa-box', 'visible' => true, 'acceso' => 2],
    'requisics' => ['nombre' => 'Requisiciones', 'icono' => 'fas fa-clipboard-list', 'visible' => true, 'acceso' => 2],
    'ayuda' => ['nombre' => 'Ayuda', 'icono' => 'fas fa-question-circle', 'visible' => false, 'acceso' => 1]
];

// Partes de plantilla

function get_header() {
    require_once 'public/header.php';
}
function get_sidebar() {
    global $ruta_actual, $rutas_validas, $nivelUsuario;
    echo '<ul class="nav nav-pills nav-sidebar flex-column">';
    foreach ($rutas_validas as $ruta => $datos) {
        if ($datos['visible'] && $nivelUsuario >= $datos['acceso']) {
            $active = ($ruta === $ruta_actual) ? 'active' : '';
                echo '
                    <li class="nav-item">
                        <a href="'.$ruta.'" class="nav-link '.$active.'">
                            <i class="nav-icon '.$datos['icono'].'"></i>
                            <p>'.$datos['nombre'].'</p>
                        </a>
                    </li>';
        }
    }
            echo '
                </ul>
            ';
}
function get_view() {
    global $ruta_actual, $rutas_validas, $nivelUsuario;
    if (array_key_exists($ruta_actual, $rutas_validas)) {
        include 'app/views/'.$ruta_actual.'.php';
    } elseif ($ruta_actual === 'logout') {
        include 'public/logout.php';
    } else {
        include 'public/404.php';
    }
}
function get_footer() {
    require_once 'public/footer.php';
}

// Estilos y Scripts

function get_styles() {
    echo '<link rel="stylesheet" href="'.info("url").'/public/assets/css/custom.css">';
}
function get_scripts() {
    echo '<script src="'.info("url").'/public/assets/js/custom.js"></script>
';
    global $ruta_actual, $rutas_validas;
    if (array_key_exists($ruta_actual, $rutas_validas)) {
    echo '<script src="'.info("url").'/app/views/js/'.$ruta_actual.'.js"></script>
';
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