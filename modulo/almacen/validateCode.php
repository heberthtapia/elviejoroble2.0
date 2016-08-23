<?php
/**
 * Created by PhpStorm.
 * User: TAPIA
 * Date: 22/08/2016
 * Time: 23:33
 */


$response = array(
    'valid' => false,
    'message' => 'Post argument "user" is missing.'
);

if( isset($_POST['idInv']) ) {
   // $userRepo = new UserRepository( DataStorage::instance() );
    //$user = $userRepo->loadUser( $_POST['idInv'] );

    $user = $_POST['idInv'];

    if( $user  == "heberth") {
        // User name is registered on another account
        $response = array('valid' => false, 'message' => 'El codigo ya esta registrado.');
    } else {
        // User name is available
        $response = array('valid' => true);
    }
}
echo json_encode($response);
?>