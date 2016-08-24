<?php
/**
 * Created by PhpStorm.
 * User: TAPIA
 * Date: 22/08/2016
 * Time: 23:33
 */
include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$response = array(
    'valid' => false,
    'message' => 'Post argument "user" is missing.'
);

if( isset($_POST['idInv']) ) {
   // $userRepo = new UserRepository( DataStorage::instance() );
    //$user = $userRepo->loadUser( $_POST['idInv'] );

    $sql = "SELECT id_inventario FROM inventario WHERE id_inventario = '".$_POST['idInv']."' ";

    $strQ = $db->Execute($sql);

    $row = $strQ->FetchRow();

    if( $_POST['idInv'] == $row[0] ) {
        // User name is registered on another account
        $response = array('valid' => false, 'message' => 'El codigo ya esta registrado.');
    } else {
        // User name is available
        $response = array('valid' => true);
    }
}
echo json_encode($response);
?>