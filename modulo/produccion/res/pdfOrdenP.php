<?PHP

	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';

	$db = NewADOConnection('mysqli');

	$db->Connect();

	$op = new cnFunction();

	$fecha	= $op->ToDay();
	$hora	= $op->Time();
	$mes	= $op->ToMes();
	$anio	= $op->ToAno();

	$id = $_REQUEST['res'];

	$strSql = "SELECT * FROM pedido AS p, pedidoEmp AS pe ";
	$strSql.= "WHERE p.id_pedido = pe.id_pedido ";
	$strSql.= "AND p.id_pedido = '".$id."' ";

	$str = $db->Execute($strSql);
	$file = $str->FetchRow();

	$strQuery = "SELECT * FROM cliente WHERE id_cliente = '".$file['id_cliente']."' ";
	$sql = $db->Execute($strQuery);
	$rcl = $sql->FetchRow();
?>

<page backtop="20mm" backbottom="10mm" backleft="15mm" backright="15mm">
  <page_header>
    <table id="header" style="width: 100%;">
        <tr>
            <td style="width: 50%">EL VIEJO ROBLE</td>
            <td style=" text-align:right; width: 50%"><?php echo date('d/m/Y'); ?></td>
        </tr>
    </table>
    <hr />
  </page_header>
  <page_footer>
    <hr />
    <table id="footer" style="width: 100%;">
        <tr>
            <td style="width: 80%"><?=$row['direccion']?></td>
            <td style=" text-align:right; width: 20%">pagina [[page_cu]]/[[page_nb]]</td>
        </tr>
    </table>
  <link rel="stylesheet" type="text/css" href="../../css/pdf.css"/>
  </page_footer>

   <h4 align="center" style="font-size:26px;">PEDIDO</h4>
	<table id="datos" style="border-collapse: collapse; width: 700px;" align="center">
      	<tbody>
        	<tr>
            	<td style="width: 70px; text-align:left;">Se&ntilde;or(a):</td>
                <td style="width: 400px; text-align:left; text-transform:capitalize"><?=$rcl['nombre'].' '.$rcl['apP'].' '.$rcl['apM'];?></td>

                <td style="width: 70px; text-align:left;">No:</td>
                <td style="width: 300px; text-align:left;">PD-<?=$op->ceros($file['id_pedido'],7);?></td>
            </tr>
            <tr>
            	<td style="width: 70px; text-align:left;">Direcci&oacute;n:</td>
                <td style="text-align:left; text-transform:capitalize"><?=$rcl['direccion'];?></td>

                <td style="width: 70px; text-align:left;">Fecha:</td>
                <td style="text-align:left;"><?=$file['dateReg'];?></td>
            </tr>
            <tr>
            	<td style="width: 70px; text-align:left;">Zona:</td>
                <td style="text-align:left; text-transform:capitalize"><?=$rcl['zona'];?></td>

                <td style="width: 70px; text-align:left;">Telefono:</td>
                <td style="text-align:left;"><?=$rcl['phone'];?></td>
            </tr>
            <tr>
            	<td style="width: 70px; text-align:left;"></td>
                <td style="text-align:left;"></td>

                <td style="width: 70px; text-align:left;">Celular:</td>
                <td style="text-align:left;"><?=$rcl['celular'];?></td>
            </tr>

  		</tbody>
    </table>
    <br>
    <br>
<h4>DETALLE PEDIDO</h4>
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
            <td align="right"><?=number_format ( $row['precio']*$row['cantidad'] , 2 );?></td>
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
    <h4>OBSERVACIONES</h4>
    <h5>
		<?PHP
			if( $file['obser']!='' )
				echo $file['obser'];
			else
				echo 'Ninguna';
		?>
    </h5>

    <!--<table id="firma" style="width: 100%" align="center">
    	<tbody>
        	<tr>
            	<td align="center">Firma 1</td>
            	<td align="center">Firma 2</td>
            </tr>
            <tr>
            	<td align="center">ddddddd</td>
            	<td align="center">sssss</td>
            </tr>
            <tr>
            	<td align="center">ssssss</td>
            	<td align="center">aaaaa</td>
            </tr>
        </tbody>
    </table>-->

</page>

