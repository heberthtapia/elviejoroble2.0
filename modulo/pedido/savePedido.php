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
	$strQuery = "INSERT INTO pedido (id_empleado, id_cliente, dateReg, subTotal, descuento, bonificacion, total, tipo, obser,  status1)";
	$strQuery.= "VALUES ('".$_SESSION['idEmp']."', '".$data->idCliente."', '".$fecha." ".$hora."', '".$data->subTotal."', ";
	$strQuery.= "'".$data->descuento."', '".$data->bonificacion."', ";
	$strQuery.= "'".$data->total."', '".$data->tipo."', '".$data->obs."', 'Pendiente')";

	$sql = $db->Execute($strQuery);

	$strQuery = "SELECT max(id_pedido) FROM pedido";
	$query = $db->Execute($strQuery);
	$idPedido = $query->FetchRow();

	$data->pedido = $idPedido[0];

	$i = 0;

	if( count($data->cantidad) > 1 ){

	  foreach( $data->cantidad as $k => $valor ){

		$strQuery = "INSERT INTO pedidoEmp (id_inventario, id_empleado, id_pedido, cantidad, precio ) ";
		$strQuery.= "VALUES ('".$data->item[$i]."', '".$_SESSION['idEmp']."', '".$idPedido[0]."', '".$data->cantidad[$i]."', '".$data->precio[$i]."' )";
		$sql = $db->Execute($strQuery);

		$sqlCant = "SELECT cantidad FROM inventario WHERE id_inventario = '".$data->item[$i]."' ";

		$sqlReg = $db->Execute($sqlCant);
		$regCant = $sqlReg->FetchRow();

		$cantidad = $regCant[0] - $data->cantidad[$i];

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

		$cantidad = $regCant[0] - $data->cantidad;

		$strInv = "UPDATE inventario SET cantidad = '".$cantidad."' WHERE id_inventario = '".$data->item."' ";

		$sqlInv = $db->Execute($strInv);

		$i++;
	}
	/* -------------------------------------------------------- */

	//print_r($data);
	if($sql)
		echo json_encode($data);
	else
		echo 0;

?>