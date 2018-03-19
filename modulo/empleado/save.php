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

	$strQuery = "INSERT INTO empleado (id_empleado, depa,  nombre, apP, apM, dateNac, phone, celular, ";
	$strQuery.= "direccion, numero, coorX, coorY, obser, email, cargo, dateReg, statusEmp ) ";
	$strQuery.= "VALUES (".$data->ci.", '".$data->dep."', '".$data->name."', '".$data->paterno."', ";
	$strQuery.= "'".$data->materno."', '".$data->dateNac."', '".$data->fono."', '".$data->celular."',";
	$strQuery.= "'".$data->addres."', '".$data->Nro."', '".$data->cx."', '".$data->cy."', '".$data->obser."', '".$data->email."', ";
	$strQuery.= "'".$data->cargo."', '".$data->date."', 'Activo' )";

	$sql = $db->Execute($strQuery);

	$strQuery = "INSERT INTO usuario (id_empleado, user, pass, status ) ";
	$strQuery.= "VALUES ('".$data->ci."', '".$data->codUser."', '".$data->password."', 'Inactivo' )";

	$sql = $db->Execute($strQuery);

	/*********************ACTUALIZA FOTO Y ENVIANDO DATOS POR EMAIL*******************************/

	$data->img = '';

	$strQuery = "SELECT * FROM auxImgEmp ";

	$srtQ = $db->Execute($strQuery);

	$row = $srtQ->FetchRow();

	if ($row[0]!=''){
		$name = $row['name'];
		$size = $row['size'];

		$strQuery = "UPDATE empleado set foto = '".$name."', size = '".$size."' ";
		$strQuery.= "WHERE id_empleado = ".$data->ci." ";

		$strQ = $db->Execute($strQuery);
		$data->img = $img;
	}
	if($data->checksEmail == 'on'){
		//echo 'entra......';
		//include '../../classes/envioData.php';
	}
	//print_r($data);
	/***************************************************************************/

	$sql = "TRUNCATE TABLE auxImgEmp ";
	$strQ = $db->Execute($sql);

	if($sql)
		echo json_encode($data);
	else
		echo 0;
?>
