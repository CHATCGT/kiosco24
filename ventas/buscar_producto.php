<?php
include ('../app/config.php');
session_start();

$cantidad = isset($_GET['cantidad']) ? (int)$_GET['cantidad'] : 0;
$codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';

// Validación básica
if (empty($codigo) || $cantidad <= 0) {
    exit("<script>alert('Datos inválidos');</script>");
}

try {
    $pdo->beginTransaction();
    
    // Buscar el producto en el almacén con su stock
    $sql_productos = "SELECT id_producto, stock FROM tb_almacen WHERE codigo = :codigo";
    $query_productos = $pdo->prepare($sql_productos);
    $query_productos->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $query_productos->execute();
    $producto = $query_productos->fetch(PDO::FETCH_ASSOC);
    
    if (!$producto) {
        renderTabla($pdo);
        exit("<script>alert('Producto no encontrado');</script>");
    }

    $producto_id = $producto['id_producto'];
    $stock_disponible = $producto['stock'];
    $session_id = session_id();
    $fechaHora = date('Y-m-d H:i:s');

    // Verificar si ya existe en tmp_ventas
    $sql_verificar = "SELECT id, cantidad FROM tmp_ventas WHERE producto_id = :producto_id AND session_id = :session_id";
    $query_verificar = $pdo->prepare($sql_verificar);
    $query_verificar->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $query_verificar->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $query_verificar->execute();
    $existe = $query_verificar->fetch(PDO::FETCH_ASSOC);

    // Calcular cantidad total solicitada
    $cantidad_solicitada = $cantidad;
    if ($existe) {
        $cantidad_solicitada += $existe['cantidad'];
    }

    // Verificar si hay suficiente stock
    if ($cantidad_solicitada > $stock_disponible) {
        renderTabla($pdo);
        exit("<script>alert('¡Stock insuficiente! Solo quedan " . $stock_disponible . " unidades disponibles.');</script>");
    }

    if ($existe) {
        // Actualizar la cantidad existente
        $nueva_cantidad = $existe['cantidad'] + $cantidad;
        $sql_actualizar = "UPDATE tmp_ventas SET cantidad = :cantidad, fyh_creacion = :fyh_creacion WHERE id = :id";
        $query_actualizar = $pdo->prepare($sql_actualizar);
        $query_actualizar->bindParam(':cantidad', $nueva_cantidad, PDO::PARAM_INT);
        $query_actualizar->bindParam(':fyh_creacion', $fechaHora);
        $query_actualizar->bindParam(':id', $existe['id'], PDO::PARAM_INT);
        $query_actualizar->execute();
    } else {
        // Insertar nuevo registro
        $sql_insertar = "INSERT INTO tmp_ventas (cantidad, producto_id, session_id, fyh_creacion) VALUES (:cantidad, :producto_id, :session_id, :fyh_creacion)";
        $query_insertar = $pdo->prepare($sql_insertar);
        $query_insertar->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $query_insertar->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $query_insertar->bindParam(':session_id', $session_id, PDO::PARAM_STR);
        $query_insertar->bindParam(':fyh_creacion', $fechaHora);
        $query_insertar->execute();
    }
    
    $pdo->commit();
    renderTabla($pdo);

} catch (Exception $e) {
    $pdo->rollBack();
    exit("<script>alert('Error: " . $e->getMessage() . "');</script>");
}

function renderTabla($pdo) {
    $session_id = session_id();
    $sql_tm_ventas = "SELECT tmp.id, tmp.cantidad, pro.codigo, pro.nombre, pro.precio_venta FROM tmp_ventas AS tmp INNER JOIN tb_almacen AS pro ON pro.id_producto = tmp.producto_id WHERE session_id = :session_id";
    $query_tm_ventas = $pdo->prepare($sql_tm_ventas);
    $query_tm_ventas->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $query_tm_ventas->execute();
    $tmp_ventas = $query_tm_ventas->fetchAll(PDO::FETCH_ASSOC);
    
    $total_cantidad = 0;
    $total_venta = 0;
    ?>
    <script>$('#tabla_ventas').css('display','none');</script>
    <div class="col-md-12">
        <table class="table table-sm table-striped table-bordered table-hover">
            <thead>
                <tr style="background-color: #cccccc">
                    <th>Nro</th>
                    <th>Código</th>
                    <th>Cantidad</th>
                    <th>Nombre</th>
                    <th>Costo</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php $cont = 1; foreach ($tmp_ventas as $tmp_venta):
                    $sub_total_venta = $tmp_venta['precio_venta'] * $tmp_venta['cantidad'];
                    $total_cantidad += $tmp_venta['cantidad'];
                    $total_venta += $sub_total_venta;
                ?>
                <tr>
                    <td style="text-align: center"><?php echo $cont++; ?></td>
                    <td style="text-align: center"><?php echo $tmp_venta['codigo']; ?></td>
                    <td style="text-align: center"><?php echo $tmp_venta['cantidad']; ?></td>
                    <td><?php echo $tmp_venta['nombre']; ?></td>
                    <td style="text-align: center"><?php echo number_format($tmp_venta['precio_venta'], 2, ',', '.'); ?></td>
                    <td style="text-align: center"><?php echo number_format($sub_total_venta, 2, ',', '.'); ?></td>
                    <td style="text-align: center">
                        <a href="borrar_tmp_venta.php?id=<?php echo $tmp_venta['id']; ?>" class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right"><b>Total cantidad</b></td>
                    <td style="text-align: center"><b><?php echo $total_cantidad; ?></b></td>
                    <td colspan="2" style="text-align: right"><b>Total compra</b></td>
                    <td style="text-align: center"><b><?php echo number_format($total_venta, 2, ',', '.'); ?></b></td>
                </tr>
            </tfoot>
        </table>
        <script>$('#precio_total').val('<?php echo number_format($total_venta, 2, ',', '.'); ?>');</script>
    </div>
    <?php
}