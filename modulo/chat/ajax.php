<?php
	include '../../modulo/chat/lib/Pusher.php';
	include '../../adodb5/adodb.inc.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$mensaje = $_POST['msj'];
	$userFrom = $_POST['userFrom'];
	$userTo = $_POST['userTo'];

	$sql = 'SELECT foto FROM empleado ';
	$sql.= 'WHERE id_empleado = '.$userFrom.'';

	$srtQuery = $db->Execute($sql);

	$row = $srtQuery->FetchRow();

	if($row['foto'] == '') $row['foto'] = 'sin_imagen.jpg';

	$options = array(
		//'encrypted' => true
	);

	$pusher = new Pusher(
	   '4e89620472fb0a58c62c',
	   '47127d9bfb1a9485ccc2',
	   '306675',
	   $options
	);

	$pusher->trigger(
		'canal_chat',
		'nuevo_comentario',
		array('mensaje' => $mensaje, 'userFrom' => $userFrom, 'userTo' => $userTo, 'foto' => $row['foto']),
		$_POST['socket_id']
	);

	echo json_encode( array('mensaje' => $mensaje, 'userFrom' => $userFrom , 'userTo' => $userTo, 'foto' => $row['foto']));
 ?>
