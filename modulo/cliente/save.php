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

	$data  = $_POST['res'];

	$data  = json_decode($data);

	$strQuery = "INSERT INTO cliente (id_cliente, ci, depa, id_empleado, nombre, apP, apM, phone, celular, calle, nom_calle, numero, zona, nom_zona, ";
	$strQuery.= "departamento, direccion_des, nombreEmp, nit, email, coorX, coorY, obser, dateReg, status ) ";
	$strQuery.= "VALUES ('".trim($data->codCl)."', '".trim($data->ci)."', '".trim($data->dep)."', '".$_SESSION['idEmp']."', '".trim($data->name)."', '".trim($data->paterno)."', ";
	$strQuery.= "'".trim($data->materno)."', '".trim($data->fono)."', '".trim($data->celular)."', '".trim($data->calle)."', '".trim($data->nom_calle)."', ";
	$strQuery.= "'".trim($data->Nro)."', '".trim($data->zona)."', '".trim($data->nom_zona)."', '".trim($data->departamento)."', '".trim($data->direccion)."', ";
	$strQuery.= "'".trim($data->nameEmp)."', '".trim($data->nit)."', '".trim($data->email)."', ";
	$strQuery.= "'".trim($data->cx)."', '".trim($data->cy)."', '".trim($data->obser)."', ";
	$strQuery.= "'".trim($data->date)."', 'Activo' )";

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
