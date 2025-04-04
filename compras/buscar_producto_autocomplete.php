<?php
// buscar_producto_autocomplete.php
include ('../app/config.php');
include ('../layout/sesion.php');

// Obtener el término de búsqueda
$term = $_GET['term'];

// Consulta para buscar productos por código o nombre
$sql = "SELECT id_producto, codigo, nombre FROM tb_almacen 
        WHERE codigo LIKE :term OR nombre LIKE :term 
        ORDER BY nombre ASC LIMIT 10";

$query = $pdo->prepare($sql);
$query->execute(['term' => '%'.$term.'%']);
$productos = $query->fetchAll(PDO::FETCH_ASSOC);

// Formatear resultados para el autocomplete
$results = [];
foreach ($productos as $producto) {
    $results[] = [
        'id' => $producto['id_producto'],
        'codigo' => $producto['codigo'],
        'nombre' => $producto['nombre'],
        'value' => $producto['codigo'] . ' - ' . $producto['nombre'] // Lo que se mostrará en el input después de seleccionar
    ];
}

// Devolver resultados en formato JSON
header('Content-Type: application/json');
echo json_encode($results);
?>