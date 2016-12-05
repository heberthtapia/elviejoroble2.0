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

	$srtSql = "SELECT * FROM empleado WHERE cargo = 'pre' ";
	$srtSqlId = $db->Execute($srtSql);

	while ($srtId = $srtSqlId->FetchRow()) {
		$strQuery = "INSERT INTO inventarioPre ( id_inventario, id_produccion, id_empleado, cantidad, dateReg, status ) ";
		$strQuery .= "VALUES ('".$data->idInv."', '".$data->idP."', '".$srtId['id_empleado']."', '" . $data->$srtId['id_empleado'] . "', ";
		$strQuery .= "'" . $data->date . "', 'Activo' )";
		$sql = $db->Execute($strQuery);
	}
	$srtQuery = "SELECT cantidad FROM inventario WHERE id_inventario = '".$data->idInv."' ";
	$srtNum   = $db->Execute($srtQuery);
	$num = $srtNum->FetchRow();

	$srtQuery = "UPDATE inventario SET cantidad = '".($num[0] + ($data->cantP))."' ";
	$srtQuery.= "WHERE id_inventario = '".$data->idInv."' ";

	$srtQ = $db->Execute($srtQuery);

	$strQuery = "UPDATE produccion SET statusProd = 4, cantidad = $data->cantP ";
	$strQuery.= "WHERE id_produccion = '".$data->idP."' ";

	$sql = $db->Execute($strQuery);

	if($sql)
		echo json_encode($data);
	else
		echo 0;
?>