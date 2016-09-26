<?PHP
include '../adodb5/adodb.inc.php';
include '../inc/function.php';
sleep(2);
$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$fecha = $op->ToDay();
$hora = $op->Time();

$id = $_REQUEST['id'];
$bd = $_REQUEST['bd'];

$data = new stdClass();
$data->coordenada = new stdClass();

$strSql = "SELECT * FROM ".$bd." ";
$strSql.= "WHERE id_".$bd." = '".$id."' ";

$str = $db->Execute($strSql);

while( $row = $str->FetchRow()){
	$data->coordenada->cx = $row['coorX'];
	$data->coordenada->cy = $row['coorY'];
	$data->coordenada->estado = 'ok';
}
//print_r($data);
if($data){
	echo json_encode($data);
}else{
	echo 0;
	}

?>