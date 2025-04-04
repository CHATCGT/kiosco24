<?php
include ('../app/config.php');
include ('../layout/sesion.php');

include ('../layout/parte1.php');

include ('../app/controllers/almacen/listado_de_productos.php');
include ('../app/controllers/proveedores/listado_de_proveedores.php');
//include ('../app/controllers/compras/listado_de_compras.php');

?>

<!-- jQuery UI CSS for autocomplete -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<!-- jQuery UI JS for autocomplete -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Registro de Compras</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
        <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Ingrese los datos</h3>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="cantidad">Cantidad</label><b> *</b>
                                            <input type="number" style="text-align: center;background-color: #ebe7ae" class="form-control" id="cantidad" value="1" name="cantidad" required>
                                            <small style="color: red;">Introduce cantidad</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    
                                        <label for="">Buscar por código o nombre</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            </div>
                                            <input id="codigo" type="text" class="form-control" placeholder="Escriba código o nombre del producto">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div style="height: 32px"></div>

                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                            <i class="fas fa-search"></i>
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Listado de productos</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                            <table id="example1" class="table table-striped table-bordered table-hover table-sm table-responsive">
                                                                <thead class="thead-light">
                                                                <tr>
                                                                    <th scope="col" style="text-align: center">Nro</th>
                                                                    <th scope="col" style="text-align: center">Acción</th>
                                                                    <th scope="col">Categoría</th>
                                                                    <th scope="col">Código</th>
                                                                    <th scope="col">Nombre</th>
                                                                    <th scope="col">Descripción</th>
                                                                    <th scope="col">Stock</th>
                                                                    <th scope="col">Precio compra</th>
                                                                    <th scope="col">Precio venta</th>
                                                                    <th scope="col">Imagen</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php $contador = 1;
                                                                foreach($productos_datos as $producto){
                                                                    $id_producto = $producto['id_producto']; ?>
                                                                    <tr>
                                                                        <td style="text-align: center;vertical-align: middle"><?= $contador++;?></td>
                                                                        <td style="text-align: center;vertical-align: middle">
                                                                            <button type="button" id="btn_seleccionar<?= $producto['id_producto'];?>" 
                                                                            class="btn btn-info seleccionar-btn" 
                                                                            data-id="<?= $producto['id_producto'];?>"
                                                                            data-codigo="<?= $producto['codigo'];?>" >Seleccionar</button>
                                                                            <script>
                                                                                $(document).ready(function () {
                                                                                    $('#btn_seleccionar<?= $producto['id_producto'];?>').click(function () {
                                                                                        var id_producto = $(this).data('id');
                                                                                        var codigo = $(this).data('codigo');
                                                                                        var cantidad = $('#cantidad').val();
                
                                                                                       
                                                                                            var url = "buscar_producto.php";
                                                                                            $.get(url,{codigo:codigo,cantidad:cantidad},function (datos) {
                                                                                            $('#respuesta_buscar_producto').html(datos);
                                                                                            
                                                                                            $('#producto_agregado').css('display','block');
                                                                                            setTimeout(function() {
                                                                                                $('#producto_agregado').css('display', 'none');
                                                                                            }, 2000);
                                                                                                                                                                                        
                                                                                            $('#codigo').val('');
                                                                                                //alert("mando los datos");
                                                                                             });
                                                                                        
                                                                                    });
                                                                                });
                                                                            </script>
                                                                        </td>
                                                                        <td style="vertical-align: middle"><?= $producto['categoria'];?></td>
                                                                        <td style="vertical-align: middle"><?= $producto['codigo'];?></td>
                                                                        <td style="vertical-align: middle"><?= $producto['nombre'];?></td>
                                                                        <td style="vertical-align: middle"><?= $producto['descripcion'];?></td>
                                                                        <td style="text-align: center;vertical-align: middle;background-color: rgba(233,231,16,0.15)"><?= $producto['stock'];?></td>
                                                                        <td style="text-align: center;vertical-align: middle"><?= $producto['precio_compra'];?></td>
                                                                        <td style="text-align: center;vertical-align: middle"><?= $producto['precio_venta'];?></td>
                                                                        <td style="text-align: center">
                                                                        <img src="<?php echo $URL."/almacen/img_productos/".$producto['imagen'];?>" width="50px" alt="asdf">
                                                                        </td>
                                                                    </tr>
                                                                <?php  }         ?>
                                                                </tbody>
                                                            </table>
                                                            <div class="alert alert-default-success" id="producto_agregado" style="display: none;">Producto agregado</div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        </div>
                                                </div>
                                            </div>
                                            </div>

                                            
                                            <a href="../almacen/create.php" type="button" class="btn btn-success"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>

                                
                                
                                <div class="row">
                                    <div class="col-md-12" id="respuesta_buscar_producto">
                                     
                                    </div>
                                    <div class="col-md-12" id="tabla_compras">
                                      <table class="table table-sm table-striped table-bordered table-hover">
                                            <thead>
                                            <tr style="background-color: #cccccc">
                                                <th>Nro</th>
                                                <th>Codígo</th>
                                                <th>Cantidad</th>
                                                <th>Nombre</th>
                                                <th>Costo</th>
                                                <th>Total</th>
                                                <th>Acción</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $cont = 1; $total_cantidad = 0; $total_compra = 0;
                                            $session_id = session_id();
                                            $sql_tm_compras = "SELECT * FROM tmp_compras as tmp 
                                                               INNER JOIN tb_almacen as pro ON pro.id_producto = tmp.producto_id  WHERE session_id = '$session_id'";
                                            $query_tm_compras = $pdo->prepare($sql_tm_compras);
                                            $query_tm_compras->execute();
                                            $tmp_compras = $query_tm_compras->fetchAll(PDO::FETCH_ASSOC);

                                            //echo print_r($tmp_compras);

                                            foreach ($tmp_compras as $tmp_compra){
                                                $total_cantidad += $tmp_compra['cantidad'];
                                                $sub_total_compra = $tmp_compra['precio_venta'] * $tmp_compra['cantidad'];
                                                $total_compra += $sub_total_compra;
                                                ?>
                                                <tr>
                                                    <td style="text-align: center"><?php echo $cont++;?></td>
                                                    <td style="text-align: center"><?php echo $tmp_compra['codigo'];?></td>
                                                    <td style="text-align: center"><?php echo $tmp_compra['cantidad'];?></td>
                                                    <td><?php echo $tmp_compra['nombre'];?></td>
                                                    <td style="text-align: center"><?php echo number_format($tmp_compra['precio_venta'],2,',','.');?></td>
                                                    <td style="text-align: center"><?php echo number_format($sub_total_compra,2,',','.');?></td>
                                                    <td style="text-align: center">
                                                        <a href="borrar_tmp_compra.php?id=<?php echo $tmp_compra['id'];?>" class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                            <tfooter>
                                                <tr>
                                                    <td colspan="2" style="text-align: right"><b>Total cantidad</b></td>
                                                    <td style="text-align: center"><b><?php echo $total_cantidad; ?></b></td>
                                                    <td colspan="2" style="text-align: right"><b>Total compra</b></td>
                                                    <td style="text-align: center"><b><?php echo $total_compra; ?></b></td>
                                                </tr>
                                            </tfooter>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                
                            <form action="create_compras.php" method="post">
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal_proveedor"><i class="fas fa-search"></i> Buscar proveedor</button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal_proveedor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Listado de proveedores</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                            <table id="example2" class="table table-striped table-bordered table-hover table-sm table-responsive">
                                                            <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col" style="text-align: center">Nro</th>
                                                                <th scope="col" style="text-align: center">Acción</th>
                                                                <th scope="col">Empresa</th>
                                                                <th scope="col">Teléfono</th>
                                                                <th scope="col">Nombre del proveedor</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php $contador = 1;
                                                            foreach($proveedores_datos as $proveedor){
                                                                ?>
                                                                <tr>
                                                                    <td style="text-align: center;vertical-align: middle"><?=$contador++;?></td>
                                                                    <td style="text-align: center;vertical-align: middle">
                                                                        <button type="button" class="btn btn-info" 
                                                                        id="seleccionar-btn-proveedor<?=$proveedor['id_proveedor'];?>" >Seleccionar</button>
                                                                        <script>
                                                                            $('#seleccionar-btn-proveedor<?=$proveedor['id_proveedor'];?>').click(function () {
                                                                                $('#empresa_proveedor').val('<?=$proveedor['empresa'];?>');
                                                                                $('#id_proveedor').val('<?=$proveedor['id_proveedor'];?>');
                                                                                $('#exampleModal_proveedor').modal('hide');
                                                                            });
                                                                        </script>
                                                                    </td>
                                                                    <td style="vertical-align: middle"><?=$proveedor['empresa'];?></td>
                                                                    <td style="vertical-align: middle"><?=$proveedor['telefono'];?></td>
                                                                    <td style="vertical-align: middle"><?=$proveedor['nombre_proveedor'];?></td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="empresa_proveedor" disabled>
                                        <input type="text" class="form-control" id="id_proveedor" name="proveedor_id" required hidden>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha">Fecha de compra</label><b> *</b>
                                            <input type="date" class="form-control" value="" name="fecha" required>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha">Comprobante</label><b> *</b>
                                            <input type="text" class="form-control" name="comprobante" required>  
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="fecha">Precio total</label><b> *</b>
                                            <input type="text" style="text-align: center;background-color: #e9e710" id="precio_total" name="precio_total" value="<?php echo $total_compra; ?>" class="form-control" required >
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fas fa-save"></i> Registrar compra</button>
                                        </div>
                                    </div>
                                </div>

                            </form>


                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include ('../layout/mensajes.php'); ?>
<?php include ('../layout/parte2.php'); ?>

<script>
    // Autocompletar para búsqueda de productos por código o nombre
    $(document).ready(function() {
        $('#codigo').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: 'buscar_producto_autocomplete.php',
                    dataType: 'json',
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var codigo = ui.item.codigo;
                var cantidad = $('#cantidad').val();
                
                var url = "buscar_producto.php";
                $.get(url, {codigo: codigo, cantidad: cantidad}, function(datos) {
                    $('#respuesta_buscar_producto').html(datos);
                    
                    $('#producto_agregado').css('display', 'block');
                    setTimeout(function() {
                        $('#producto_agregado').css('display', 'none');
                    }, 2000);
                    
                    $('#codigo').val('');
                });
                
                return false;
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>")
                .append("<div><strong>" + item.codigo + "</strong> - " + item.nombre + "</div>")
                .appendTo(ul);
        };
    });

    // Mantener el enfoque en el campo de búsqueda
    $('#codigo').focus();
    
    // Conservar la funcionalidad original con tecla Enter
    $('#codigo').on('keyup', function(e) {
        if(e.which === 13) {
            var codigo = $(this).val();
            var cantidad = $('#cantidad').val();
            
            if(codigo.length > 0) {
                var url = "buscar_producto.php";
                $.get(url, {codigo: codigo, cantidad: cantidad}, function(datos) {
                    $('#respuesta_buscar_producto').html(datos);
                    $('#codigo').val('');
                });
            } else {
                alert('Ingrese un código o nombre');
            }
        }
    });
</script>

<script>
    $(function () {
        $("#example1").DataTable({
            "pageLength": 5,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Productos",
                "infoEmpty": "Mostrando 0 a 0 de 0 Productos",
                "infoFiltered": "(Filtrado de _MAX_ total Productos)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Productos",
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

        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });


    $(function () {
        $("#example2").DataTable({
            "pageLength": 5,
            "language": {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Proveedores",
                "infoEmpty": "Mostrando 0 a 0 de 0 Proveedores",
                "infoFiltered": "(Filtrado de _MAX_ total Proveedores)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Proveedores",
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

        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>