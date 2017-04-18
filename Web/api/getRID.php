<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POST USERNAME
    $item = $_POST['item'];
    $price = $_POST['price'];
    $descr = $_POST['descr'];
    $picture = $_POST['picture'];

   //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    $rid = $db->getRID($item, $price, $descr, $picture);

    $response['ID:']=$rid;
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}

echo json_encode($response);

?>