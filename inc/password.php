<?PHP
	ini_set("session.use_trans_sid","0");
	ini_set("session.use_only_cookies","1");
	ini_set("register_long_array","on");
	
	session_start();	
	date_default_timezone_set("America/La_Paz" ) ;	
	session_set_cookie_params(0,"/",$_SERVER["HTTP_HOST"],0);

	include '../adodb5/adodb.inc.php';
	
	$db = NewADOConnection('mysqli');
		
	$db->Connect();
	
	$data = stripslashes($_POST['res']);		
	$data = json_decode($data);
	
	$strQuery = "SELECT * FROM usuario WHERE user = '".$data->username."' ";
	$strQuery.= "AND pass = '".$data->password."' ";
	
	$strSql = $db->Execute($strQuery);
	$row = $strSql->FetchRow();
	
	if( $row['status'] == 'Activo' ){	
		$fechaGuardada = $row['dateReg'];
		$ahora = date("Y-n-j H:i:s");		
		$tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));
		
		if($tiempo_transcurrido>2160){			
			$strQuery = 'UPDATE usuario SET status = "Inactivo", dateReg = "0000-00-00 00:00:00" WHERE id_usuario = "'.$row['id_usuario'].'"';
			$str = $db->Execute($strQuery);
		}		
	}
	
	$sql = 'SELECT * '; 
	$sql.= 'FROM empleado AS e, usuario AS u ';
	$sql.= 'WHERE u.user = "'.$data->username.'" AND u.pass = "'.$data->password.'" ';
	$sql.= 'AND e.id_empleado = u.id_empleado ' ;
	$sql.= 'AND e.status = "Activo" ';
	$sql.= 'AND u.status= "Inactivo"';
	
	$strSql = $db->Execute($sql);
	$reg = $strSql->FetchRow();
	$num = $strSql->RecordCount();
	
	$_SESSION['idEmp'] = $reg['id_empleado'];
	$_SESSION['cargo']	= $reg['cargo'];
	
	$data->cargo = $reg['cargo'];
	
	if($num == 1){
		$strQuery = 'UPDATE usuario SET status = "Activo", dateReg = "'.date("Y-n-j H:i:s").'" WHERE id_usuario = "'.$reg['id_usuario'].'"';
		$str = $db->Execute($strQuery);		
		$_SESSION["idUser"] = $reg['id_usuario'];
		$_SESSION["ultimoAcceso"] = date("Y-n-j H:i:s");	
		echo json_encode($data);
	}else{
		$_SESSION["idUser"] = NULL;
		echo 0;
	}
	
?>