<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='GET') {

   //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    $response[] = $row;
    
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}

echo json_encode($response);

?>