<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POSTting values
    $username = $_POST['username'];
    $password = $_POST['password'];
    $encpw = md5($password);

    //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    //Check login credentials
    $credCheck = $db->checkCredentials($username, $encpw);
    if (!$credCheck) {
        $response['error']=true;
        $response['message']='Incorrect password or username combination.';
    } else {
        $response['error']=false;
        $response['message']='Success, you are being logged in.';
    }
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}

echo json_encode($response);

?>