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
echo $strSql.= "AND p.id_pedido = '".$data->pedido."' ";

$str = $db->Execute($strSql);
$file = $str->FetchRow();

$data->OkCont = 0;

if($file['status'] == 'Activo'){

	if($file['status1'] == 'Pendiente'){
		$status = 'Aprobado';

		$sql = "UPDATE pedido SET status = 'Inactivo' ";
		$sql.= "WHERE id_pedido = '".$data->pedido."' ";

		$sql = $db->Execute($sql);

	}elseif( $file['status1'] == 'Aprobado' && $file['status2'] == 'Entregado'){
		$status = 'Aprobado';

		$sql = "UPDATE pedido SET status = 'Inactivo' ";
		$sql.= "WHERE id_pedido = '".$data->pedido."' ";

		$sql = $db->Execute($sql);

	}else{
		$status = 'Pendiente';
		$data->OkCont = 1;

		$sql = "UPDATE pedido SET status = 'Activo' ";
		$sql.= "WHERE id_pedido = '".$data->pedido."' ";

		$sql = $db->Execute($sql);
	}

	/* ACTUALIZAR EL STATUS DEL CONTADOR....!!! */

	$strSql = "UPDATE pedido SET status1 = '".$status."', dateStatus1 = '".$fecha." ".$hora."' ";
	$strSql.= "WHERE id_pedido = '".$data->pedido."' ";

	$sql = $db->Execute($strSql);

	/* -------------------------------------------------------- */
}

$data->tabla = 'pedido';

echo json_encode($data);

?>