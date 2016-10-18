<?php
/**
 * Created by PhpStorm.
 * User: TAPIA
 * Date: 22/08/2016
 * Time: 23:33
 */

session_start();

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$fecha = $op->ToDay();
$hora = $op->Time();


$response = array(
    'valid' => false,
    'message' => 'Post argument "user" is missing.'
);

if( isset($_POST['ci']) ) {
   // $userRepo = new UserRepository( DataStorage::instance() );
    //$user = $userRepo->loadUser( $_POST['idInv'] );

    $user = $_POST['ci'];

    $query = "SELECT ci FROM cliente WHERE ci = '$user'";
    $sql = $db->Execute($query);
    $row = $sql->FetchRow();

    if( $user  == $row[0]) {
        // User name is registered on another account
        $response = array('valid' => false, 'message' => 'El CI ya esta registrado.');
    } else {
        // User name is available
        $response = array('valid' => true);
    }
}
echo json_encode($response);
?>