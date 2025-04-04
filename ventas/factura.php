<?php
require_once('../app/TCPDF-main/tcpdf.php');
include('../app/config.php');
include('../app/controllers/ventas/literal.php');

session_start();
if(!isset($_SESSION['sesion_email'])) {
    header('Location: '.$URL.'/login');
    exit();
}

// Obtener datos del usuario
$email_sesion = $_SESSION['sesion_email'];
$sql = "SELECT us.id_usuario as id_usuario, us.nombres as nombres, us.email as email, rol.rol as rol 
        FROM tb_usuarios as us INNER JOIN tb_roles as rol ON us.id_rol = rol.id_rol WHERE email = ?";
$query = $pdo->prepare($sql);
$query->execute([$email_sesion]);
$usuario = $query->fetch(PDO::FETCH_ASSOC);

if(!$usuario) {
    header('Location: '.$URL.'/login');
    exit();
}

$id_usuario_sesion = $usuario['id_usuario'];
$nombres_sesion = $usuario['nombres'];
$rol_sesion = $usuario['rol'];

// Obtener ID de venta
if(!isset($_GET['venta_id'])) {
    die("ID de venta no especificado");
}
$id_venta_get = $_GET['venta_id'];

// Obtener datos generales de la venta
$sql_ventas = "SELECT ve.*, cli.nombre_cliente, cli.nit_ci_cliente 
               FROM ventas as ve 
               INNER JOIN tb_clientes as cli ON cli.id_cliente = ve.cliente_id 
               WHERE ve.id_venta = ?";
$query_ventas = $pdo->prepare($sql_ventas);
$query_ventas->execute([$id_venta_get]);
$venta = $query_ventas->fetch(PDO::FETCH_ASSOC);

if(!$venta) {
    die("Venta no encontrada");
}

$fyh_creacion = $venta['fyh_creacion'];
$nit_ci_cliente = $venta['nit_ci_cliente'];
$nombre_cliente = $venta['nombre_cliente'];
$total_pagado = $venta['total_pagado'];

// Convertir precio total a literal
$monto_literal = numtoletras($total_pagado);
$fecha = date("d/m/Y", strtotime($fyh_creacion));

// Crear PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215,279), true, 'UTF-8', false);

// Configuración del documento
$pdf->setCreator('Sistema de Ventas');
$pdf->setAuthor('Sistema de Ventas');
$pdf->setTitle('Factura #'.$id_venta_get);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->setMargins(15, 15, 15);
$pdf->setAutoPageBreak(true, 5);
$pdf->setFont('Helvetica', '', 12);
$pdf->AddPage();

// Obtener detalles de los productos vendidos
$sql_carrito = "SELECT dv.*, pro.nombre as nombre_producto, pro.descripcion, pro.precio_venta 
                FROM detalle_ventas AS dv 
                INNER JOIN tb_almacen as pro ON dv.producto_id = pro.id_producto 
                WHERE dv.venta_id = ? 
                ORDER BY dv.id ASC";

$query_carrito = $pdo->prepare($sql_carrito);
$query_carrito->execute([$id_venta_get]);
$carrito_datos = $query_carrito->fetchAll(PDO::FETCH_ASSOC);

// Calcular totales
$contador_de_carrito = 0;
$cantidad_total = 0;
$precio_total = 0;

// Construir tabla de productos
$productos_html = '';
foreach ($carrito_datos as $carrito_dato) {
    $contador_de_carrito++;
    $cantidad_total += $carrito_dato['cantidad'];
    $subtotal = $carrito_dato['cantidad'] * $carrito_dato['precio_venta'];
    $precio_total += $subtotal;

    $productos_html .= '
    <tr>
        <td style="text-align: center">'.$contador_de_carrito.'</td>
        <td>'.$carrito_dato['nombre_producto'].'</td>
        <td>'.$carrito_dato['descripcion'].'</td>
        <td style="text-align: center">'.$carrito_dato['cantidad'].'</td>
        <td style="text-align: center">Bs. '.number_format($carrito_dato['precio_venta'], 2).'</td>
        <td style="text-align: center">Bs. '.number_format($subtotal, 2).'</td>
    </tr>';
}

// HTML completo
$html = '
<table border="0" style="font-size: 10px">
    <tr>
        <td style="text-align: center;width: 230px">
            <img src="../public/images/logo.jpg" width="80px" alt=""> <br><br>
            <b>SISTEMA DE VENTAS HILARI WEB</b> <br>
            Zona Alto Lima 1ra Sección Av. Litoral #2345 <br>
            23884774 - 75657007 <br>
            LA PAZ - BOLIVIA
        </td>
        <td style="width: 150px"></td>
        <td style="font-size: 16px;width: 290px"><br><br><br>
            <b>NIT: </b>10001099920 <br>
            <b>Nro factura:</b> '.$id_venta_get.' <br>
            <b>Nro de autorización: </b>100020029930
            <p style="text-align: center"><B>ORIGINAL</B></p>
        </td>
    </tr>
</table>

<p style="text-align: center;font-size: 25px"><b>FACTURA</b></p>

<div style="border: 1px solid #000000">
    <table border="0" cellpadding="6px">
        <tr>
            <td><b>Fecha:</b> '.$fecha.'</td>
            <td></td>
            <td><b>Nit/CI: </b>'.$nit_ci_cliente.'</td>
        </tr>
        <tr>
            <td colspan="3"><b>Señor(es): </b>'.$nombre_cliente.' </td>
        </tr>
    </table>
</div>

<br>

<table border="1" cellpadding="5" style="font-size: 12px">
    <tr style="text-align: center;background-color: #d6d6d6">
        <th style="width: 40px"><b>Nro</b></th>
        <th style="width: 150px"><b>Producto</b></th>
        <th style="width: 235px"><b>Descripción</b></th>
        <th style="width: 65px"><b>Cantidad</b></th>
        <th style="width: 98px"><b>Precio Unitario</b></th>
        <th style="width: 69px"><b>Sub total</b></th>
    </tr>
    '.$productos_html.'
    <tr>
        <td colspan="3" style="text-align: right;background-color: #d6d6d6"><b>Total</b></td>
        <td style="text-align: center;background-color: #d6d6d6">'.$cantidad_total.'</td>
        <td style="text-align: center;background-color: #d6d6d6">-</td>
        <td style="text-align: center;background-color: #d6d6d6">Bs. '.number_format($precio_total, 2).'</td>
    </tr>
</table>

<p style="text-align: right">
    <b>Monto Total: </b> Bs. '.number_format($precio_total, 2).'
</p>
<p>
    <b>Son: </b>'.$monto_literal.'
</p>
<br>
-------------------------------------------------------------------------------- <br>
<b>USUARIO:</b> '.$nombres_sesion.' ('.$email_sesion.') <br>

<p style="text-align: center">"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LA LEY"
</p>
<p style="text-align: center"><b>GRACIAS POR SU PREFERENCIA</b></p>';

// Generar PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Agregar código QR
$style = array(
    'border' => 0,
    'vpadding' => '3',
    'hpadding' => '3',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false,
    'module_width' => 1,
    'module_height' => 1
);

$QR = 'Factura #'.$id_venta_get.' - Cliente: '.$nombre_cliente.' ('.$nit_ci_cliente.') - Fecha: '.$fecha.' - Total: Bs. '.number_format($precio_total, 2);
$pdf->write2DBarcode($QR, 'QRCODE,L', 170, 240, 40, 40, $style);

// Salida del PDF
$pdf->Output('factura_'.$id_venta_get.'.pdf', 'I');