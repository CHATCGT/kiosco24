<?php
include ('../../config.php');

// Validate and sanitize input
$id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
$porcentaje_aumento = filter_input(INPUT_POST, 'porcentaje_aumento', FILTER_VALIDATE_FLOAT);

if (!$id_producto || !$porcentaje_aumento) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

try {
    // Prepare SQL to update product price
    $sql = "UPDATE tb_almacen 
            SET 
            precio_compra = ROUND(precio_compra * (1 + :porcentaje / 100), 2),
            precio_venta = ROUND(precio_venta * (1 + :porcentaje / 100), 2)
            WHERE 
            id_producto = :id_producto";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':porcentaje', $porcentaje_aumento, PDO::PARAM_STR);
    $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    
    // Execute the update
    $resultado = $stmt->execute();
    
    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Precio actualizado correctamente']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo actualizar el precio']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>