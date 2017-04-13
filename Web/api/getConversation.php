<?php

//creating a response array to store data
$response = array();
 
//creating a key in the response array to insert values
//this key will store an array iteself
$response['messages'] = array();
 

if($_SERVER['REQUEST_METHOD']=='POST') {
	//POST values
	$user1 = $_POST['user1'];
	$user2 = $_POST['user2'];
 
// 	$user1 = 'amitts';
//	$user2 = 'test1';
 
 
 	//including the file dboperation
	require_once '../includes/DbOperation.php';
	//creating object of class DbOperation
	$db = new DbOperation();
 
	//getting the messages
	$messages = $db->getMessages($user1, $user2);
 
	//looping through all the messages.
	while($message = $messages->fetch_assoc()){
		//creating a temporary array
		$temp = [];
	
		
		$temp['sender'] = $message['sender'];
    	$temp['message'] = $message['message'];
	
		
		//inserting the temporary array inside response
		array_push($response['messages'],$temp);
	}
}
 
//displaying the array in json format
echo json_encode($response);

// 
// //including the file dboperation
// require_once '../includes/DbOperation.php';
//  
// //creating a response array to store data
// $response = array();
//  
// //creating a key in the response array to insert values
// $response['messages'] = array();
//  
// //creating object of class DbOperation
// $db = new DbOperation();
//  
// //getting the messages
// $messages = $db->getMessages('amitts','test1');
//  
// //looping through all the messages
// while($message = $messages->fetch_assoc()){
//     //creating a temporary array
//     $temp = array();
//  
//     //inserting the message in the temporary array
//     $temp['sender'] = $message['sender'];
//     $temp['message']=$message['message'];
//     
//  
//     //inserting the temporary array inside response
//     array_push($response['messages'],$temp);
// }
//  
// //displaying the array in json format
// echo json_encode($response);


?>