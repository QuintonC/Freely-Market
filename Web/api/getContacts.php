<?php

 
//creating a response array to store data
$response = array();
 
//creating a key in the response array to insert values
//this key will store an array iteself
$response['contacts'] = array();
 

if($_SERVER['REQUEST_METHOD']=='POST') {
	//POST values
	$user = $_POST['user'];
 
 	//including the file dboperation
	require_once '../includes/DbOperation.php';
	//creating object of class DbOperation
	$db = new DbOperation();
 
	//getting the contacts
	$contacts = $db->getContacts($user);
 
	//looping through all the contacts.
	while($contact = $contacts->fetch_assoc()){
		//creating a temporary array
		$temp = [];
	
		$sender = $contact['sender'];
		$reciever = $contact['reciever'];
	
		if ($user == $sender) {
			if (in_array($reciever, $response['contacts'])) {
			} else {
				$temp = $reciever;
				//inserting the temporary array inside response
				array_push($response['contacts'],$temp);
			}
		} else {
			if (in_array($sender, $response['contacts'])) {
			} else {
				$temp = $sender;
				//inserting the temporary array inside response
				array_push($response['contacts'],$temp);
			}
		}
	}
}
 
//displaying the array in json format
echo json_encode($response);

?>