<?PHP
	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';

	$db = NewADOConnection('mysqli');

	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora  = $op->Time();

	$data  = stripslashes($_POST['res']);

	$data  = json_decode($data);

	//print_r($data);

	/* ACTUALIZACION DE EMPLEADO */

	$strQuery = "UPDATE cliente SET nombre = '".$data->name."', apP = '".$data->paterno."', apM = '".$data->materno."', phone = '".$data->fono."', celular = '".$data->celular."', ";
	$strQuery.= "nombreEmp = '".$data->nameEmp."', email  = '".$data->emailC."', direccion = '".$data->addresC."', coorX = '".$data->cx."', coorY = '".$data->cy."', ";
	$strQuery.= "obser  = '".$data->obser."', dateReg = '".$data->fecha."' ";
	$strQuery.= "WHERE id_cliente = '".$data->ci."' ";

	$sql = $db->Execute($strQuery);

	/*********************ACTUALIZA FOTO Y ENVIANDO DATOS POR EMAIL*******************************/

	$data->img = '';

	$strQuery = "SELECT * FROM aux_img ";

	$srtQ = $db->Execute($strQuery);

	$row = $srtQ->FetchRow();

	if ($row[0]!=''){
		$img = $row['imagen'];

		$strQuery = "UPDATE cliente set foto = '".$img."' ";
		$strQuery.= "WHERE id_cliente = ".$data->ci." ";

		$strQ = $db->Execute($strQuery);
		$data->img = $img;
	}
	if($data->checksEmail == 'on'){
		//echo 'entra......';
		//include '../../classes/envioData.php';
	}
	//print_r($data);
	/***************************************************************************/

	if($sql)
		echo json_encode($data);
	else
		echo 0;
?>