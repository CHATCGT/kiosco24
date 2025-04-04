<?php
include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');

// Function to fetch providers
function obtenerProveedores($pdo) {
    $sql_proveedores = "SELECT * FROM tb_proveedores";
    $query_proveedores = $pdo->prepare($sql_proveedores);
    $query_proveedores->execute();
    return $query_proveedores->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch all products
function obtenerTodosProductos($pdo) {
    $sql_productos = "SELECT 
        a.id_producto, 
        a.codigo, 
        a.nombre, 
        a.stock, 
        a.precio_compra,
        p.nombre_proveedor
    FROM 
        tb_almacen a
    JOIN 
        tb_proveedores p ON a.id_proveedor = p.id_proveedor
    ORDER BY 
        p.nombre_proveedor, a.nombre";
    
    $query_productos = $pdo->prepare($sql_productos);
    $query_productos->execute();
    return $query_productos->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch products by provider
function obtenerProductosPorProveedor($pdo, $id_proveedor) {
    $sql_productos = "SELECT 
        a.id_producto, 
        a.codigo, 
        a.nombre, 
        a.stock, 
        a.precio_compra,
        p.nombre_proveedor
    FROM 
        tb_almacen a
    JOIN 
        tb_proveedores p ON a.id_proveedor = p.id_proveedor
    WHERE 
        a.id_proveedor = :id_proveedor";
    
    $query_productos = $pdo->prepare($sql_productos);
    $query_productos->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);
    $query_productos->execute();
    return $query_productos->fetchAll(PDO::FETCH_ASSOC);
}

// Get all providers and all products
$proveedores_datos = obtenerProveedores($pdo);
$todos_productos = obtenerTodosProductos($pdo);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Modificar Precios</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Selección de Método de Modificación de Precios</h3>
                        </div>

                        <div class="card-body">
                            <ul class="nav nav-tabs" id="metodosModificacion" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="proveedor-tab" data-toggle="tab" href="#proveedor" role="tab">Por Proveedor</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="producto-tab" data-toggle="tab" href="#producto" role="tab">Por Producto</a>
                                </li>
                            </ul>

                            <div class="tab-content mt-3" id="metodosContent">
                                <!-- Pestaña de Proveedor -->
                                <div class="tab-pane fade show active" id="proveedor" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#proveedorModal">
                                                        <i class="fas fa-search"></i> Buscar Proveedor
                                                    </button>
                                                    
                                                    <!-- Modal de Proveedores -->
                                                    <div class="modal fade" id="proveedorModal" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Listado de Proveedores</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table id="tablaProveedores" class="table table-striped table-bordered table-hover table-sm">
                                                                        <thead class="thead-light">
                                                                            <tr>
                                                                                <th>Nro</th>
                                                                                <th>Acción</th>
                                                                                <th>Empresa</th>
                                                                                <th>Teléfono</th>
                                                                                <th>Nombre del Proveedor</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach($proveedores_datos as $index => $proveedor): ?>
                                                                            <tr>
                                                                                <td><?= $index + 1 ?></td>
                                                                                <td>
                                                                                    <button type="button" 
                                                                                            class="btn btn-info seleccionar-proveedor" 
                                                                                            data-id="<?= $proveedor['id_proveedor'] ?>"
                                                                                            data-nombre="<?= htmlspecialchars($proveedor['nombre_proveedor']) ?>">
                                                                                        Seleccionar
                                                                                    </button>
                                                                                </td>
                                                                                <td><?= $proveedor['empresa'] ?></td>
                                                                                <td><?= $proveedor['telefono'] ?></td>
                                                                                <td><?= $proveedor['nombre_proveedor'] ?></td>
                                                                            </tr>
                                                                            <?php endforeach; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="empresa_proveedor">Proveedor Seleccionado</label>
                                                    <input type="text" class="form-control" id="empresa_proveedor" disabled>
                                                    <input type="hidden" id="id_proveedor" name="proveedor_id">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="porcentaje_aumento">% de Aumento</label><b> *</b>
                                                        <input type="number" class="form-control" id="porcentaje_aumento_proveedor" min="0" max="100" step="0.1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button id="aplicar_cambios_proveedor" class="btn btn-primary btn-lg btn-block" disabled>
                                                            <i class="fas fa-save"></i> Registrar cambios por Proveedor
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <table id="tablaProductos" class="table table-sm table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr style="background-color: #cccccc">
                                                        <th>Nro</th>
                                                        <th>Código</th>
                                                        <th>Nombre</th>
                                                        <th>Cantidad</th>
                                                        <th>Costo Actual</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="productos_tbody_proveedor">
                                                    <!-- Productos se cargarán dinámicamente aquí -->
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" style="text-align: center"><b>Total Productos</b></td>
                                                        <td id="total_cantidad_proveedor" colspan="2" style="text-align: center"><b>0</b></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pestaña de Producto -->
                                <div class="tab-pane fade" id="producto" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#productosModal">
                                                        <i class="fas fa-search"></i> Buscar Producto
                                                    </button>
                                                    
                                                    <!-- Modal de Productos -->
                                                    <div class="modal fade" id="productosModal" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Listado de Productos</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <table id="tablaProductosModal" class="table table-striped table-bordered table-hover table-sm">
                                                                        <thead class="thead-light">
                                                                            <tr>
                                                                                <th>Nro</th>
                                                                                <th>Acción</th>
                                                                                <th>Código</th>
                                                                                <th>Nombre</th>
                                                                                <th>Proveedor</th>
                                                                                <th>Costo Actual</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach($todos_productos as $index => $producto): ?>
                                                                            <tr>
                                                                                <td><?= $index + 1 ?></td>
                                                                                <td>
                                                                                    <button type="button" 
                                                                                            class="btn btn-info seleccionar-producto" 
                                                                                            data-id="<?= $producto['id_producto'] ?>"
                                                                                            data-nombre="<?= htmlspecialchars($producto['nombre']) ?>"
                                                                                            data-costo="<?= $producto['precio_compra'] ?>"
                                                                                            data-proveedor="<?= htmlspecialchars($producto['nombre_proveedor']) ?>">
                                                                                        Seleccionar
                                                                                    </button>
                                                                                </td>
                                                                                <td><?= $producto['codigo'] ?></td>
                                                                                <td><?= $producto['nombre'] ?></td>
                                                                                <td><?= $producto['nombre_proveedor'] ?></td>
                                                                                <td><?= $producto['precio_compra'] ?></td>
                                                                            </tr>
                                                                            <?php endforeach; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <label for="producto_seleccionado">Producto Seleccionado</label>
                                                    <input type="text" class="form-control" id="producto_seleccionado" disabled>
                                                    <input type="hidden" id="id_producto" name="producto_id">
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <label for="proveedor_producto">Proveedor</label>
                                                    <input type="text" class="form-control" id="proveedor_producto" disabled>
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <label for="costo_actual_producto">Costo Actual</label>
                                                    <input type="text" class="form-control" id="costo_actual_producto" disabled>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="porcentaje_aumento_individual">% de Aumento</label><b> *</b>
                                                        <input type="number" class="form-control" id="porcentaje_aumento_individual" min="0" max="100" step="0.1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button id="aplicar_cambios_producto" class="btn btn-primary btn-lg btn-block" disabled>
                                                            <i class="fas fa-save"></i> Registrar cambios de Producto
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <!-- Esta sección podría usarse para mostrar detalles adicionales del producto si es necesario -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Proveedor tab functionality
    $('.seleccionar-proveedor').on('click', function() {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        
        // Set provider details
        $('#id_proveedor').val(id);
        $('#empresa_proveedor').val(nombre);
        
        // Close modal
        $('#proveedorModal').modal('hide');
        
        // Fetch products for this provider
        $.ajax({
            url: '../app/controllers/almacen/obtener_productos_por_proveedor.php',
            method: 'POST',
            data: { id_proveedor: id },
            dataType: 'json',
            success: function(response) {
                let tbody = $('#productos_tbody_proveedor');
                let total_cantidad = 0;
                
                // Clear previous results
                tbody.empty();
                
                // Populate table
                response.forEach((producto, index) => {
                    let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${producto.codigo}</td>
                            <td>${producto.nombre}</td>
                            <td>${producto.stock}</td>
                            <td>${producto.precio_compra}</td>
                        </tr>
                    `;
                    tbody.append(row);
                    total_cantidad += parseInt(producto.stock);
                });
                
                // Update total quantity
                $('#total_cantidad_proveedor').text(total_cantidad);
                
                // Enable apply changes button
                $('#aplicar_cambios_proveedor').prop('disabled', false);
            },
            error: function() {
                alert('Error al obtener productos');
            }
        });
    });

    // Producto tab functionality
    $('.seleccionar-producto').on('click', function() {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let costo = $(this).data('costo');
        let proveedor = $(this).data('proveedor');
        
        // Set product details
        $('#id_producto').val(id);
        $('#producto_seleccionado').val(nombre);
        $('#proveedor_producto').val(proveedor);
        $('#costo_actual_producto').val(costo);
        
        // Close modal
        $('#productosModal').modal('hide');
        
        // Enable apply changes button
        $('#aplicar_cambios_producto').prop('disabled', false);
    });

    // Apply price changes for provider
    $('#aplicar_cambios_proveedor').on('click', function() {
        let id_proveedor = $('#id_proveedor').val();
        let porcentaje_aumento = $('#porcentaje_aumento_proveedor').val();
        
        if (!id_proveedor || !porcentaje_aumento) {
            alert('Por favor, seleccione un proveedor y un porcentaje de aumento');
            return;
        }
        
        $.ajax({
            url: '../app/controllers/almacen/actualizar_precios.php',
            method: 'POST',
            data: {
                id_proveedor: id_proveedor,
                porcentaje_aumento: porcentaje_aumento
            },
            success: function(response) {
                alert('Precios actualizados correctamente');
                location.reload(); // Reload page to refresh data
            },
            error: function() {
                alert('Error al actualizar precios');
            }
        });
    });

    // Apply price changes for individual product
    $('#aplicar_cambios_producto').on('click', function() {
        let id_producto = $('#id_producto').val();
        let porcentaje_aumento = $('#porcentaje_aumento_individual').val();
        
        if (!id_producto || !porcentaje_aumento) {
            alert('Por favor, seleccione un producto y un porcentaje de aumento');
            return;
        }
        
        $.ajax({
            url: '../app/controllers/almacen/actualizar_precio_producto.php',
            method: 'POST',
            data: {
                id_producto: id_producto,
                porcentaje_aumento: porcentaje_aumento
            },
            success: function(response) {
                alert('Precio de producto actualizado correctamente');
                location.reload(); // Reload page to refresh data
            },
            error: function() {
                alert('Error al actualizar precio del producto');
            }
        });
    });

    // Initialize DataTables for modals
    $('#tablaProveedores, #tablaProductosModal').DataTable({
        "pageLength": 10,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        }
    });
});
</script>

<?php include ('../layout/mensajes.php'); ?>
<?php include ('../layout/parte2.php'); ?>