<?php
include ('../app/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];

    $sentencia = $pdo->prepare("SELECT email, password_user FROM tb_usuarios WHERE dni = :dni");
    $sentencia->bindParam(':dni', $dni);
    $sentencia->execute();
    
    if ($sentencia->rowCount() > 0) {
        $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);
        $email = $usuario['email'];
        $password = $usuario['password_user'];
    } else {
        // Redirigir o mostrar mensaje de error si no se encuentra el DNI
        session_start();
        $_SESSION['mensaje'] = "DNI no encontrado.";
        header('Location: recuperar_clave.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clave Recuperada</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../public/templeates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../public/templeates/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../public/templeates/AdminLTE-3.2.0/dist/css/adminlte.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1"><b>Clave</b> Recuperada</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Los datos recuperados son:</p>
            <form>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="text" class="form-control" value="<?php echo isset($password) ? $password : ''; ?>" readonly>
                </div>
                <div class="row">
                    <div class="col-6">
                        <a href="recuperar_clave.php" class="btn btn-secondary btn-block">Volver</a>
                    </div>
                    <div class="col-6">
                        <a href="../index.php" class="btn btn-primary btn-block">Continuar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="../public/templeates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../public/templeates/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../public/templeates/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>
