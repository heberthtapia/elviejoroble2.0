<?PHP	
include '../../adodb5/adodb.inc.php';
include '../../classes/function.php';

$db = NewADOConnection('mysqli');
	
$db->Connect();

$op = new cnFunction();

$fecha	= $op->ToDay();    
$hora	= $op->Time();
$mes	= $op->ToMes();
$anio	= $op->ToAno();

$id = $_REQUEST['id'];

$strSql = "SELECT * FROM pedido AS p, pedidoEmp AS pe ";
$strSql.= "WHERE p.id_pedido = pe.id_pedido ";
$strSql.= "AND p.id_pedido = '".$id."' ";

$str = $db->Execute($strSql);
$file = $str->FetchRow();

$strQuery = "SELECT * FROM cliente WHERE id_cliente = '".$file['id_cliente']."' ";
$sql = $db->Execute($strQuery);
$rcl = $sql->FetchRow();
?>
<link rel="stylesheet" type="text/css" href="css/pdf.css"/>
<style>
	table#datos tr td{
		font-size:12px;
		}
</style>
<script>
$(document).ready(function(e) {
     /* idealForm */
	  $('#form').idealForms();
});
</script>
<form id="form" class="ideal-form" action="javascript:saveForm('form','pedido/OkAlm.php')" >
<fieldset>
    <legend>E N T R E G A R&nbsp;&nbsp;&nbsp;P E D I D O</legend>
	<input type="hidden" id="pedido" name="pedido" value="<?=$id;?>"/>
	<table id="datos" style="border-collapse: collapse" align="center" style="width: 700px;"> 
    	<br>
        <br>   	
      	<tbody>
        	<tr>
            	<td style="width: 100px; text-align:left; font-weight:bold;">Se&ntilde;or(a):</td>
                <td style="width: 2400px; text-align:left; text-transform:capitalize"><?=$rcl['nombre'].' '.$rcl['apP'].' '.$rcl['apM'];?></td>
                
                <td style="width: 100px; text-align:left; font-weight:bold;">No:</td>
                <td style="width: 500px; text-align:left;">PD-<?=$op->ceros($file['id_pedido'],7);?></td>
            </tr>
            <tr>
            	<td style="width: 100px; text-align:left; font-weight:bold;">Direcci&oacute;n:</td>
                <td style="width: 500px; text-align:left; text-transform:capitalize"><?=$rcl['direccion'];?></td>
                
                <td style="width: 100px; text-align:left; font-weight:bold;">Fecha:</td>
                <td style="width: 500px; text-align:left;"><?=$file['dateReg'];?></td>
            </tr>
            <tr>
            	<td style="width: 100px; text-align:left; font-weight:bold;">Zona:</td>
                <td style="width: 500px; text-align:left; text-transform:capitalize"><?=$rcl['zona'];?></td>
                
                <td style="width: 70px; text-align:left; font-weight:bold;">Telefono:</td>
                <td style="width: 430px; text-align:left;"><?=$rcl['phone'];?></td>
            </tr>
            <tr>
            	<td style="width: 70px; text-align:left;"></td>
                <td style="width: 430px; text-align:left;"></td>
                
                <td style="width: 70px; text-align:left; font-weight:bold;">Celular:</td>
                <td style="width: 430px; text-align:left;"><?=$rcl['celular'];?></td>
            </tr>
   
  		</tbody>
    </table>
    <br>
    <br>
<h4>DETALLE PEDIDO</h4><br>
    <table class="report" style="width: 100%; border-collapse: collapse" align="center">
    	
      <thead>
          <tr>
              <th>N°</th>
              <th>CANTIDAD</th>
              <th>PRODUCTO</th>
              <th>PRECIO <br> UNITARIO</th>
              <th>DESCUENTO</th>
              <th>BONIFICACI&Oacute;N</th>
              <th>SUBTOTAL</th>              
          </tr>
      </thead>
      <tbody>
		<?PHP	  
        $sqll = "SELECT p.*, pe.*, i.detalle ";
        $sqll.= "FROM pedidoEmp AS pe, pedido AS p, inventario AS i ";
        $sqll.= "WHERE pe.id_pedido = ".$id." ";
		$sqll.= "AND pe.id_pedido = p.id_pedido ";
		$sqll.= "AND pe.id_inventario = i.id_inventario";
               
        $cont = 0;
		
		$strQuery = $db->Execute($sqll);
        
        while( $row = $strQuery->FetchRow() ){
            $cont = $cont + 1;
            //$cont = $operations->ceros($cont,2);
        ?>
		<tr <?php if($cont%2 == 0) echo("class='even'"); ?> >
            <td align="center"><?=$cont;?></td>
            <td align="center"><?=$row['cantidad'];?></td>
            <td align="center"><?=$row['detalle'];?></td>
            <td align="right"><?=$row['precio'];?></td>
            <td align="center"><?=$row['descuento'];?></td>
            <td align="center"><?=$row['bonificacion'];?></td>
            <td align="right"><?=number_format ( $row['precio']*$row['cantidad'] , 2 );;?></td>
        </tr>
        <?PHP
			$total = $row['total'];
		}
		?>
   
      </tbody>
      <tfoot>
        <tr class="footer">
          <th colspan="5" align="center">TIPO DE PAGO - <?PHP if($file['tipo'] == 'con') echo "CONTADO"; else echo "CREDITO";?></th>               
          <th align="right">TOTAL (Bs.)</th>          
          <th align="right"><?=$total;?></th> 
        </tr>
      </tfoot>
    </table>
</fieldset>	
  <div class="idealWrap" align="center">
  <?PHP
   if($file['status1'] == 'Pendiente' && $file['status2'] == 'No Entregado'){
      $msj = 'Entregar Pedido';
      $alt = 'No puede entregar el pedido por que no fue aprobado todav&iacute;a.';
   }elseif($file['status1'] == 'Aprobado' && $file['status2'] == 'No Entregado')
      $msj = 'Entregar Pedido';
   else
      $msj = 'Cancelar Pedido';
  ?>		
    <input type="reset" id="reset" value="Cerrar..." onclick="parent.$.colorbox.close();"/>
    <input type="submit" id="save" value="<?=$msj;?>"/>
  </div>
</form>
<br/><br/>
<h4 align="center"><?=$alt;?></h4>