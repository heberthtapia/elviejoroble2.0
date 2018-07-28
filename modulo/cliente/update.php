<?PHP
	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../inc/function.php';

	$db = NewADOConnection('mysqli');

	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora  = $op->Time();

	//$data  = stripslashes($_POST['res']);
	$data  = trim($_POST['res']);

	$data  = json_decode($data);

	//print_r($data);

	/**
	 * Actualiza el CLIENTE
	 */

	$strQuery = "UPDATE cliente SET nombre = '".trim($data->nameU)."', apP = '".trim($data->paternoU)."', apM = '".trim($data->maternoU)."', phone = '".trim($data->fonoU)."', celular = '".trim($data->celularU)."', ";
	$strQuery.= "calle = '".trim($data->calleU)."', nom_calle = '".trim($data->nom_calleU)."', numero = '".trim($data->NroU)."', ";
	$strQuery.= "zona = '".trim($data->zonaU)."', nom_zona = '".trim($data->nom_zonaU)."', departamento = '".trim($data->departamentoU)."', ";
	$strQuery.= "direccion_des = '".trim($data->direccionU)."', nombreEmp = '".trim($data->nameEmpU)."', nit = '".trim($data->nitU)."', email  = '".trim($data->emailU)."', coorX = '".trim($data->cxU)."', coorY = '".trim($data->cyU)."', ";
	$strQuery.= "obser  = '".trim($data->obserU)."', dateReg = '".$fecha.' '.$hora."' ";
	$strQuery.= "WHERE id_cliente = '".trim($data->codCliU)."' ";

	$sql = $db->Execute($strQuery);

	/**
	 * Actualiza el id del CLIENTE
	 */

	$strQuery = "UPDATE cliente SET id_cliente = '".$data->codClU."' ";
	$strQuery.= "WHERE id_cliente = '".$data->codCliU."' ";

	$sql = $db->Execute($strQuery);

	/*********************ACTUALIZA FOTO Y ENVIANDO DATOS POR EMAIL*******************************/

	$data->img = '';

	$strQuery = "SELECT * FROM auxImg ";

	$srtQ = $db->Execute($strQuery);

	$row = $srtQ->FetchRow();

	if ($row[0]!=''){
		$name = $row['name'];
		$size = $row['size'];

		$strQuery = "UPDATE cliente set foto = '".$name."', size = '".$size."' ";
		$strQuery.= "WHERE id_cliente = '".$data->codClU."' ";

		$strQ = $db->Execute($strQuery);
		$data->img = $img;
	}

	/***************************************************************************/

	$sql = "TRUNCATE TABLE auxImg";
	$strQ = $db->Execute($sql);

	if($sql)
		echo json_encode($data);
	else
		echo 0;
?>
