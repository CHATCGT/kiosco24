<?php
/**
 * Created by PhpStorm.
 * User: HILARIWEB
 * Date: 19/1/2023
 * Time: 22:40
 */

 $id_usuario_get = $_GET['id'];

 $sql_usuarios = "SELECT us.id_usuario as id_usuario, us.nombres as nombres, us.email as email, rol.rol as rol, us.dni as dni 
                  FROM tb_usuarios as us 
                  INNER JOIN tb_roles as rol ON us.id_rol = rol.id_rol 
                  WHERE id_usuario = :id_usuario";
 $query_usuarios = $pdo->prepare($sql_usuarios);
 $query_usuarios->bindParam(':id_usuario', $id_usuario_get, PDO::PARAM_INT);
 $query_usuarios->execute();
 $usuarios_datos = $query_usuarios->fetchAll(PDO::FETCH_ASSOC);
 
 foreach ($usuarios_datos as $usuarios_dato) {
     $nombres = $usuarios_dato['nombres'];
     $email = $usuarios_dato['email'];
     $rol = $usuarios_dato['rol'];
     $dni = $usuarios_dato['dni'];  // Capturamos el DNI
 }
 