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

    //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    //Insert user into database after confirming if user exists or not

    $aid = $db->getAID($owner);
    if (!$aid) {
        $reponse['error'] = true;
        $response['message'] = 'Could not locate aid';
    } else {
        $createListing = $db->createListing($type, $item, $price, $descr, $aid, $owner);
        if (!createListing) {
            $response['error'] = true;
            $response['message'] = "Could not create listing";
        } else {
            $response['error'] = false;
            $response['message'] = "Listing created successfully";
        }
    }








    //$aid = $db->getAID($owner);
    //echo($aid);
    // if (!$aid) {
    //     $response['error']=true;
    //     $response['error']='Could not locate aid for this user.';
    // } else {
    //     echo($aid);
    //     $createListing = $db->createListing($type, $item, $price, $descr, $aid, $owner);
        // if (!$createListing) {
        //     $response['error']=true;
        //     $response['message']='Could not create listing';
        // } else {
        //     $response['error']=false;
        //     $response['message']='Listing created successfully';
        // }
    //} 
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}

echo json_encode($response);

?>