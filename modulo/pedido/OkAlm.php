<?PHP
session_start();

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');

$db->Connect();

$op = new cnFunction();

$fecha = $op->ToDay();
$hora = $op->Time();

$data = stripslashes($_POST['res']);

$data = json_decode($data);

	//print_r($data);

/* POR SI ACASO SI ES NECESARIO ACTUALIZAR ALGUN DATO */
$strSql = "SELECT * FROM pedido AS p, pedidoEmp AS pe ";
$strSql.= "WHERE p.id_pedido = pe.id_pedido ";
$strSql.= "AND p.id_pedido = '".$data->pedido."' ";

$str = $db->Execute($strSql);
$file = $str->FetchRow();

$data->OkAlm = 0;

//if($file['status'] == 'Activo'){

	if($file['status2'] == 'No Entregado'){

		$status = 'Entregado';

	}elseif( $file['status1'] == 'Aprobado' && $file['status2'] == 'Entregado'){

		$status = 'Entregado';

	}else{

		$status = 'No Entregado';
		$data->OkAlm = 1;

	}

	/* ACTUALIZAR EL STATUS DEL CONTADOR....!!! */

	$strSql = "UPDATE pedido SET status2 = '".$status."', dateStatus2 = '".$fecha." ".$hora."' ";
	$strSql.= "WHERE id_pedido = '".$data->pedido."' ";

	$reg = $db->Execute($strSql);

	/* -------------------------------------------------------- */
//}

$data->tabla = 'pedido';
	//print_r($data);
if($reg)
	echo json_encode($data);
else
	echo 0;
?>