<?PHP
	session_start();
	
	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';
	
	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();	
	
	$op = new cnFunction();
	
	$fecha = $op->ToDay();    
	$hora = $op->Time();	
	
	$data = stripslashes($_POST['res']);
	
	$data = json_decode($data);
		
	$strQuery = "INSERT INTO empleado (id_empleado, depa,  nombre, apP, apM, dateNac, phone, celular, ";
	$strQuery.= "direccion, coorX, coorY, obser, email, cargo, dateReg, status ) ";
	$strQuery.= "VALUES (".$data->ci.", '".$data->dep."', '".$data->name."', '".$data->paterno."', ";
	$strQuery.= "'".$data->materno."', '".$data->dateNac."', '".$data->fono."', '".$data->celular."',"; 
	$strQuery.= "'".$data->addresC."', '".$data->cx."', '".$data->cy."', '".$data->obser."', '".$data->emailC."', ";
	$strQuery.= "'".$data->cargo."', '".$data->date."', 'Activo' )";
	
	$sql = $db->Execute($strQuery);
	
	$strQuery = "INSERT INTO usuario (id_empleado, user, pass, status ) ";
	$strQuery.= "VALUES ('".$data->ci."', '".$data->codUser."', '".$data->password."', 'Inactivo' )";
	
	$sql = $db->Execute($strQuery);
	
	/*********************ACTUALIZA FOTO Y ENVIANDO DATOS POR EMAIL*******************************/
	
	$data->img = '';		
		
	$strQuery = "SELECT * FROM aux_img ";
	
	$srtQ = $db->Execute($strQuery);
	
	$row = $srtQ->FetchRow();
	
	if ($row[0]!=''){		
		$img = $row['imagen'];
		
		$strQuery = "UPDATE empleado set foto = '".$img."' ";
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
	
	if($sql)	
		echo json_encode($data);
	else
		echo 0;	
?>