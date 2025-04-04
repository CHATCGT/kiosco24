<?php
include ('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proveedor = $_POST['id_proveedor'];
    $porcentaje_aumento = floatval($_POST['porcentaje_aumento']);

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Update prices for products from this provider
        $sql = "UPDATE tb_almacen 
                SET 
                    precio_compra = ROUND(precio_compra * (1 + :porcentaje / 100), 2),
                    precio_venta = ROUND(precio_venta * (1 + :porcentaje / 100), 2)
                WHERE 
                    id_proveedor = :id_proveedor";
        
        $query = $pdo->prepare($sql);
        $query->bindParam(':porcentaje', $porcentaje_aumento, PDO::PARAM_STR);
        $query->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);
        $query->execute();

        // Commit transaction
        $pdo->commit();

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // Rollback transaction
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}
?>