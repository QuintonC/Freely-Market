<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POSTting values
    $type = $_POST['type'];
    $item = $_POST['item'];
    $price = $_POST['price'];
    $descr = $_POST['descr'];
    $picture = $_POST['picture'];
    $id = $_POST['id'];

    //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    //Insert user into database after confirming if user exists or not


    $editListing = $db->editListing($type, $item, $price, $descr, $picture, $id);
    if (!editListing) {
        $response['error'] = true;
        $response['message'] = "Could not edit listing";
    } else {
        $response['error'] = false;
        $response['message'] = "Listing edited successfully";
    }
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}

echo json_encode($response);

?>