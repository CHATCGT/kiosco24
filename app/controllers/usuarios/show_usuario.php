<?php
$id_usuario_get = $_GET['id'];

$sql_usuarios = "SELECT us.id_usuario as id_usuario, us.nombres as nombres, us.email as email, us.dni as dni, rol.rol as rol 
                 FROM tb_usuarios as us 
                 INNER JOIN tb_roles as rol ON us.id_rol = rol.id_rol 
                 WHERE us.id_usuario = :id_usuario";
$query_usuarios = $pdo->prepare($sql_usuarios);
$query_usuarios->bindParam(':id_usuario', $id_usuario_get, PDO::PARAM_INT);
$query_usuarios->execute();
$usuarios_datos = $query_usuarios->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios_datos as $usuarios_dato) {
    $nombres = $usuarios_dato['nombres'];
    $email = $usuarios_dato['email'];
    $dni = $usuarios_dato['dni'];
    $rol = $usuarios_dato['rol'];
}
?>

 