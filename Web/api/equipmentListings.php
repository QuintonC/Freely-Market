<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='GET') {

   //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    $listings = $db->equipmentListings();

    //loop through all of the listings
    $response['listing'] = array();


    while($listing = $listings->fetch_assoc()) {
    	$temp = array();
        //echo $listing;
        //print_r($listing);

    	$temp['item'] = $listing['item'];
    	$temp['price'] = $listing['price'];
    	$temp['picture'] = $listing['picture'];

    	array_push($response['listing'], $listing);
    }
    
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}


echo json_encode($response);

?>