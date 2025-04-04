<?php

include ('../../config.php');

// Recibiendo datos del formulario
$codigo = $_POST['codigo'];
$id_categoria = $_POST['id_categoria'];
$nombre = $_POST['nombre'];
$id_usuario = $_POST['id_usuario'];
$descripcion = $_POST['descripcion'];
$stock = $_POST['stock'];
$stock_minimo = $_POST['stock_minimo'];
$stock_maximo = $_POST['stock_maximo'];
$precio_compra = $_POST['precio_compra'];
$precio_venta = $_POST['precio_venta'];
$porcentaje_ganancia = $_POST['porcentaje_ganancia'];
$fecha_ingreso = $_POST['fecha_ingreso'];
$id_proveedor = $_POST['id_proveedor'];


// Imagen
$nombreDelArchivo = date("Y-m-d-h-i-s");
$filename = $nombreDelArchivo . "__" . $_FILES['image']['name'];
$location = "../../../almacen/img_productos/" . $filename;

// Movemos la imagen subida a la ubicación indicada
move_uploaded_file($_FILES['image']['tmp_name'], $location);

// Lógica de cálculo (precio de venta o porcentaje de ganancia)
if (empty($precio_venta) && !empty($porcentaje_ganancia)) {
    // Si no se ha ingresado el precio de venta pero sí el porcentaje de ganancia
    $precio_venta = $precio_compra + ($precio_compra * ($porcentaje_ganancia / 100));
} elseif (empty($porcentaje_ganancia) && !empty($precio_venta)) {
    // Si no se ha ingresado el porcentaje de ganancia pero sí el precio de venta
    $porcentaje_ganancia = (($precio_venta - $precio_compra) / $precio_compra) * 100;
}

// Registro de la fecha y hora de creación
$fechaHora = date("Y-m-d H:i:s");

// Inserción en la base de datos
$sentencia = $pdo->prepare("INSERT INTO tb_almacen
       ( codigo, nombre, descripcion, stock, stock_minimo, stock_maximo, precio_compra, precio_venta, fecha_ingreso, imagen, id_usuario, id_categoria, fyh_creacion, id_proveedor) 
VALUES (:codigo, :nombre, :descripcion, :stock, :stock_minimo, :stock_maximo, :precio_compra, :precio_venta, :fecha_ingreso, :imagen, :id_usuario, :id_categoria, :fyh_creacion, :id_proveedor)");

// Bind de los parámetros
$sentencia->bindParam('codigo', $codigo);
$sentencia->bindParam('nombre', $nombre);
$sentencia->bindParam('descripcion', $descripcion);
$sentencia->bindParam('stock', $stock);
$sentencia->bindParam('stock_minimo', $stock_minimo);
$sentencia->bindParam('stock_maximo', $stock_maximo);
$sentencia->bindParam('precio_compra', $precio_compra);
$sentencia->bindParam('precio_venta', $precio_venta);
$sentencia->bindParam('fecha_ingreso', $fecha_ingreso);
$sentencia->bindParam('imagen', $filename);
$sentencia->bindParam('id_usuario', $id_usuario);
$sentencia->bindParam('id_categoria', $id_categoria);
$sentencia->bindParam('fyh_creacion', $fechaHora);
$sentencia->bindParam('id_proveedor', $id_proveedor);  // Nuevo bind para el proveedor

// Ejecución de la consulta
if ($sentencia->execute()) {
    session_start();
    $_SESSION['mensaje'] = "Se registró el producto correctamente";
    $_SESSION['icono'] = "success";
    header('Location: ' . $URL . '/almacen/');
} else {
    session_start();
    $_SESSION['mensaje'] = "Error: no se pudo registrar el producto en la base de datos";
    $_SESSION['icono'] = "error";
    header('Location: ' . $URL . '/almacen/create.php');
}

