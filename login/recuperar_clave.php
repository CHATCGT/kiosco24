<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Clave</title>
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
            <a href="#" class="h1"><b>Recuperar</b> CLAVE</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Ingrese su DNI</p>
            <form action="clave.php" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="dni" class="form-control" placeholder="DNI (formato XX.XXX.XXX)" maxlength="10" oninput="formatDNI(this)" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-card"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Recuperar Clave</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <a href="../index.php">Volver al Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function formatDNI(dniInput) {
        let dni = dniInput.value.replace(/\D/g, '').substring(0, 8);
        if (dni.length > 5) {
            dni = dni.replace(/(\d{2})(\d{3})(\d{3})/, '$1.$2.$3');
        } else if (dni.length > 2) {
            dni = dni.replace(/(\d{2})(\d{3})/, '$1.$2');
        }
        dniInput.value = dni;
    }
</script>

<!-- jQuery -->
<script src="../public/templeates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../public/templeates/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../public/templeates/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>
