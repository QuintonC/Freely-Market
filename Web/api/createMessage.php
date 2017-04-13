<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POSTting values
    $sender = $_POST['sender'];
    $reciever = $_POST['reciever'];
    $message = $_POST['message'];
   

    //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();

    //Insert message into database
    $userExists = $db->findUser($reciever);
    if (!$userExists) {
        $response['error'] = true;
        $response['message'] = 'Could not send message';
    } else {
        $result = $db->createMessage($sender, $reciever, $message);
        if (!$result) {
            $response['error']=true;
            $response['message']='Could not send message';
        } else {
            $response['error']=false;
            $response['message']='Message successfully sent';
        }
    }
} else {
    $response['error']=true;
    $response['message']='You are not authorized';
}

echo json_encode($response);

?>