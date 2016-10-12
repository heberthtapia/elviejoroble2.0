<?PHP
	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../inc/function.php';

	$db = NewADOConnection('mysqli');

	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora = $op->Time();

	$data = stripslashes($_POST['res']);

	$data = json_decode($data);

	//print_r($data);

	/* REGISTRA VENTA */
	$strQuery = "UPDATE pedido SET id_cliente = '".$data->idCliente."', dateReg = '".$fecha." ".$hora."', ";
	$strQuery.= "subtotal = '".$data->subTotal."', descuento = '".$data->descuento."', bonificacion = '".$data->bonificacion."', ";
	$strQuery.= "total = '".$data->total."', tipo = '".$data->tipo."', obser = '".$data->obs."', status1 = 'Pendiente' ";
	$strQuery.= "WHERE id_pedido = '".$data->pedido."' ";

	$sql = $db->Execute($strQuery);

	$strQuery = "DELETE FROM pedidoEmp WHERE id_pedido = '".$data->pedido."'";
	$sql = $db->Execute($strQuery);

	$strQuery = "SELECT max(id_pedido) FROM pedido";
	$query = $db->Execute($strQuery);
	$idPedido = $query->FetchRow();

	$i = 0;

	if( count($data->cantidad) > 1 ){

	  foreach( $data->cantidad as $k => $valor ){

		$strQuery = "INSERT INTO pedidoEmp (id_inventario, id_empleado, id_pedido, cantidad, precio ) ";
		$strQuery.= "VALUES ('".$data->item[$i]."', '".$_SESSION['idEmp']."', '".$idPedido[0]."', '".$data->cantidad[$i]."', '".$data->precio[$i]."' )";
		$sql = $db->Execute($strQuery);

		$sqlCant = "SELECT cantidad FROM inventario WHERE id_inventario = '".$data->item[$i]."' ";

		$sqlReg = $db->Execute($sqlCant);
		$regCant = $sqlReg->FetchRow();

		$cantidad = $regCant[0] - $data->cantidad[$i] + $data->cantInv[$i];

		$strInv = "UPDATE inventario SET cantidad = '".$cantidad."' WHERE id_inventario = '".$data->item[$i]."' ";

		$sqlInv = $db->Execute($strInv);

		$i++;

	  }

	}else{

		$strQuery = "INSERT INTO pedidoEmp (id_inventario, id_empleado, id_pedido, cantidad, precio ) ";
		$strQuery.= "VALUES ('".$data->item."', '".$_SESSION['idEmp']."', '".$idPedido[0]."', '".$data->cantidad."', '".$data->precio."' )";
		$sql = $db->Execute($strQuery);

		$sqlCant = "SELECT cantidad FROM inventario WHERE id_inventario = '".$data->item."' ";

		$sqlReg = $db->Execute($sqlCant);
		$regCant = $sqlReg->FetchRow();

		$cantidad = $regCant[0] - $data->cantidad + $data->cantInv;

		$strInv = "UPDATE inventario SET cantidad = '".$cantidad."' WHERE id_inventario = '".$data->item."' ";

		$sqlInv = $db->Execute($strInv);

		$i++;
	}

	/**
	 * LEE EL XML Y ACTUALIZA LA BD
	 */

	$xml = new SimpleXMLElement('eliminados.xml', 0, true);

    foreach($xml as $item){

    	$strCant = "SELECT cantidad FROM inventario WHERE id_inventario = '".$item->id."'";
      	$strCant = $db->Execute($strCant);
      	$cant = $strCant->FetchRow();

      	$cantidad = $cant[0]+($item->cantidad);

      	$strInv = "UPDATE inventario SET cantidad = '".$cantidad."' WHERE id_inventario = '".$item->id."' ";

		$sqlInv = $db->Execute($strInv);
    }

	if($sql)
		echo json_encode($data);
	else
		echo 0;

?>