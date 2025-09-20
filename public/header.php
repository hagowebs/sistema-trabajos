<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php info('name'); ?></title>
    <!-- AdminLTE 3.2.0 CSS -->
    <link rel="stylesheet" href="<?php echo info("url"); ?>/public/dist/css/adminlte.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?php echo info("url"); ?>/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo info("url"); ?>/public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo info("url"); ?>/public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="<?php echo info("url"); ?>/public/plugins/daterangepicker/daterangepicker.css">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="<?php echo info("url"); ?>/public/plugins/select2/css/select2.min.css">
    <!-- sweetalert2 CSS -->
    <link rel="stylesheet" href="<?php echo info("url"); ?>/public/plugins/sweetalert2/sweetalert2.min.css">
    <!-- Font Awesome Free 5.15.4 -->
    <link rel="stylesheet" href="<?php echo info("url"); ?>/public/plugins/fontawesome-free/css/all.min.css">
    <?php get_styles(); ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="ayuda" class="nav-link"><i class="fas fa-question-circle"></i> Ayuda</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="trabajos">
                    <i class="fas fa-clock"></i>
                    <span id="contador-pendiente" class="badge badge-warning navbar-badge"></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="trabajos">
                    <i class="fas fa-palette"></i>
                    <span id="contador-diseno" class="badge badge-warning navbar-badge"></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="trabajos">
                    <i class="fas fa-print"></i>
                    <span id="contador-produccion" class="badge badge-warning navbar-badge"></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="trabajos">
                    <i class="fas fa-image"></i>
                    <span id="contador-taller" class="badge badge-warning navbar-badge"></span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-circle-user"></i> <?php echo $_SESSION["nombre"]; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a class="dropdown-item" href="logout">Salir</a>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?php echo info('url'); ?>" class="brand-link">
            <img src="<?php echo info('url'); ?>/public/images/icono-blanco.webp">
            <span class="brand-text font-weight-light"><?php info('name'); ?></span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">              
                <?php get_sidebar(); ?>
</nav>
        </div>
    </aside>
    
