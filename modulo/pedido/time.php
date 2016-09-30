<?PHP
	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';

	$db = NewADOConnection('mysqli');

	$db->Connect();

	$op = new cnFunction();

	date_default_timezone_set("America/La_Paz" );
	$fecha = $op->ToDay();
	$hora = $op->Time();

	$sqlTime = "SELECT id_pedido, dateReg FROM pedido ";
	$strTime = $db->Execute($sqlTime);

	$r = $strTime->FetchRow();
	$r = $strTime->FetchRow();

	$from = new DateTime($fecha." ".$hora);

	$to = new DateTime($r[1]);

	$fecha = $to->diff($from);

	echo json_encode($fecha);

	//printf(' ------------ %d años, %d meses, %d días, %d horas, %d minutos', $fecha->y, $fecha->m, $fecha->d, $fecha->h, $fecha->i);

?>