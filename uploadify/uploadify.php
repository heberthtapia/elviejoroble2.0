<?php
include '../adodb5/adodb.inc.php';
include '../inc/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$sql = "TRUNCATE TABLE aux_img ";
$strImg = $db->Execute($sql);

/* Nombre de la imagen */

$nameImg = $_POST['name'];

$fecha = $op->ToDay();
$hora = $op->Time();

$fecha = str_replace('-','',$fecha);
$hora = str_replace('-','',$hora);
$hora = str_replace(':','',$hora);

/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
*/

// Define a destination
$targetFolder = $_POST['path']; // Relative to the root
//echo $ad = $_REQUEST['directorio'];
if (!empty($_FILES)) {

	//echo $nom = $_POST['someKey'];

	//$name = $_FILES['Filedata']['name'];
	$name = md5($_SERVER['REMOTE_ADDR'].rand()).'.jpg';
	//$name = $nameImg.'-'.$fecha.$hora.'.jpg';

	$tempFile = $_FILES['Filedata']['tmp_name'];
	//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetPath = '../modulo/'.$targetFolder.'/uploads';
	$targetFile = rtrim($targetPath,'/') . '/' . $name;//$_FILES['Filedata']['name'];

	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($name);//$_FILES['Filedata']['name']);

	//$name = $_FILES['Filedata']['name'];

	$strQuery = "INSERT INTO aux_img( imagen ) ";
	$strQuery.= "VALUES( '".$name."' ) ";

	$srt = $db->Execute($strQuery);

	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo ($name);
	} else {
		echo 'Invalid file type.';
	}
}
?>