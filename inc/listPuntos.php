<?php
session_start();

//$id_cel = $_SESSION['CEL'];

include("../adodb5/adodb.inc.php");

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$status = $_POST['status'];
$zona   = $_POST["zona"];
$fecha  = $_POST["fecha"];

$sql = "SELECT *
        FROM cliente
        WHERE  id_empleado = ".$_SESSION['idEmp']."
        AND coorX != '' ";

if ($zona != '') {
    $sql.= "AND nom_zona like '%".$zona."%' ";
}

$Query = $db->Execute($sql);

$data = new stdClass();
$c=0;

while ($reg = $Query->FetchRow()) {

    if ($reg['coorX'] != '') {

        $id = $reg['id_cliente'];

        $data->cx[$c]      = $reg['coorX'];
        $data->cy[$c]      = $reg['coorY'];
        $data->nombre[$c]  = $reg['nombre'];
        $data->avenida[$c] = $reg['calle'];
        $data->nomAve[$c]  = $reg['nom_calle'];
        $data->num[$c]     = $reg['numero'];
        $data->zona[$c]    = $reg['zona'];
        $data->nomZona[$c] = $reg['nom_zona'];

        $sqlQuery = "SELECT * FROM status_cliente WHERE id_cliente = '".$id."' AND fecha = '".$fecha."' ORDER BY (idstatus_cliente) ASC ";
        $query = $db->Execute($sqlQuery);
        $row = $query->FetchRow();

        if ($row->status == ''){
            $data->status[$c]  = $reg['estado'];
        }else{
            $data->status[$c]  = $row['estado'];
        }

        $c++;
    }

}
//print_r($data);
if($data){
    echo json_encode($data);
}else{
    echo 0;
    }

?>
