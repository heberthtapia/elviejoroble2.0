<?PHP

	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../inc/function.php';

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

	echo $strQuery = "SELECT * FROM cliente WHERE id_cliente = '".$file['id_cliente']."' ";
	$sql = $db->Execute($strQuery);
	$rcl = $sql->FetchRow();

  $queryEmp = "SELECT e.nombre, e.apP, e.apM FROM empleado AS e, cliente AS c WHERE e.id_empleado = c.id_empleado ";
  $sqlEmp = $db->Execute($queryEmp);
  $rowSql = $sqlEmp->FetchRow();
?>

<page orientation="paysage" backtop="10mm" backbottom="10mm" backleft="5mm" backright="5mm">
  <page_header>
    <table id="header" style="width: 50%;">
        <tr>
            <td style="width: 47%">EL VIEJO ROBLE</td>
            <td style=" text-align:right; width: 50%"><?php echo date('d/m/Y'); ?></td>
            <td style="width: 6%"></td>
            <td style="width: 47%">EL VIEJO ROBLE</td>
            <td style=" text-align:right; width: 50%"><?php echo date('d/m/Y'); ?></td>
        </tr>
    </table>
    <hr />
  </page_header>
  <page_footer>
    <hr />
    <table id="footer" style="width: 50%;">
        <tr>
            <td style="width: 47%"><?=$row['direccion']?></td>
            <td style=" text-align:right; width: 50%">pagina [[page_cu]]/[[page_nb]]</td>
            <td style="width: 6%;"></td>
            <td style="width: 47%"><?=$row['direccion']?></td>
            <td style=" text-align:right; width: 50%">pagina [[page_cu]]/[[page_nb]]</td>
        </tr>
    </table>
  <link rel="stylesheet" type="text/css" href="../../css/pdf.css"/>
  </page_footer>

<table id="pagina">
<tr>
<td style="border-right: 1px solid dashed; width: 490px;">

  <h4 align="center" style="font-size:26px;">PEDIDO</h4>
	<table id="datos" style="border-collapse: collapse;">
      	<tbody>
        	<tr>
            	<td style="text-align:left;"><strong>Cliente Sr(a).:</strong></td>
              <td style="width:240px; text-align:left; text-transform:capitalize"><?=$rcl['nombre'].' '.$rcl['apP'].' '.$rcl['apM'];?></td>

              <td style="text-align:left;"><strong>No de Pedido:</strong></td>
              <td style="text-align:left;">PD-<?=$op->ceros($file['id_pedido'],7);?></td>
          </tr>
            <tr>
            	<td style=" text-align:left;"><strong>Direcci&oacute;n:</strong></td>
                <td style="text-align:left; text-transform:capitalize"><?=$rcl['direccion'];?></td>

                <td style=" text-align:left;"><strong>Fecha:</strong></td>
                <td style="text-align:left;"><?=$file['dateReg'];?></td>
            </tr>
            <tr>
            	<td style=" text-align:left;"><strong>N°:</strong></td>
                <td style="text-align:left; text-transform:capitalize"><?=$rcl['numero'];?></td>

                <td style=" text-align:left;"><strong>Telefono:</strong></td>
                <td style="text-align:left;"><?=$rcl['phone'];?></td>
            </tr>
            <tr>
            	<td style="w text-align:left;"></td>
                <td style="text-align:left;"></td>

                <td style=" text-align:left;"><strong>Celular:</strong></td>
                <td style="text-align:left;"><?=$rcl['celular'];?></td>
            </tr>

  		</tbody>
    </table>
    <br>
    <h5>DETALLE PEDIDO</h5>
    <table class="report" style=" border-collapse: collapse" align="center">
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

    <h5>OBSERVACIONES</h5>
    <span>
		<?PHP
			if( $file['obser']!='' )
				echo $file['obser'];
			else
				echo 'Ninguna';
		?>
    </span>

    <table id="firma" align="center" style="font-weight: bold;">
    	<tbody>
        <tr>
         	<td align="center" style="width: 200px; text-align: center; text-transform: capitalize; border-top: 1px solid dashed;"><?=$rcl['nombre'].' '.$rcl['apP'].' '.$rcl['apM'];?></td>
          <td style="width: 60px"></td>
         	<td align="center" style="width: 200px; text-align: center; text-transform: capitalize; border-top: 1px solid dashed;"><?=$rowSql['nombre'].' '.$rowSql['apP'].' '.$rowSql['apM'];?></td>
        </tr>
        <tr>
         	<td align="center">CLIENTE</td>
          <td></td>
         	<td align="center">VENDEDOR</td>
        </tr>
      </tbody>
    </table>
</td>

<td style="padding: 0 10px; width: 490px;">

  <h4 align="center" style="font-size:26px;">ENTREGA DE PRODUCTO</h4>
  <table id="datos" style="border-collapse: collapse;">
        <tbody>
          <tr>
              <td style="text-align:left;"><strong>Cliente Sr(a).:</strong></td>
              <td style="width:270px; text-align:left; text-transform:capitalize"><?=$rcl['nombre'].' '.$rcl['apP'].' '.$rcl['apM'];?></td>

              <td style="text-align:left;"><strong>No:</strong></td>
              <td style="text-align:left;">PD-<?=$op->ceros($file['id_pedido'],7);?></td>
          </tr>
            <tr>
              <td style=" text-align:left;"><strong>Direcci&oacute;n:</strong></td>
                <td style="text-align:left; text-transform:capitalize"><?=$rcl['direccion'];?></td>

                <td style=" text-align:left;"><strong>Fecha:</strong></td>
                <td style="text-align:left;"><?=$file['dateReg'];?></td>
            </tr>
            <tr>
              <td style=" text-align:left;"><strong>N°:</strong></td>
                <td style="text-align:left; text-transform:capitalize"><?=$rcl['numero'];?></td>

                <td style=" text-align:left;"><strong>Telefono:</strong></td>
                <td style="text-align:left;"><?=$rcl['phone'];?></td>
            </tr>
            <tr>
              <td style="w text-align:left;"></td>
                <td style="text-align:left;"></td>

                <td style=" text-align:left;"><strong>Celular:</strong></td>
                <td style="text-align:left;"><?=$rcl['celular'];?></td>
            </tr>

      </tbody>
    </table>
    <br>
    <h5>DETALLE PEDIDO</h5>
    <table class="report" style=" border-collapse: collapse" align="center">
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

    <h5>OBSERVACIONES</h5>
    <span>
    <?PHP
      if( $file['obser']!='' )
        echo $file['obser'];
      else
        echo 'Ninguna';
    ?>
    </span>

    <table id="firma" align="center" style="font-weight: bold;">
      <tbody>
        <tr>
          <td align="center" style="width: 200px; text-align: center; text-transform: capitalize; border-top: 1px solid dashed;"><?=$rcl['nombre'].' '.$rcl['apP'].' '.$rcl['apM'];?></td>
          <td style="width: 60px"></td>
          <td align="center" style="width: 200px; text-align: center; text-transform: capitalize; border-top: 1px solid dashed;"><?=$rowSql['nombre'].' '.$rowSql['apP'].' '.$rowSql['apM'];?></td>
        </tr>
        <tr>
          <td align="center">CLIENTE</td>
          <td></td>
          <td align="center">VENDEDOR</td>
        </tr>
      </tbody>
    </table>
</td>

</tr>
</table>
</page>

