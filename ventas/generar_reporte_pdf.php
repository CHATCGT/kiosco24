<?php
require_once('../app/config.php');
require_once('../app/TCPDF-main/tcpdf.php');

// Validar método de envío
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido directamente');
}

// Obtener parámetros
$fecha_inicio = $_POST['fecha_inicio'] ?? null;
$fecha_fin = $_POST['fecha_fin'] ?? null;
$tipo_reporte = $_POST['tipo_reporte'] ?? 'detallado';

// Validar fechas
if (empty($fecha_inicio) || empty($fecha_fin)) {
    die('Debe especificar ambas fechas');
}

if ($fecha_inicio > $fecha_fin) {
    die('La fecha de inicio no puede ser mayor a la fecha final');
}

// Crear PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Ventas');
$pdf->SetTitle('Reporte de Ventas');
$pdf->SetMargins(15, 25, 15);
$pdf->AddPage();

// Consultar ventas en el rango
$sql_ventas = "SELECT v.*, c.nombre_cliente 
               FROM ventas v
               INNER JOIN tb_clientes c ON v.cliente_id = c.id_cliente
               WHERE v.fecha BETWEEN :fecha_inicio AND :fecha_fin
               ORDER BY v.fecha ASC";

$query_ventas = $pdo->prepare($sql_ventas);
$query_ventas->bindParam(':fecha_inicio', $fecha_inicio);
$query_ventas->bindParam(':fecha_fin', $fecha_fin);
$query_ventas->execute();
$ventas_datos = $query_ventas->fetchAll(PDO::FETCH_ASSOC);

// Encabezado del reporte
$html = '<h1 style="text-align:center;">Reporte de Ventas</h1>';
$html .= '<p style="text-align:center;">Desde: '.date('d/m/Y', strtotime($fecha_inicio)).' - Hasta: '.date('d/m/Y', strtotime($fecha_fin)).'</p>';

// Tabla de ventas
$html .= '<table border="1" cellpadding="5" style="width:100%">
            <tr style="background-color:#f2f2f2;">
                <th width="10%"><center>Nro</center></th>
                <th width="20%"><center>Fecha</center></th>
                <th width="25%"><center>Cliente</center></th>
                <th width="15%"><center>Comprobante</center></th>
                <th width="15%"><center>Total</center></th>
            </tr>';

$contador = 0;
$total_general = 0;

foreach ($ventas_datos as $ventas_dato) {
    $contador++;
    $id_venta = $ventas_dato['id'];
    
    $html .= '<tr>
                <td><center>'.$contador.'</center></td>
                <td><center>'.$ventas_dato['fecha'].'</center></td>
                <td>'.$ventas_dato['nombre_cliente'].'</td>
                <td><center>'.$ventas_dato['comprobante'].'</center></td>
                <td align="right">'.$ventas_dato['total_pagado'].'</td>
              </tr>';
    
    $total_general += $ventas_dato['total_pagado'];

    // Detalle de productos si es reporte detallado
    if ($tipo_reporte === 'detallado') {
        $sql_carrito = "SELECT dc.*, pro.nombre as nombre_producto, pro.descripcion as descripcion, 
                        pro.precio_venta as precio_venta
                        FROM detalle_ventas dc 
                        INNER JOIN tb_almacen pro ON dc.producto_id = pro.id_producto 
                        WHERE dc.venta_id = :id_venta 
                        ORDER BY dc.id ASC";
        
        $query_carrito = $pdo->prepare($sql_carrito);
        $query_carrito->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
        $query_carrito->execute();
        $carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);

        $cantidad_total = 0;
        $precio_total = 0;

        foreach ($carrito_datos as $carrito_dato) {
            $subtotal = $carrito_dato['cantidad'] * $carrito_dato['precio_venta'];
            
            $html .= '<tr>
                        <td colspan="4" align="right">
                            '.$carrito_dato['nombre_producto'].' ('.$carrito_dato['cantidad'].' x '.$carrito_dato['precio_venta'].')
                        </td>
                        <td align="right">'.$subtotal.'</td>
                      </tr>';
            
            $cantidad_total += $carrito_dato['cantidad'];
            $precio_total += $subtotal;
        }
    }
}

// Total general
$html .= '<tr style="background-color:#f2f2f2;">
            <td colspan="4" align="right"><strong>TOTAL GENERAL</strong></td>
            <td align="right"><strong>'.number_format($total_general, 2).'</strong></td>
          </tr>';

$html .= '</table>';

// Generar PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reporte_ventas_'.date('Ymd_His').'.pdf', 'I');