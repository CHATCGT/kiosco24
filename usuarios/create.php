<?php
include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
include ('../app/controllers/roles/listado_de_roles.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Registro de un nuevo usuario</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-5">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Llene los datos con cuidado</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="../app/controllers/usuarios/create.php" method="post">
                                        <div class="form-group">
                                            <label for="nombres">Nombres</label>
                                            <input type="text" name="nombres" class="form-control" 
                                            placeholder="Escriba aquí el nombre del nuevo usuario..."
                                            value="<?php echo isset($_SESSION['nombres']) ? $_SESSION['nombres'] : ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" 
                                            placeholder="Escriba aquí el correo del nuevo usuario..."
                                            value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="dni">DNI (formato XX.XXX.XXX)</label>
                                            <input type="text" name="dni" class="form-control" maxlength="10"
                                            oninput="formatDNI(this)" placeholder="XX.XXX.XXX" 
                                            value="<?php echo isset($_SESSION['dni']) ? $_SESSION['dni'] : ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="rol">Rol del usuario</label>
                                            <select name="rol" id="rol" class="form-control">
                                                <?php foreach ($roles_datos as $roles_dato) { ?>
                                                    <option value="<?php echo $roles_dato['id_rol']; ?>"
                                                    <?php echo isset($_SESSION['rol']) && $_SESSION['rol'] == $roles_dato['id_rol'] ? 'selected' : ''; ?>>
                                                    <?php echo $roles_dato['rol']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_user">Contraseña</label>
                                            <div class="input-group">
                                                <input type="password" name="password_user" id="password_user" class="form-control" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-eye" id="togglePasswords" style="cursor: pointer;"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_repeat">Repita la Contraseña</label>
                                            <div class="input-group">
                                                <input type="password" name="password_repeat" id="password_repeat" class="form-control" required>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- SweetAlert Script -->
<?php if (isset($_SESSION['error'])): ?>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "<?php echo $_SESSION['error']; ?>"
        });
    </script>
    <?php unset($_SESSION['error']); // Limpiar el mensaje de error ?>
<?php endif; ?>

<!-- Script para formatear el DNI automáticamente -->
<script>
    function formatDNI(dniInput) {
        let dni = dniInput.value.replace(/\D/g, ''); // Remover caracteres no numéricos
        dni = dni.substring(0, 8); // Limitar a 8 dígitos
        if (dni.length > 5) {
            dni = dni.replace(/(\d{2})(\d{3})(\d{3})/, '$1.$2.$3');
        } else if (dni.length > 2) {
            dni = dni.replace(/(\d{2})(\d{3})/, '$1.$2');
        }
        dniInput.value = dni;
    }

    // Script para mostrar/ocultar ambas contraseñas con un solo ícono de ojo
    const togglePasswords = document.querySelector('#togglePasswords');
    const passwordField = document.querySelector('#password_user');
    const passwordRepeatField = document.querySelector('#password_repeat');

    togglePasswords.addEventListener('click', function () {
        // Alternar entre 'password' y 'text' para ambos campos
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        passwordRepeatField.setAttribute('type', type);

        // Cambiar el ícono del ojo
        this.classList.toggle('fa-eye-slash');
    });
</script>

<?php include ('../layout/parte2.php'); ?>
