<?php

//creating response array
$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

    //POSTting values
    $user = $_POST['user'];

    //$encpw = password_hash($password, PASSWORD_BCRYPT);

    //including the db operation file
    require_once '../includes/DbOperation.php';

    $db = new DbOperation();
	
	$contacts = $db->getContacts($user);
    
}

echo json_encode($contacts);





//   $host = "db.soic.indiana.edu";
//   $user = "caps16_team12";
//   $password = "my+sql=caps16_team12";
//   
//   $connection = mysql_connect($host, $user, $password);
//   
//   if(!$connection) {
//   	die('Connection Failed');
//   } else {
//   	$dbconnect = @mysql_select_db('caps16_team12', $connection);
//   	
//   	if(!$dbconnect) {
//   		die('Could not connect to database');
//   	} else {
//   		$query = "SELECT sender, reciever FROM Messages WHERE sender = 'oklightning' OR reciever = 'oklightning';";
//   		$resultset = mysql_query($query, $connection);
//   		
//   		$records = array();
//   		
//   		while($r = mysql_fetch_assoc($resultset)) {
//   			$records[ ] = $r;
//   		}
//   		
//   		echo json_encode($records);
//   	}
//   }


?>