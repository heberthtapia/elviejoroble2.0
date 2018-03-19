<?PHP
	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../inc/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op    = new cnFunction();

	$fecha = $op->ToDay();
	$hora  = $op->Time();

	$data  = stripslashes($_POST['res']);

	$data  = json_decode($data);

	$strQuery = "INSERT INTO cliente (id_cliente, ci, depa, id_empleado, nombre, apP, apM, phone, celular, ";
	$strQuery.= "nombreEmp, iva, email, direccion, numero, coorX, coorY, obser, dateReg, status ) ";
	$strQuery.= "VALUES ('".$data->codCl."', ".$data->ci.", '".$data->dep."', '".$_SESSION['idEmp']."', '".$data->name."', '".$data->paterno."', ";
	$strQuery.= "'".$data->materno."', '".$data->fono."', '".$data->celular."', '".$data->nameEmp."', '".$data->iva."', ";
	$strQuery.= "'".$data->email."', '".$data->addres."', '".$data->Nro."', '".$data->cx."', '".$data->cy."', '".$data->obser."', ";
	$strQuery.= "'".$data->date."', 'Activo' )";

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
		$strQuery.= "WHERE id_cliente = '".$data->codCl."' ";

		$strQ = $db->Execute($strQuery);
		$data->img = $img;
	}

	/***************************************************************************/

	$sql = "TRUNCATE TABLE auxImg ";
	$strQ = $db->Execute($sql);

	if($sql)
		echo json_encode($data);
	else
		echo 0;
?>
