<?php
	session_start();
	session_destroy();
	
	include("../adodb5/adodb.inc.php");
	
	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();
	
	$user = $_REQUEST['user'];
	$strQuery = 'UPDATE usuario SET status = "Inactivo", dateReg = "0000-00-00 00:00:00" WHERE id_usuario = "'.$user.'"';
	$str = $db->Execute($strQuery);
	
	header("location:../index.php"); 
?>