<?php
	session_start();

	include '../adodb5/adodb.inc.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$idEmp = $_SESSION['idEmp'];

	$sql  = "SELECT * ";
	$sql .= "FROM inventario ";

	$strQuery = $db->Execute($sql);

sleep( 3 );
// no term passed - just exit early with no response
if (empty($_GET['term'])) exit ;
$q = strtolower($_GET["term"]);
// remove slashes if they were magically added
if (get_magic_quotes_gpc()) $q = stripslashes($q);

$items = array();

	while( $row = $strQuery->FetchRow()){
		$items[$row['id_inventario']] = $row['detalle'];
	}


$result = array();
foreach ($items as $key=>$value) {

	if (strpos(strtolower($key), $q) !== false) {
		array_push($result, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
	}
	if (count($result) > 11)
		break;
}

// json_encode is available in PHP 5.2 and above, or you can install a PECL module in earlier versions
echo json_encode($result);
?>