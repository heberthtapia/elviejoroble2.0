<?php
session_start();
include '../../adodb5/adodb.inc.php';
include '../../classes/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$fecha = $op->ToDay();
$hora = $op->Time();

$id = $_REQUEST['res'];

$data = json_decode($data);

$strQuery = "SELECT * FROM cliente WHERE id_cliente = '".$data->idCliente."' ";
$sql = $db->Execute($strQuery);
$rcl = $sql->FetchRow();



$sql = 'SELECT * ';
$sql.= 'FROM empleado ';
$sql.= 'WHERE id_empleado = '.$_SESSION['idEmp'].'';

$reg = $db->Execute($sql);

$rt = $reg->FetchRow();

$inc = strtoupper($rt['nombre']);
$incp = strtoupper($rt['apP']);

$html = '

<pageheader name="myHeaderNoNum" content-left="Industrias &ldquo;El Viejo Roble&rdquo; s.r.l." content-center="OR-P-'.$id.'" content-right="{DATE j-m-Y}" header-style="font-family:sans-serif; font-size:8pt; color:#000000;" header-style-right="font-size:8pt; font-weight:bold; font-style:italic; color:#000000;" line="on" />

<pagefooter name="myFooter1" content-left="Industrias &ldquo;El Viejo Roble&rdquo; s.r.l." content-center="" content-right="{PAGENO}" footer-style="font-family:sans-serif; font-size:8pt; font-weight:bold; color:#000000;" footer-style-left="" line="on" />

<setpageheader name="myHeaderNoNum" page="O" value="on" show-this-page="1" />
<setpagefooter name="myFooter1" page="O" value="on" show-this-page="1" />

<h2 style="margin-collapse: none; margin-top: 5mm; text-align: center;">Orden de Pedido</h2>
<br><br>
<p><strong>Cliente: Sr(a).</strong> '.ucfirst($rcl['nombre']).'&nbsp;'.ucfirst($rcl['apP']).'&nbsp;'.ucfirst($rcl['apM']).'</p>
<p><strong>Dirección: </strong> '.ucfirst($rcl['direccion']).'</p>
<p><strong>N°: </strong> '.ucfirst($rcl['numero']).'</p>
<p><strong>Telefono:</strong></p>
<br><br>

<tab</strong>le class="bpmTopicC" align="center">
<thead>
    <tr class="headerrow">
        <th>Codigo Orden Producci&oacute;n</th>
        <th>Producto</th>
        <th>Detalle</th>
        <th>Fecha Inicio</th>
        <th>Fecha Terminada</th>
        <th>Cantidad</th>
    </tr>
</thead>
<tbody>';
    while( $row = $str->FetchRow()){
    $html.= '<tr>';
        $html.= '<td>OR-P-'.$row[0].'</td>';
        $html.= '<td>'.$row[1].'</td>';
        $html.= '<td>'.$row[2].'</td>';
        $html.= '<td>'.$row[3].'</td>';
        $html.= '<td>'.$row[4].'</td>';
        $html.= '<td>'.$row[5].'</td>';
    $html.= '</tr>';
    }
$html.='</tbody>

</table>

<br><br>
<div>Detalle de Asignacion a Vendedores:</div>
<br><br>

<table class="bpmTopicC" align="center">
<thead>
    <tr class="headerrow">
        <th>Codigo Empleado</th>
        <th>Vendedor</th>
        <th>Codigo de Producto</th>
        <th>Detalle Producto</th>
        <th>Cantidad Asignada</th>
    </tr>
</thead>
<tbody>';
    $c = 0;
    while( $file = $strP->FetchRow()){
    $html.= '<tr>';
        $html.= '<td>'.$file[0].'</td>';
        $html.= '<td>'.$file[1].' '.$file[2].'</td>';
        $html.= '<td>'.$file[3].'</td>';
        $html.= '<td>'.$file[4].'</td>';
        $html.= '<td>'.$file[5].'</td>';
        $c = $c + $file[5];
    $html.= '</tr>';
    }
$html.='</tbody>
<tfoot>';
$html.= '<tr>';
$html.= '<td colspan="4" align="right">Total </td>';
$html.= '<td>'.$c.'</td>';
$html.= '</tr>';
$html.= '</tfoot>

</table>

<div class="firma">'.ucfirst($rt['nombre']).'&nbsp;'.ucfirst($rt['apP']).'<br>Almacen</div>

';

//==============================================================
//==============================================================
//==============================================================
include("../../mpdf60/mpdf.php");
$mpdf=new mPDF('c','LETTER');

$mpdf->mirrorMargins = true;

$mpdf->SetDisplayMode('fullpage','two');

// LOAD a stylesheet
$stylesheet = file_get_contents('../../css/styleReporte.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html);

$mpdf->Output();
exit;
//==============================================================
//==============================================================
//==============================================================
//==============================================================
//==============================================================


?>