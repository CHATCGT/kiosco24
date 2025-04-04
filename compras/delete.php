<?php
require_once('../app/config.php');
require_once('../layout/sesion.php');

if(!isset($_GET['id_compra'])) {
    $_SESSION['mensaje'] = "No se proporcionó el ID de la compra";
    $_SESSION['icono'] = "error";
    header('Location: index.php');
    exit();
}

$id_compra = $_GET['id_compra'];
$debug = false; // Cambiar a true para ver información de depuración

try {
    $pdo->beginTransaction();
    
    // 1. Obtener detalles de la compra
    $sql_detalles = "SELECT producto_id, cantidad FROM detalle_compras WHERE compra_id = :id_compra";
    $stmt_detalles = $pdo->prepare($sql_detalles);
    $stmt_detalles->bindParam(':id_compra', $id_compra, PDO::PARAM_INT);
    $stmt_detalles->execute();
    $detalles = $stmt_detalles->fetchAll(PDO::FETCH_ASSOC);
    
    if($debug) {
        echo "<pre>Detalles encontrados: ";
        print_r($detalles);
        echo "</pre>";
    }
    
    // 2. Actualizar stock (RESTANDO en lugar de sumando)
    foreach($detalles as $detalle) {
        $sql_update_stock = "UPDATE tb_almacen SET stock = stock - :cantidad WHERE id_producto = :producto_id";
        $stmt_update = $pdo->prepare($sql_update_stock);
        $stmt_update->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
        $stmt_update->bindParam(':producto_id', $detalle['producto_id'], PDO::PARAM_INT);
        $stmt_update->execute();
        
        if($debug) {
            echo "Actualizado stock del producto {$detalle['producto_id']} -{$detalle['cantidad']} unidades<br>";
        }
    }
    
    // 3. Eliminar detalles de la compra
    $sql_delete_detalles = "DELETE FROM detalle_compras WHERE compra_id = :id_compra";
    $stmt_delete_detalles = $pdo->prepare($sql_delete_detalles);
    $stmt_delete_detalles->bindParam(':id_compra', $id_compra, PDO::PARAM_INT);
    $stmt_delete_detalles->execute();
    
    if($debug) {
        echo "Detalles eliminados: ".$stmt_delete_detalles->rowCount()."<br>";
    }
    
    // 4. Eliminar compra principal
    $sql_delete_compra = "DELETE FROM compras WHERE id = :id_compra";
    $stmt_delete_compra = $pdo->prepare($sql_delete_compra);
    $stmt_delete_compra->bindParam(':id_compra', $id_compra, PDO::PARAM_INT);
    $stmt_delete_compra->execute();
    
    if($debug) {
        echo "Compra eliminada: ".$stmt_delete_compra->rowCount()."<br>";
        exit();
    }
    
    $pdo->commit();
    
    $_SESSION['mensaje'] = "Compra eliminada correctamente";
    $_SESSION['icono'] = "success";
    
} catch (PDOException $e) {
    $pdo->rollBack();
    
    $_SESSION['mensaje'] = "Error al eliminar la compra: ".$e->getMessage();
    $_SESSION['icono'] = "error";
    
    if($debug) {
        echo "Error: ".$e->getMessage();
        exit();
    }
}

header('Location: index.php');
exit();
?>