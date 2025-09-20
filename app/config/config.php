<?php // app/config

function info($value) {
    switch ($value) {
        case 'name': echo 'Trabajos PIP'; break;
        case 'url': return 'https://localhost/sistema'; break;
        case 'version': echo '2025-09-12 3.0'; break;
        case 'updates': echo '2025-09-12 3.0 Actualizar de AdminLte2 a AdminLte3'; break;
    }
}

// Rutas validas
$rutas_validas = [
    'usuarios' => [
        'nombre' => 'Usuarios',
        'icono' => 'fas fa-users',
        'perfiles' => ['Administrador']
    ],
    'clientes' => [
        'nombre' => 'Clientes',
        'icono' => 'fas fa-user-tie',
        'perfiles' => ['Administrador', 'Especial', 'Vendedor']
    ],
    'trabajos' => [
        'nombre' => 'Trabajos',
        'icono' => 'fas fa-briefcase',
        'perfiles' => ['Administrador', 'Especial', 'Vendedor']
    ],
    'cxc' => [
        'nombre' => 'CXC',
        'icono' => 'fas fa-file-invoice-dollar',
        'perfiles' => ['Administrador', 'Especial']
    ],
    'dtfs' => [
        'nombre' => 'DTFs',
        'icono' => 'fas fa-plane',
        'perfiles' => ['Administrador', 'Especial', 'Vendedor']
    ],
    'dtfuvs' => [
        'nombre' => 'DTF-UVs',
        'icono' => 'fas fa-paper-plane',
        'perfiles' => ['Administrador', 'Especial', 'Vendedor']
    ],
    'lonas' => [
        'nombre' => 'Lonas',
        'icono' => 'fas fa-image',
        'perfiles' => ['Administrador', 'Especial', 'Vendedor']
    ],
    'vinilos' => [
        'nombre' => 'Viniles',
        'icono' => 'fas fa-palette',
        'perfiles' => ['Administrador', 'Especial', 'Vendedor']
    ],
    'placas' => [
        'nombre' => 'Placas',
        'icono' => 'fas fa-layer-group',
        'perfiles' => ['Administrador', 'Especial', 'Vendedor']
    ],
    'productos' => [
        'nombre' => 'Productos',
        'icono' => 'fas fa-box',
        'perfiles' => ['Administrador', 'Especial', 'Vendedor']
    ],
    'requisics' => [
        'nombre' => 'Requisiciones',
        'icono' => 'fas fa-clipboard-list',
        'perfiles' => ['Administrador', 'Especial']
    ],
    'ayuda' => [
        'nombre' => 'Ayuda',
        'icono' => 'fas fa-question-circle',
        'perfiles' => ['Administrador', 'Especial', 'Vendedor']
    ]
];

// Vista predeterminada
$ruta_actual = $_GET['ruta'] ?? 'trabajos';

// Obtener vista
function get_view() {
    global $ruta_actual, $rutas_validas;
    if (!isset($_SESSION['usuario']) || $_SESSION['ingreso'] != 'woolo') {
        require_once '../public/login.php';
    } else {
        $perfil_usuario = $_SESSION['perfil'] ?? null;
        if (array_key_exists($ruta_actual, $rutas_validas)) {
            $ruta = $rutas_validas[$ruta_actual];
            if (in_array($perfil_usuario, $ruta['perfiles'])) {
                require_once '../public/header.php';
                require_once '../app/views/'.$ruta_actual.'.php';
                require_once '../public/footer.php';
            } else {
                http_response_code(403);
                require_once '../public/header.php';
                require_once '../public/403.php';
                require_once '../public/footer.php';
            }
        } elseif ($ruta_actual == 'logout') {
            require_once 'logout.php';
        } else {
            http_response_code(404);
            require_once '../public/header.php';
            require_once '../public/404.php';
            require_once '../public/footer.php';
        }
    }
}

// Obtener navegación
function get_sidebar() {
    global $ruta_actual, $rutas_validas;
    $perfil_usuario = $_SESSION['perfil'] ?? null;
    echo '<ul class="nav nav-pills nav-sidebar flex-column">';
    foreach ($rutas_validas as $ruta => $datos) {
        if ($perfil_usuario && in_array($perfil_usuario, $datos['perfiles'])) {
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

// Obtener estilos y Scripts
function get_styles() {
    echo '<!-- Custom CSS -->
<link rel="stylesheet" href="'.info("url").'/public/styles.css">
';
}
function get_scripts() {
    echo '<!-- Custom JS -->
<script src="'.info("url").'/public/scripts.js"></script>
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