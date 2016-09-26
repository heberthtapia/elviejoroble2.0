<?php
	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../inc/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora = $op->Time();
	$data = array('id' => $_REQUEST['res'] );

    $strSql = "SELECT coorX, coorY FROM empleado WHERE id_empleado = '".$data[id]."' ";

    $str = $db->Execute($strSql);
    $file = $str->FetchRow();

    if ($file[0]) {
    	$data = array('x' => $file['coorX'], 'y' => $file['coorY']);

    	echo json_encode($data);
    }else{
    	echo 0;
    }
?>