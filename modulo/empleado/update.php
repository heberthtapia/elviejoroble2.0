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

	/* ACTUALIZACION DE EMPLEADO */
	$strQuery = "UPDATE empleado SET nombre = '".$data->nameU."', apP = '".$data->paternoU."', apM = '".$data->maternoU."', dateNac = '".$data->dateNacU."', phone = '".$data->fonoU."', celular = '".$data->celularU."', depa = '".$data->depU."', ";
	$strQuery.= "direccion = '".$data->addresU."', numero = '".$data->NroU."', obser = '".$data->obserU."', ";
	$strQuery.= "email  = '".$data->emailU."', coorX  = '".$data->cxU."', coorY  = '".$data->cyU."', cargo = '".$data->cargoU."' ";
	$strQuery.= "WHERE id_empleado = '".$data->ciU."' ";

	$sql = $db->Execute($strQuery);

	$strQuery = "UPDATE usuario SET user ='".$data->codUserU."', pass = '".$data->passwordU."', statusEmp = 'Inactivo' ";
	$strQuery.= "WHERE id_empleado = '".$data->ciU."' ";

	$sql = $db->Execute($strQuery);

	/*********************ACTUALIZA FOTO Y ENVIANDO DATOS POR EMAIL*******************************/

	$data->img = '';

	$strQuery = "SELECT * FROM aux_img ";

	$srtQ = $db->Execute($strQuery);

	$row = $srtQ->FetchRow();

	if ($row[0]!=''){
		$img = $row['imagen'];

		$strQuery = "UPDATE empleado set foto = '".$img."' ";
		$strQuery.= "WHERE id_empleado = ".$data->ciU." ";

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