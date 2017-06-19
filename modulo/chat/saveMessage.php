<?php
	include '../../adodb5/adodb.inc.php';
	include '../../inc/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();
	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora = $op->Time();

	$userTo		= $_POST['userTo'];
	$userFrom	= $_POST['userFrom'];
	$message	= $_POST['message'];

	$intID = $userTo + $userFrom;

	$sql = "INSERT INTO chat(chatID, sendFrom, sendTo, message, dateSend) VALUES('$intID', '$userFrom', '$userTo', '$message', '".$fecha." ".$hora."')";

	$resp = $db->Execute($sql);

	if($resp)
		echo 1;
	else
		echo 0;
?>
<script>
</script>
