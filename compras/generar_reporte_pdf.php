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
$pdf->SetAuthor('Sistema de Compras');
$pdf->SetTitle('Reporte de Compras');
$pdf->SetMargins(15, 25, 15);
$pdf->AddPage();

// Consultar compras en el rango
$sql_compras = "SELECT c.*, p.nombre_proveedor 
               FROM compras c
               INNER JOIN tb_proveedores p ON c.proveedor_id = p.id_proveedor
               WHERE c.fecha BETWEEN :fecha_inicio AND :fecha_fin
               ORDER BY c.fecha ASC";

$query_compras = $pdo->prepare($sql_compras);
$query_compras->bindParam(':fecha_inicio', $fecha_inicio);
$query_compras->bindParam(':fecha_fin', $fecha_fin);
$query_compras->execute();
$compras_datos = $query_compras->fetchAll(PDO::FETCH_ASSOC);

// Encabezado del reporte
$html = '<h1 style="text-align:center;">Reporte de Compras</h1>';
$html .= '<p style="text-align:center;">Desde: '.date('d/m/Y', strtotime($fecha_inicio)).' - Hasta: '.date('d/m/Y', strtotime($fecha_fin)).'</p>';

// Tabla de copras
$html .= '<table border="1" cellpadding="5" style="width:100%">
            <tr style="background-color:#f2f2f2;">
                <th width="10%"><center>Nro</center></th>
                <th width="20%"><center>Fecha</center></th>
                <th width="25%"><center>Proveedor</center></th>
                <th width="20%"><center>Comprobante</center></th>
                <th width="15%"><center>Total</center></th>
            </tr>';

$contador = 0;
$total_general = 0;

foreach ($compras_datos as $compras_dato) {
    $contador++;
    $id_compra = $compras_dato['id'];
    
    $html .= '<tr>
                <td><center>'.$contador.'</center></td>
                <td><center>'.$compras_dato['fecha'].'</center></td>
                <td>'.$compras_dato['nombre_proveedor'].'</td>
                <td><center>'.$compras_dato['comprobante'].'</center></td>
                <td align="right">'.$compras_dato['precio_total'].'</td>
              </tr>';
    
    $total_general += $compras_dato['precio_total'];

    // Detalle de productos si es reporte detallado
    if ($tipo_reporte === 'detallado') {
        $sql_carrito = "SELECT dc.*, pro.nombre as nombre_producto, pro.descripcion as descripcion, 
                        pro.precio_compra as precio_compra
                        FROM detalle_compras dc 
                        INNER JOIN tb_almacen pro ON dc.producto_id = pro.id_producto 
                        WHERE dc.compra_id = :id_compra 
                        ORDER BY dc.id ASC";
        
        $query_carrito = $pdo->prepare($sql_carrito);
        $query_carrito->bindParam(':id_compra', $id_compra, PDO::PARAM_INT);
        $query_carrito->execute();
        $carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);

        $cantidad_total = 0;
        $precio_total = 0;

        foreach ($carrito_datos as $carrito_dato) {
            $subtotal = $carrito_dato['cantidad'] * $carrito_dato['precio_compra'];
            
            $html .= '<tr>
                        <td colspan="4" align="right">
                            '.$carrito_dato['nombre_producto'].' ('.$carrito_dato['cantidad'].' Unid. x '.$carrito_dato['precio_compra'].' P. U.)
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