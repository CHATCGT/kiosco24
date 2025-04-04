<?php
include ('../../config.php');

$nombres = $_POST['nombres'];
$email = $_POST['email'];
$password_user = $_POST['password_user'];
$password_repeat = $_POST['password_repeat'];
$id_usuario = $_POST['id_usuario'];
$rol = $_POST['rol'];
$dni = $_POST['dni'];
$fechaHora = date('Y-m-d H:i:s');  // Asegúrate de tener el formato adecuado de fecha y hora

if($password_user == "") {
    // Si no se proporciona nueva contraseña, no se actualiza el campo de contraseña
    $sentencia = $pdo->prepare("UPDATE tb_usuarios
        SET nombres=:nombres,
            email=:email,
            id_rol=:id_rol,
            dni=:dni,
            fyh_actualizacion=:fyh_actualizacion 
        WHERE id_usuario = :id_usuario");

    // Binding de parámetros
    $sentencia->bindParam('nombres', $nombres);
    $sentencia->bindParam('email', $email);
    $sentencia->bindParam('id_rol', $rol);
    $sentencia->bindParam('dni', $dni);
    $sentencia->bindParam('fyh_actualizacion', $fechaHora);
    $sentencia->bindParam('id_usuario', $id_usuario);
    $sentencia->execute();

    session_start();
    $_SESSION['mensaje'] = "Se actualizó el usuario correctamente";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'/usuarios/');
} else {
    // Si se proporciona nueva contraseña
    if($password_user == $password_repeat) {
        // No aplicamos hash, guardamos la contraseña tal cual
        $sentencia = $pdo->prepare("UPDATE tb_usuarios
            SET nombres=:nombres,
                email=:email,
                id_rol=:id_rol,
                password_user=:password_user,
                dni=:dni,
                fyh_actualizacion=:fyh_actualizacion 
            WHERE id_usuario = :id_usuario");

        // Binding de parámetros
        $sentencia->bindParam('nombres', $nombres);
        $sentencia->bindParam('email', $email);
        $sentencia->bindParam('id_rol', $rol);
        $sentencia->bindParam('dni', $dni);
        $sentencia->bindParam('password_user', $password_user);
        $sentencia->bindParam('fyh_actualizacion', $fechaHora);
        $sentencia->bindParam('id_usuario', $id_usuario);
        $sentencia->execute();

        session_start();
        $_SESSION['mensaje'] = "Se actualizó el usuario correctamente";
        $_SESSION['icono'] = "success";
        header('Location: '.$URL.'/usuarios/');
    } else {
        // Las contraseñas no coinciden
        session_start();
        $_SESSION['mensaje'] = "Error, las contraseñas no son iguales";
        $_SESSION['icono'] = "error";
        header('Location: '.$URL.'/usuarios/update.php?id='.$id_usuario);
    }
}
?>
