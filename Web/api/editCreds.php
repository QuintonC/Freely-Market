<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POSTting values
    $password = $_POST['password'];
    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $encpw = password_hash($password, PASSWORD_BCRYPT);

    //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    //Insert user into database after confirming if user exists or not
    $result = $db->editCreds($encpw, $first_name, $last_name, $email, $phone);
    if (!$result) {
        $response['error']=true;
        $response['message']='Could not edit information.';
    } else {
        $response['error']=false;
        $response['message']='Information has been edited successfully.';
    }
} else {
    $response['error']=true;
    $response['message']='Something went wrong.';
}

echo json_encode($response);

?>