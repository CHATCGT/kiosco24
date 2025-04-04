<?php
include ('../app/config.php');
session_start();

$id = $_GET['id'];
$session_id = session_id();
    
    try {
        $sentencia = $pdo->prepare("DELETE FROM tmp_ventas WHERE id = ? AND session_id = ?");
        $resultado = $sentencia->execute([$id, $session_id]);
        
        if($resultado) {
            header('Location: ' . $URL . '/ventas/create.php');
        } else {
            header('Location: ' . $URL . '/ventas/create.php');
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de base de datos']);
    }

?>