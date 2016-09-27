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
	$strQuery = "UPDATE inventario SET dateReg = '".$fecha." ".$hora."', ";
	$strQuery.= "detalle = '".$data->detalle."', cantidad = '".$data->cant."', volumen = '".$data->vol."', ";
	$strQuery.= "precioCF = '".$data->precioCF."', precioSF = '".$data->precioSF."', status = 'Activo' ";
	$strQuery.= "WHERE id_inventario = '".$data->idInv."' ";

	$sql = $db->Execute($strQuery);

	/* -------------------------------------------------------- */

	//print_r($data);
	if($sql)
		echo json_encode($data);
	else
		echo 0;

?>