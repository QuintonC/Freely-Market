<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POSTting values
    $username = $_POST['username'];

    //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    //Check to see if username exists
    $findUser = $db->findUser($username);
    if ($findUser) {
        $response['error']=false;
        $response['message']='User Found';
    } else {
        $response['error']=true;
        $response['message']='User with that username does not exist.';
    }
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}

echo json_encode($response);

?>