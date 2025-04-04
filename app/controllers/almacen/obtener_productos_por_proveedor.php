<?php
include ('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proveedor = $_POST['id_proveedor'];

    $sql = "SELECT 
        id_producto, 
        codigo, 
        nombre, 
        stock, 
        precio_compra
    FROM 
        tb_almacen
    WHERE 
        id_proveedor = :id_proveedor";
    
    $query = $pdo->prepare($sql);
    $query->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);
    $query->execute();
    
    $productos = $query->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($productos);
    exit;
}
?>