<?php
include ('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];

    // Limpieza del DNI (remover puntos)
    $dni = str_replace('.', '', $dni);

    // Consulta para obtener el correo y la contraseña asociados al DNI
    $stmt = $pdo->prepare("SELECT email, password_user FROM tb_usuarios WHERE dni = :dni");
    $stmt->bindParam(':dni', $dni);
    $stmt->execute();

    // Comprobamos si hay resultados
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Redireccionar a clave.php pasando el email y la contraseña
        session_start();
        $_SESSION['email'] = $user['email'];
        $_SESSION['password'] = $user['password_user'];
        header('Location: ../../../login/clave.php');
        exit();
    } else {
        // Manejar el caso cuando no se encuentra el usuario
        session_start();
        $_SESSION['mensaje'] = "DNI no encontrado.";
        header('Location: recuperar_clave.php');
        exit();
    }
}
