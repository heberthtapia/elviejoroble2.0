<?php
/**
 * Created by PhpStorm.
 * User: SONY
 * Date: 23/9/2016
 * Time: 16:25
 */
session_start();

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$data = stripslashes($_POST['res']);

$data = json_decode($data);

?>

<?PHP
    $sqle = "SELECT * ";
    $sqle.= "FROM empleado ";
    $sqle.= "WHERE id_empleado = $data->pre ";

    $srtQuery = $db->Execute($sqle);

    $rowe = $srtQuery->FetchRow();

    $data->id = $rowe['id_empleado'];
    $data->nombre = $rowe['nombre'];
    $data->paterno = $rowe['apP'];
    $data->materno = $rowe['apM'];

    if($rowe)
        echo json_encode($data);
    else
        echo 0;
?>