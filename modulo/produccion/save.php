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

	$strQuery = "INSERT INTO produccion (id_inventario, detalle, cantidad, dateInc, status ) ";
	$strQuery.= "VALUES ('".$data->idInvN."', '".$data->detalleN."', '".$data->cantN."', ";
	$strQuery.= "'".$data->dateN."', 'Activo' )";

	$sql = $db->Execute($strQuery);

	$strQuery = "SELECT max(id_produccion) FROM produccion";
	$query = $db->Execute($strQuery);
	$idProduccion = $query->FetchRow();

	$data->id_produccion = $idProduccion[0];

	if($sql)
		echo json_encode($data);
	else
		echo 0;

?>