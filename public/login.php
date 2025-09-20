<?php // public/login

require_once '../app/config/database.php';

// Verificar inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario  = trim($_POST['usuario']);
    $clave = trim($_POST['clave']);
    try {
        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $encriptado = crypt($clave, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
            if ($encriptado === $row['clave']) {

                // Actualizar último login
                $update = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id";
                $updateStmt = $pdo->prepare($update);
                $updateStmt->bindParam(":id", $row['id'], PDO::PARAM_INT);
                $updateStmt->execute();

                // Obtener variables de usuario
                $_SESSION['id'] = $row['id'];
                $_SESSION['usuario'] = $row['usuario'];
                $_SESSION["nombre"] = $row["nombre"];
                $_SESSION["perfil"] = $row["perfil"];
                $_SESSION["ingreso"] = 'woolo';
                header("Location: trabajos");

                exit;
            } else {
                $_SESSION['error'] = "Contraseña incorrecta";
            }
        } else {
            $_SESSION['error'] = "Usuario no encontrado";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error en la base de datos: " . $e->getMessage();
    }
} ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php info('name'); ?></title>
    <link rel="icon" href="<?php echo info('url'); ?>/public/images/icono-negro.webp">
    <!-- Font Awesome Free 5.15.4 -->
    <link rel="stylesheet" href="<?php echo info("url"); ?>/public/plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE 3.2.0 CSS -->
    <link rel="stylesheet" href="<?php echo info("url"); ?>/public/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page" style="background-image: url('<?php echo info('url'); ?>/public/images/back.webp'); background-size: cover; background-position: center;">
    <div class="login-box">
        <div class="login-logo">
            <img src="<?php echo info('url'); ?>/public/images/logo-blanco-bloque.webp" class="img-responsive" style="padding:30px 100px 0px 100px">
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Usuario" name="usuario" autofocus required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Contraseña" name="clave" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                </form>
            </div>    
        </div>
    </div>
    <!-- AdminLTE 3.2.0 JS -->
    <script src="<?php echo info("url"); ?>/public/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo info("url"); ?>/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo info("url"); ?>/public/dist/js/adminlte.min.js"></script>
</body>
</html>