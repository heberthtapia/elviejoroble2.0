<?php
session_start();
include '../adodb5/adodb.inc.php';
$db = NewADOConnection('mysqli');
$db->Connect();

$strEmp = "SELECT COUNT(*) FROM cliente WHERE id_empleado = ".$_SESSION['idEmp']." ";
$strNum = $db->Execute($strEmp);
$NumRow = $strNum->FetchRow();

echo ($NumRow[0]);

?>