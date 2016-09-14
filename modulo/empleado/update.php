<?PHP
	session_start();
	
	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';
	
	$db = NewADOConnection('mysqli');
		
	$db->Connect();
	
	$op = new cnFunction();
	
	$fecha = $op->ToDay();    
	$hora = $op->Time();	

	$data = stripslashes($_POST['res']);
	
	$data = json_decode($data);
	
	//print_r($data);
			
	/* ACTUALIZACION DE EMPLEADO */	
	$strQuery = "UPDATE empleado SET nombre = '".$data->name."', apP = '".$data->paterno."', apM = '".$data->materno."', dateNac = '".$data->dateNac."', phone = '".$data->fono."', celular = '".$data->celular."', ";
	$strQuery.= "direccion = '".$data->addres."', zona = '".$data->zona."', obser = '".$data->obser."' ";
	$strQuery.= "email  = '".$data->email."' ";
	$strQuery.= "WHERE id_empleado = '".$data->ci."' ";
	
	$sql = $db->Execute($strQuery);
	
	$strQuery = "UPDATE usuario SET user ='".$data->codUser."', pass = '".$data->password."', status = 'Inactivo' ";
	$strQuery.= "WHERE id_empleado = '".$data->ci."' ";
	
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