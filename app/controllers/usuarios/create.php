<?php

include ('../../config.php');

session_start(); // Siempre asegurarse de iniciar sesión antes de usar $_SESSION

// Capturamos los datos del formulario
$nombres = $_POST['nombres'];
$email = $_POST['email'];
$rol = $_POST['rol'];
$dni = $_POST['dni'];  
$password_user = $_POST['password_user'];
$password_repeat = $_POST['password_repeat'];

// Guardamos los datos ingresados en la sesión para que no se pierdan si hay error
$_SESSION['nombres'] = $nombres;
$_SESSION['email'] = $email;
$_SESSION['rol'] = $rol;
$_SESSION['dni'] = $dni;

if ($password_user == $password_repeat) {

    // Preparamos la sentencia SQL para insertar los datos
    $sentencia = $pdo->prepare("INSERT INTO tb_usuarios
        (nombres, email, id_rol, dni, password_user, fyh_creacion) 
        VALUES (:nombres, :email, :id_rol, :dni, :password_user, :fyh_creacion)");

    // Enlazamos los parámetros
    $sentencia->bindParam('nombres', $nombres);
    $sentencia->bindParam('email', $email);
    $sentencia->bindParam('id_rol', $rol);
    $sentencia->bindParam('dni', $dni);  
    $sentencia->bindParam('password_user', $password_user);

    // Fecha y hora de creación
    $fechaHora = date("Y-m-d H:i:s");
    $sentencia->bindParam('fyh_creacion', $fechaHora);

    // Ejecutamos la consulta
    $sentencia->execute();

    // Limpiamos los datos de la sesión
    unset($_SESSION['nombres']);
    unset($_SESSION['email']);
    unset($_SESSION['rol']);
    unset($_SESSION['dni']);

    $_SESSION['mensaje'] = "Se registró al usuario de la manera correcta";
    header('Location: ' . $URL . '/usuarios/');
    exit();

} else {
    // Si las contraseñas no coinciden, guardamos el mensaje de error en la sesión
    $_SESSION['error'] = "Error: Las contraseñas no coinciden.";
    header('Location: ' . $URL . '/usuarios/create.php');
    exit();
}
