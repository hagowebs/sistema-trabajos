<?php // Configuración

function info($value) {
    switch ($value) {
        case 'name': return 'Trabajos PIP'; break;
        case 'url': return 'https://localhost/sistema'; break;
        case 'version': return '2026-01-07 3.0'; break;
    }
}

// Rutas validas

function get_route() {
    return [
        'usuarios',
        'clientes',
        'trabajos',
        'placas',
        'pruebas'
    ];
}

// Obtener vista

function get_view() {
    $ruta = $_GET['ruta'] ?? 'pruebas';
    $validas = get_route();
    if (in_array($ruta, $validas)) {
        include 'app/views/'.$ruta.'.php';
    } elseif ($ruta === 'logout') {
        include 'public/logout.php';
    } else {
        include 'public/404.php';
    }
}

// Partes de plantilla

function get_header() {
    require_once 'public/header.php';
}
function get_sidebar() {
    require_once 'public/sidebar.php';
}
function get_footer() {
    require_once 'public/footer.php';
}

// Estilos y Scripts

function get_styles() {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
          <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
          <link rel="stylesheet" href="'.info("url").'/public/assets/css/custom.css">';
}
function get_scripts() {
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
          <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
          <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
          <script src="'.info("url").'/public/assets/js/custom.js"></script>';
    $ruta = $_GET['ruta'] ?? null;
    $validas = get_route();
    if (in_array($ruta, $validas)) {
        echo'<script src="'.info("url").'/app/views/js/'.$ruta.'.js"></script>';
    }
}

// Zona horaria

date_default_timezone_set('America/Mexico_City');

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
