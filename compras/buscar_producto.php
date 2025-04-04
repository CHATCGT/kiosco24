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
    
    // Buscar el producto en el almacén
    $sql_productos = "SELECT id_producto FROM tb_almacen WHERE codigo = :codigo";
    $query_productos = $pdo->prepare($sql_productos);
    $query_productos->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $query_productos->execute();
    $producto = $query_productos->fetch(PDO::FETCH_ASSOC);
    
    if (!$producto) {
        renderTabla($pdo);
        exit("<script>alert('Producto no encontrado');</script>");
    }

    $producto_id = $producto['id_producto'];
    $session_id = session_id();
    $fechaHora = date('Y-m-d H:i:s');

    // Verificar si ya existe en tmp_compras
    $sql_verificar = "SELECT id, cantidad FROM tmp_compras WHERE producto_id = :producto_id AND session_id = :session_id";
    $query_verificar = $pdo->prepare($sql_verificar);
    $query_verificar->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
    $query_verificar->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $query_verificar->execute();
    $existe = $query_verificar->fetch(PDO::FETCH_ASSOC);

    if ($existe) {
        // Actualizar la cantidad existente
        $nueva_cantidad = $existe['cantidad'] + $cantidad;
        $sql_actualizar = "UPDATE tmp_compras SET cantidad = :cantidad, fyh_creacion = :fyh_creacion WHERE id = :id";
        $query_actualizar = $pdo->prepare($sql_actualizar);
        $query_actualizar->bindParam(':cantidad', $nueva_cantidad, PDO::PARAM_INT);
        $query_actualizar->bindParam(':fyh_creacion', $fechaHora);
        $query_actualizar->bindParam(':id', $existe['id'], PDO::PARAM_INT);
        $query_actualizar->execute();
    } else {
        // Insertar nuevo registro
        $sql_insertar = "INSERT INTO tmp_compras (cantidad, producto_id, session_id, fyh_creacion) VALUES (:cantidad, :producto_id, :session_id, :fyh_creacion)";
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
    $sql_tm_compras = "SELECT tmp.id, tmp.cantidad, pro.codigo, pro.nombre, pro.precio_compra FROM tmp_compras AS tmp INNER JOIN tb_almacen AS pro ON pro.id_producto = tmp.producto_id WHERE session_id = :session_id";
    $query_tm_compras = $pdo->prepare($sql_tm_compras);
    $query_tm_compras->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $query_tm_compras->execute();
    $tmp_compras = $query_tm_compras->fetchAll(PDO::FETCH_ASSOC);
    
    $total_cantidad = 0;
    $total_compra = 0;
    ?>
    <script>$('#tabla_compras').css('display','none');</script>
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
                <?php $cont = 1; foreach ($tmp_compras as $tmp_compra):
                    $sub_total_compra = $tmp_compra['precio_compra'] * $tmp_compra['cantidad'];
                    $total_cantidad += $tmp_compra['cantidad'];
                    $total_compra += $sub_total_compra;
                ?>
                <tr>
                    <td style="text-align: center"><?php echo $cont++; ?></td>
                    <td style="text-align: center"><?php echo $tmp_compra['codigo']; ?></td>
                    <td style="text-align: center"><?php echo $tmp_compra['cantidad']; ?></td>
                    <td><?php echo $tmp_compra['nombre']; ?></td>
                    <td style="text-align: center"><?php echo number_format($tmp_compra['precio_compra'], 2, ',', '.'); ?></td>
                    <td style="text-align: center"><?php echo number_format($sub_total_compra, 2, ',', '.'); ?></td>
                    <td style="text-align: center">
                        <a href="borrar_tmp_compra.php?id=<?php echo $tmp_compra['id']; ?>" class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right"><b>Total cantidad</b></td>
                    <td style="text-align: center"><b><?php echo $total_cantidad; ?></b></td>
                    <td colspan="2" style="text-align: right"><b>Total compra</b></td>
                    <td style="text-align: center"><b><?php echo number_format($total_compra, 2, ',', '.'); ?></b></td>
                </tr>
            </tfoot>
        </table>
        <script>$('#precio_total').val('<?php echo number_format($total_compra, 2, ',', '.'); ?>');</script>
    </div>
    <?php
}