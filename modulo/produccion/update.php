<?php
/**
 * Created by PhpStorm.
 * User: TAPIA
 * Date: 28/06/2016
 * Time: 14:26
 */
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

$strQuery = "UPDATE produccion SET dateInc = '".$fecha." ".$hora."', ";
$strQuery.= "id_inventario = '".$data->idInvU."', detalle = '".$data->detalleU."', cantidad = '".$data->cantU."' ";
$strQuery.= "WHERE id_produccion = '".$data->idU."' ";

$sql = $db->Execute($strQuery);

if($sql)
    echo json_encode($data);
else
    echo 0;
?>