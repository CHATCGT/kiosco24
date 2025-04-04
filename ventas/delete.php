<?php
require_once('../app/config.php');
require_once('../layout/sesion.php');

if(!isset($_GET['id_venta'])) {
    $_SESSION['mensaje'] = "No se proporcionó el ID de la venta";
    $_SESSION['icono'] = "error";
    header('Location: index.php');
    exit();
}

$id_venta = $_GET['id_venta'];
$debug = false; // Cambiar a true para ver información de depuración

try {
    $pdo->beginTransaction();
    
    // 1. Obtener detalles
    $sql_detalles = "SELECT producto_id, cantidad FROM detalle_ventas WHERE venta_id = :id_venta";
    $stmt_detalles = $pdo->prepare($sql_detalles);
    $stmt_detalles->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
    $stmt_detalles->execute();
    $detalles = $stmt_detalles->fetchAll(PDO::FETCH_ASSOC);
    
    if($debug) {
        echo "<pre>Detalles encontrados: ";
        print_r($detalles);
        echo "</pre>";
    }
    
    // 2. Actualizar stock
    foreach($detalles as $detalle) {
        $sql_update_stock = "UPDATE tb_almacen SET stock = stock + :cantidad WHERE id_producto = :producto_id";
        $stmt_update = $pdo->prepare($sql_update_stock);
        $stmt_update->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
        $stmt_update->bindParam(':producto_id', $detalle['producto_id'], PDO::PARAM_INT);
        $stmt_update->execute();
        
        if($debug) {
            echo "Actualizado stock del producto {$detalle['producto_id']} +{$detalle['cantidad']} unidades<br>";
        }
    }
    
    // 3. Eliminar detalles
    $sql_delete_detalles = "DELETE FROM detalle_ventas WHERE venta_id = :id_venta";
    $stmt_delete_detalles = $pdo->prepare($sql_delete_detalles);
    $stmt_delete_detalles->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
    $stmt_delete_detalles->execute();
    
    if($debug) {
        echo "Detalles eliminados: ".$stmt_delete_detalles->rowCount()."<br>";
    }
    
    // 4. Eliminar venta principal
    $sql_delete_venta = "DELETE FROM ventas WHERE id = :id_venta";
    $stmt_delete_venta = $pdo->prepare($sql_delete_venta);
    $stmt_delete_venta->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
    $stmt_delete_venta->execute();
    
    if($debug) {
        echo "Venta eliminada: ".$stmt_delete_venta->rowCount()."<br>";
        exit();
    }
    
    $pdo->commit();
    
    $_SESSION['mensaje'] = "Venta eliminada correctamente";
    $_SESSION['icono'] = "success";
    
} catch (PDOException $e) {
    $pdo->rollBack();
    
    $_SESSION['mensaje'] = "Error al eliminar la venta: ".$e->getMessage();
    $_SESSION['icono'] = "error";
    
    if($debug) {
        echo "Error: ".$e->getMessage();
        exit();
    }
}

header('Location: index.php');
exit();
?>