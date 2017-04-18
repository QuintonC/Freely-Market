<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POSTting values
    $type = $_POST['type'];
    $item = $_POST['item'];
    $price = $_POST['price'];
    $descr = $_POST['descr'];
    $owner = $_POST['owner'];
    $picture = $_POST['picture'];

    //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    //Insert user into database after confirming if user exists or not

    $aid = $db->getAID($owner);
    if (!$aid) {
        $reponse['error'] = true;
        $response['message'] = 'Could not locate aid';
    } else {
        $createListing = $db->createListing($type, $item, $price, $descr, $aid, $owner, $picture);
        if (!createListing) {
            $response['error'] = true;
            $response['message'] = "Could not create listing";
        } else {
            $response['error'] = false;
            $response['message'] = "Listing created successfully";
        }
    }
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}

echo json_encode($response);

?>