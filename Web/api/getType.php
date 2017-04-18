<?php

//creating response array
$response = "0";

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POST USERNAME
    $username = $_POST['username'];

   //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    $userType = $db->getUserType($username);

    echo $userType;
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}

?>