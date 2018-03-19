<?php

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$id = $_REQUEST['id'];

//$data = stripslashes($_POST['id']);

//$data = json_decode($data);

$data = new stdClass();

//$result = $_REQUEST['result'];

$sql = "SELECT foto FROM empleado WHERE id_empleado = ".$id."";

$srtQuery = $db->Execute($sql);
$c = 0;
while( $row = $srtQuery->FetchRow()){
	$data->id[$c] = $row[0];
	$c++;
}

//print_r($data);

//echo (count($result));

//echo ("<br><br>");

//print_r($result);

//elimino el tercer elemento del array y muestro el array
//unset($result[files][0]);

//print_r($result);

//elimino el primer y segundo elemento del array y muestro el array
//unset($result=>files[0],$result=>files[2]);
//var_export ($array1);

//$data->id = $id;

echo json_encode($data);


 ?>