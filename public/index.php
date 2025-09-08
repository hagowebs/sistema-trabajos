<?php // public/index

ini_set('session.gc_maxlifetime', 36000);
ini_set('session.cookie_lifetime', 36000);
session_start();

require_once "../app/config/config.php";

if (!isset($_SESSION['ingreso'])) {
    $_SESSION['ingreso'] = null;
}

if (!isset($_SESSION['usuario']) && $_SESSION['ingreso'] != 'woolo') {
    get_login();
} else {
    get_view();
}