<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POSTting values
    $type = $_POST['type'];
    $id = $_POST['id'];

    //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    //Insert user into database after confirming if user exists or not


    $deleteListing = $db->deleteListing($type, $id);
    if (deleteListing == false) {
        $response['error'] = true;
        $response['message'] = "Could not delete listing";
    } else {
        $response['error'] = false;
        $response['message'] = "Listing deleted successfully";
    }
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}

echo json_encode($response);

?>