<?php
include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');

// Corrected query to fetch only purchase-related data
$sql_ventas = "SELECT c.*, p.nombre_cliente 
                FROM ventas c
                INNER JOIN tb_clientes p ON c.cliente_id = p.id_cliente
                ORDER BY c.id DESC";
$query_ventas = $pdo->prepare($sql_ventas);
$query_ventas->execute();
$ventas_datos = $query_ventas->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Listado de ventas actualizado</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Ventas registrados</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body" style="display: block;">
                            <div class="table table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                    <tr>
                                        <th><center>Nro</center></th>
                                        <th><center>Producto</center></th>
                                        <th><center>Fecha de venta</center></th>
                                        <th><center>Proveedor</center></th>
                                        <th><center>Comprobante</center></th>
                                        <th><center>Total Pagado</center></th>
                                        <th><center>Cantidad</center></th>
                                        <th><center>Acciones</center></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $contador = 0;
                                    foreach ($ventas_datos as $ventas_dato){
                                        $id_venta = $ventas_dato['id'];
                                        $id_cliente = $ventas_dato['cliente_id'];
                                        $contador++;
                                    ?>
                                        <tr>
                                            <td><center><?php echo $ventas_dato['id'];?></center></td>
                                            <td>
                                                <center>
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary"
                                                            data-toggle="modal" data-target="#Modal_productos<?php echo $id_venta; ?>">
                                                        <i class="fa fa-shopping-basket"></i> Productos
                                                    </button>

                                                    <!-- Modal for Products -->
                                                    <div class="modal fade" id="Modal_productos<?php echo $id_venta; ?>" tabindex="-1" role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="background-color: #08c2ec">
                                                                    <h5><b>Productos Vendidos</b></h5>                                                            
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-sm table-hover table-striped">
                                                                            <thead>
                                                                            <tr>
                                                                                <th style="background-color: #e7e7e7;text-align: center">Nro</th>
                                                                                <th style="background-color: #e7e7e7;text-align: center">Producto</th>
                                                                                <th style="background-color: #e7e7e7;text-align: center">Descripcion</th>
                                                                                <th style="background-color: #e7e7e7;text-align: center">Cantidad</th>
                                                                                <th style="background-color: #e7e7e7;text-align: center">Precio Unitario</th>
                                                                                <th style="background-color: #e7e7e7;text-align: center">Precio SubTotal</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php
                                                                            // Corrected query to fetch details for specific purchase
                                                                            $sql_carrito = "SELECT dc.*, pro.nombre as nombre_producto, pro.descripcion as descripcion, 
                                                                                            pro.precio_venta as precio_venta, pro.stock as stock, pro.id_producto as id_producto 
                                                                                            FROM detalle_ventas dc 
                                                                                            INNER JOIN tb_almacen pro ON dc.producto_id = pro.id_producto 
                                                                                            WHERE dc.venta_id = :id_venta 
                                                                                            ORDER BY dc.id ASC";
                                                                            $query_carrito = $pdo->prepare($sql_carrito);
                                                                            $query_carrito->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
                                                                            $query_carrito->execute();
                                                                            $carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);

                                                                            $contador_de_carrito = 0;
                                                                            $cantidad_total = 0;
                                                                            $precio_unitario_total = 0;
                                                                            $precio_total = 0;

                                                                            foreach ($carrito_datos as $carrito_dato){
                                                                                $contador_de_carrito++;
                                                                                $cantidad_total += $carrito_dato['cantidad'];
                                                                                $precio_unitario_total += floatval($carrito_dato['precio_venta']);
                                                                            ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <center><?php echo $contador_de_carrito; ?></center>
                                                                                        <input type="hidden" value="<?php echo $carrito_dato['id_producto']; ?>" id="id_producto<?php echo $contador_de_carrito; ?>">
                                                                                    </td>
                                                                                    <td><?php echo $carrito_dato['nombre_producto']; ?></td>
                                                                                    <td><?php echo $carrito_dato['descripcion']; ?></td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <span id="cantidad_carrito<?php echo $contador_de_carrito; ?>"><?php echo $carrito_dato['cantidad'];?></span>
                                                                                        </center>
                                                                                        <input type="hidden" value="<?php echo $carrito_dato['stock'];?>" id="stock_de_inventario<?php echo $contador_de_carrito; ?>">
                                                                                    </td>
                                                                                    <td><center><?php echo $carrito_dato['precio_venta'];?></center></td>
                                                                                    <td>
                                                                                        <center>
                                                                                            <?php
                                                                                            $cantidad = floatval($carrito_dato['cantidad']);
                                                                                            $precio_venta = floatval($carrito_dato['precio_venta']);
                                                                                            $subtotal = $cantidad * $precio_venta;
                                                                                            echo $subtotal;
                                                                                            $precio_total += $subtotal;
                                                                                            ?>
                                                                                        </center>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                            <tr>
                                                                                <th colspan="3" style="background-color: #e7e7e7;text-align: right">Total</th>
                                                                                <th><center><?php echo $cantidad_total; ?></center></th>
                                                                                <th colspan="2" style="background-color: #fff819"><center><?php echo $precio_total; ?></center></th>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </center>
                                            </td>
                                            <td><?php echo $ventas_dato['fecha'];?></td>
                                            <td>
                                                <center>
                                                    <!-- Provider Modal -->
                                                    <button type="button" class="btn btn-warning"
                                                            data-toggle="modal" data-target="#Modal_clientes<?php echo $id_venta; ?>">
                                                        <i class="fa fa-shopping-basket"></i> <?php echo $ventas_dato['nombre_cliente']; ?>
                                                    </button>

                                                    <div class="modal fade" id="Modal_clientes<?php echo $id_venta; ?>">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="background-color: #b6900c;color: white">
                                                                    <h4 class="modal-title">Cliente</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <?php
                                                                // Fetch provider details 
                                                                $sql_clientes = "SELECT * FROM tb_clientes WHERE id_cliente = :id_cliente";
                                                                $query_clientes = $pdo->prepare($sql_clientes);
                                                                $query_clientes->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
                                                                $query_clientes->execute();
                                                                $clientes_datos = $query_clientes->fetch(PDO::FETCH_ASSOC);
                                                                ?>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="">Nombre del cliente</label>
                                                                        <input type="text" value="<?php echo $clientes_datos['nombre_cliente']; ?>" class="form-control" disabled>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">DNI del cliente</label>
                                                                        <input type="text" value="<?php echo $clientes_datos['DNI_cliente']; ?>" class="form-control" disabled>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">Celular del cliente</label>
                                                                        <input type="text" value="<?php echo $clientes_datos['celular_cliente']; ?>" class="form-control" disabled>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">Correo del cliente</label>
                                                                        <input type="email" value="<?php echo $clientes_datos['email_cliente']; ?>" class="form-control" disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </center>
                                            </td>
                                            <td><?php echo $ventas_dato['comprobante'];?></td>
                                            <td><?php echo $ventas_dato['total_pagado'];?></td>
                                            <td><?php echo $cantidad_total;?></td>
                                            <td>
    <center>
        <!-- Botón para eliminar con confirmación -->
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmarEliminar<?php echo $id_venta; ?>">
            <i class="fa fa-trash"></i> Borrar
        </button>
        
        <!-- Modal de confirmación -->
        <div class="modal fade" id="confirmarEliminar<?php echo $id_venta; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar esta venta?<br>
                        <strong>Fecha:</strong> <?php echo $ventas_dato['fecha']; ?><br>
                        <strong>Cliente:</strong> <?php echo $ventas_dato['nombre_cliente']; ?><br>
                        <strong>Total:</strong> <?php echo $ventas_dato['total_pagado']; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <a href="delete.php?id_venta=<?php echo $id_venta; ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>
    </center>
</td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ('../layout/mensajes.php'); ?>
<?php include ('../layout/parte2.php'); ?>

<script>
    $(function () {
        $("#example1").DataTable({
            "pageLength": 5,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Ventas",
                "infoEmpty": "Mostrando 0 a 0 de 0 Ventas",
                "infoFiltered": "(Filtrado de _MAX_ total Ventas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Ventas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscador:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "responsive": true, "lengthChange": true, "autoWidth": false,
            buttons: [{
                extend: 'collection',
                text: 'Reportes',
                orientation: 'landscape',
                buttons: [{
                    text: 'Copiar',
                    extend: 'copy',
                }, {
                    extend: 'pdf'
                },{
                    extend: 'csv'
                },{
                    extend: 'excel'
                },{
                    text: 'Imprimir',
                    extend: 'print'
                }
                ]
            },
                {
                    extend: 'colvis',
                    text: 'Visor de columnas',
                    collectionLayout: 'fixed three-column'
                }
            ],
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>