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

	$strQuery	= "SELECT statusProd FROM produccion WHERE id_produccion = '".$data."'";
	$strSql		= $db->Execute($strQuery);
	$status		= $strSql->FetchRow();

	if($status[0] == "En Produccion"){

		$strQuery = "UPDATE produccion SET statusProd = 3, dateFin = '".$fecha." ".$hora."' ";
		$strQuery.= "WHERE id_produccion = '".$data."' ";

		$sql = $db->Execute($strQuery);

		echo 1;
	}else
		echo 0;
?>