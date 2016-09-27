<?PHP
	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../inc/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora = $op->Time();

	$data = stripslashes($_POST['res']);

	$data = json_decode($data);

	//$cargo = $op->toCargo($data->cargo);

	$strQuery = "INSERT INTO inventario (id_inventario, detalle,  volumen, cantidad, precioCF, precioSF, dateReg, status ) ";
	$strQuery.= "VALUES ('".$data->idInv."', '".$data->detalle."', '".$data->vol."', '".$data->cant."', ";
	$strQuery.= "'".$data->precioCF."', '".$data->precioSF."', '".$data->date."', 'Activo' )";

	$sql = $db->Execute($strQuery);

	if($sql)
		echo json_encode($data);
	else
		echo 0;

?>