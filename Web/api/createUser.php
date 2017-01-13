<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POSTting values
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first_name = $_POST['fname'];
    $last_name = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];


    //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    //inserting values 

    $result = $db->createUser($username, $password, $first_name, $last_name, $email, $phone);
    if (!$result) {
        $response['error']=true;
        
        $response['password']=$password;
        $response['message']='Could not create user';
        
    } else {
        $response['error']=false;
        $response['message']='User created successfully';
    }

}else{
    $response['error']=true;
    $response['message']='You are not authorized';
}

echo json_encode($response);

?>