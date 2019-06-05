<?PHP
	session_start();

	include '../adodb5/adodb.inc.php';
	include '../inc/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora = $op->Time();

	$data = stripslashes($_POST['res']);
	$data = json_decode($data);

	//print_r($data);

	$strQuery = "SELECT * FROM inventario WHERE id_inventario = '".$data->producto."' ";

	$sql = $db->Execute($strQuery);
	$row = $sql->FetchRow();

	$data->detalle	= $row['detalle'];
	$data->volumen	= $row['volumen'];
	$data->cantI	= $row['cantidad'];
	$data->precio	= $row['precioCF'];

	if($row)
		echo json_encode($data);
	else
		echo 0;
?>
