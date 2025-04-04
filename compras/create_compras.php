<?php
include ('../app/config.php');
session_start();

// 1. Recibir datos del formulario
$fecha = $_POST['fecha'];
$comprobante = $_POST['comprobante'];
$proveedor_id = $_POST['proveedor_id'];
$precio_total = $_POST['precio_total'];
// Convertir '30.000,00' a '30000.00' (formato que PHP/MySQL entiende)
$precio_total = str_replace('.', '', $precio_total);     // Elimina los puntos de miles
$precio_total = str_replace(',', '.', $precio_total);     // Cambia coma decimal por punto
$session_id = session_id();

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // 2. Insertar en tabla compras
    $sql_compra = "INSERT INTO compras 
                        (fecha, comprobante, proveedor_id, precio_total, fyh_creacion) 
                  VALUES (:fecha, :comprobante, :proveedor_id, :precio_total, NOW())";
    
    $stmt_compra = $pdo->prepare($sql_compra);
    $stmt_compra->bindParam(':fecha', $fecha);
    $stmt_compra->bindParam(':comprobante', $comprobante);
    $stmt_compra->bindParam(':proveedor_id', $proveedor_id, PDO::PARAM_INT);
    $stmt_compra->bindParam(':precio_total', $precio_total);
    $stmt_compra->execute();
    
    // Obtener ID de la compra recién insertada
    $compra_id = $pdo->lastInsertId();

    // 3. Obtener productos temporales de esta sesión
    $sql_tmp = "SELECT tmp.*, pro.precio_compra 
               FROM tmp_compras tmp
               JOIN tb_almacen pro ON pro.id_producto = tmp.producto_id
               WHERE tmp.session_id = :session_id";
    
    $stmt_tmp = $pdo->prepare($sql_tmp);
    $stmt_tmp->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $stmt_tmp->execute();
    $productos_tmp = $stmt_tmp->fetchAll(PDO::FETCH_ASSOC);

    // 4. Insertar en detalle_compras y actualizar stock
    foreach ($productos_tmp as $producto) {
        // Insertar en detalle_compras
        $sql_detalle = "INSERT INTO detalle_compras 
                              (compra_id, producto_id, cantidad, fyh_creacion) 
                       VALUES (:compra_id, :producto_id, :cantidad,NOW())";
        
        $stmt_detalle = $pdo->prepare($sql_detalle);
        $stmt_detalle->bindParam(':compra_id', $compra_id, PDO::PARAM_INT);
        $stmt_detalle->bindParam(':producto_id', $producto['producto_id'], PDO::PARAM_INT);
        $stmt_detalle->bindParam(':cantidad', $producto['cantidad'], PDO::PARAM_INT);
        
        $stmt_detalle->execute();

        // Actualizar stock en tb_almacen
        $sql_update = "UPDATE tb_almacen 
                      SET stock = stock + :cantidad 
                      WHERE id_producto = :producto_id";
        
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':cantidad', $producto['cantidad'], PDO::PARAM_INT);
        $stmt_update->bindParam(':producto_id', $producto['producto_id'], PDO::PARAM_INT);
        $stmt_update->execute();
    }

    // 5. Eliminar registros temporales
    $sql_delete_tmp = "DELETE FROM tmp_compras WHERE session_id = :session_id";
    $stmt_delete = $pdo->prepare($sql_delete_tmp);
    $stmt_delete->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $stmt_delete->execute();

    // Confirmar transacción
    $pdo->commit();

    // 6. Respuesta exitosa
    $_SESSION['mensaje'] = "Compra registrada correctamente";
    $_SESSION['icono'] = "success";
    header('Location: '.$URL.'/compras/');
    
} catch (PDOException $e) {
    // Revertir transacción en caso de error
    $pdo->rollBack();
    
    $_SESSION['mensaje'] = "Error al registrar la compra: ".$e->getMessage();
    $_SESSION['icono'] = "error";
    header('Location: '.$URL.'/compras/create.php');
}
?>