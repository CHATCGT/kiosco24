<?php
$sql_compras = "SELECT*,
                pro.codigo as codigo, 
                pro.nombre as nombre_producto, 
                pro.descripcion as descripcion, 
                pro.stock as stock, 
                pro.stock_minimo as stock_minimo, 
                pro.stock_maximo as stock_maximo, 
                pro.precio_compra as precio_compra_producto,
                pro.precio_venta as precio_venta_producto, 
                pro.fecha_ingreso as fecha_ingreso,
                pro.imagen as imagen,
                dc.cantidad as cantidad,
                cat.nombre_categoria as nombre_categoria,
                prov.nombre_proveedor as nombre_proveedor,
                prov.celular as celular_proveedor, 
                prov.telefono as telefono_proveedor,
                prov.empresa as empresa,
                prov.email as email_proveedor,
                prov.direccion as direccion_proveedor,
                us.nombres as nombre_usuarios_producto
                FROM compras as co 
                INNER JOIN detalle_compras as dc ON co.id = dc.compra_id  -- Relación entre compras y detalle_compras
                INNER JOIN tb_almacen as pro ON dc.producto_id = pro.id_producto  -- Relación entre detalle_compras y tb_almacen
                INNER JOIN tb_categorias as cat ON pro.id_categoria = cat.id_categoria
                
                INNER JOIN tb_usuarios as us ON pro.id_usuario = us.id_usuario 
                INNER JOIN tb_proveedores as prov ON co.proveedor_id = prov.id_proveedor";

$query_compras = $pdo->prepare($sql_compras);
$query_compras->execute();
$compras_datos = $query_compras->fetchAll(PDO::FETCH_ASSOC);
