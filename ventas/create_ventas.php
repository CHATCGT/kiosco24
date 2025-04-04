<?php
include ('../app/config.php');
session_start();

// Verificar si se han enviado los datos necesarios
if(!isset($_POST['cliente_id']) || !isset($_POST['fecha']) || !isset($_POST['comprobante']) || !isset($_POST['total_pagado'])) {
    $_SESSION['mensaje'] = "Faltan datos requeridos para la venta";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/ventas/create.php');
    exit();
}

// 1. Recibir datos del formulario
$fecha = $_POST['fecha'];
$comprobante = $_POST['comprobante'];
$cliente_id = $_POST['cliente_id'];
$total_pagado = $_POST['total_pagado'];
// Convertir '30.000,00' a '30000.00' (formato que PHP/MySQL entiende)
$total_pagado = str_replace('.', '', $total_pagado);     // Elimina los puntos de miles
$total_pagado = str_replace(',', '.', $total_pagado);     // Cambia coma decimal por punto

$session_id = session_id();

echo $_POST['fecha'];
echo $_POST['comprobante'];
echo $_POST['cliente_id'];
echo $_POST['total_pagado'];

// Validar que haya productos en la venta temporal
$sql_check = "SELECT COUNT(*) FROM tmp_ventas WHERE session_id = :session_id";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->bindParam(':session_id', $session_id);
$stmt_check->execute();
$count = $stmt_check->fetchColumn();

if($count == 0) {
    $_SESSION['mensaje'] = "No hay productos en la venta";
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/ventas/create.php');
    exit();
}

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // 2. Insertar en tabla ventas
    $sql_venta = "INSERT INTO ventas 
                        (fecha, comprobante, cliente_id, total_pagado, fyh_creacion) 
                  VALUES (:fecha, :comprobante, :cliente_id, :total_pagado, NOW())";
    
    $stmt_venta = $pdo->prepare($sql_venta);
    $stmt_venta->bindParam(':fecha', $fecha);
    $stmt_venta->bindParam(':comprobante', $comprobante);
    $stmt_venta->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
    $stmt_venta->bindParam(':total_pagado', $total_pagado);
    $stmt_venta->execute();
    
    // Obtener ID de la venta recién insertada
    $venta_id = $pdo->lastInsertId();

    // 3. Obtener productos temporales de esta sesión
    $sql_tmp = "SELECT tmp.*, pro.precio_venta 
               FROM tmp_ventas tmp
               JOIN tb_almacen pro ON pro.id_producto = tmp.producto_id
               WHERE tmp.session_id = :session_id";
    
    $stmt_tmp = $pdo->prepare($sql_tmp);
    $stmt_tmp->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $stmt_tmp->execute();
    $productos_tmp = $stmt_tmp->fetchAll(PDO::FETCH_ASSOC);

    // 4. Insertar en detalle_ventas y actualizar stock
    foreach ($productos_tmp as $producto) {
        // Insertar en detalle_ventas
        $sql_detalle = "INSERT INTO detalle_ventas 
                              (venta_id, producto_id, cantidad, fyh_creacion) 
                       VALUES (:venta_id, :producto_id, :cantidad, NOW())";
        
        $stmt_detalle = $pdo->prepare($sql_detalle);
        $stmt_detalle->bindParam(':venta_id', $venta_id, PDO::PARAM_INT);
        $stmt_detalle->bindParam(':producto_id', $producto['producto_id'], PDO::PARAM_INT);
        $stmt_detalle->bindParam(':cantidad', $producto['cantidad'], PDO::PARAM_INT);
        
        $stmt_detalle->execute();

        // CORRECCIÓN: En ventas, el stock debe DISMINUIR, no aumentar
        $sql_update = "UPDATE tb_almacen 
                      SET stock = stock - :cantidad 
                      WHERE id_producto = :producto_id";
        
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':cantidad', $producto['cantidad'], PDO::PARAM_INT);
        $stmt_update->bindParam(':producto_id', $producto['producto_id'], PDO::PARAM_INT);
        $stmt_update->execute();
    }

    // 5. Eliminar registros temporales
    $sql_delete_tmp = "DELETE FROM tmp_ventas WHERE session_id = :session_id";
    $stmt_delete = $pdo->prepare($sql_delete_tmp);
    $stmt_delete->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $stmt_delete->execute();

    // Confirmar transacción
    $pdo->commit();

    // 6. Respuesta exitosa
    $_SESSION['mensaje'] = "Venta registrada correctamente";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'/ventas/');
    exit();
    
} catch (PDOException $e) {
    // Revertir transacción en caso de error
    $pdo->rollBack();
    
    $_SESSION['mensaje'] = "Error al registrar la venta: ".$e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/ventas/create.php');
    exit();
}

?>