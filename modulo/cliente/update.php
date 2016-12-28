<?PHP
	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../inc/function.php';

	$db = NewADOConnection('mysqli');

	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora  = $op->Time();

	$data  = stripslashes($_POST['res']);

	$data  = json_decode($data);

	//print_r($data);

	/**
	 * Actualiza el CLIENTE
	 */

	$strQuery = "UPDATE cliente SET nombre = '".$data->nameU."', apP = '".$data->paternoU."', apM = '".$data->maternoU."', depa = '".$data->depU."', phone = '".$data->fonoU."', celular = '".$data->celularU."', ";
	$strQuery.= "nombreEmp = '".$data->nameEmpU."', email  = '".$data->emailU."', direccion = '".$data->addresU."', numero = '".$data->NroU."', coorX = '".$data->cxU."', coorY = '".$data->cyU."', ";
	$strQuery.= "obser  = '".$data->obserU."', dateReg = '".$fecha.' '.$hora."' ";
	$strQuery.= "WHERE id_cliente = '".$data->codCliU."' ";

	$sql = $db->Execute($strQuery);

	/**
	 * Actualiza el id del CLIENTE
	 */

	$strQuery = "UPDATE cliente SET id_cliente = '".$data->codClU."' ";
	$strQuery.= "WHERE id_cliente = '".$data->codCliU."' ";

	$sql = $db->Execute($strQuery);

	/*********************ACTUALIZA FOTO Y ENVIANDO DATOS POR EMAIL*******************************/

	$data->img = '';

	$strQuery = "SELECT * FROM aux_img ";

	$srtQ = $db->Execute($strQuery);

	$row = $srtQ->FetchRow();

	if ($row[0]!=''){
		$img = $row['imagen'];

		$strQuery = "UPDATE cliente set foto = '".$img."' ";
		$strQuery.= "WHERE id_cliente = '".$data->codClU."' ";

		$strQ = $db->Execute($strQuery);
		$data->img = $img;
	}
	if($data->checksEmailU == 'on'){
		//echo 'entra......';
		//include '../../classes/envioData.php';
	}
	//print_r($data);
	/***************************************************************************/

	$sql = "TRUNCATE TABLE aux_img ";
	$strQ = $db->Execute($sql);

	if($sql)
		echo json_encode($data);
	else
		echo 0;
?>