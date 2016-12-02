<?php
/**
 * Created by PhpStorm.
 * User: TAPIA
 * Date: 28/06/2016
 * Time: 18:02
 */
  include '../../adodb5/adodb.inc.php';
  include '../../inc/function.php';

  $db = NewADOConnection('mysqli');
  //$db->debug = true;
  $db->Connect();

  $op = new cnFunction();

  $fecha = $op->ToDay();
  $hora = $op->Time();

  $data = stripslashes($_POST['res']);

  $data = json_decode($data);

  $q = "DELETE FROM produccion WHERE id_produccion = '".$data->id."' ";
  $reg = $db->Execute($q);

 if($reg)
    echo json_encode($data);
  else
    echo 0;
?>