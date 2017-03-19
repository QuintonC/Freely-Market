<?php

session_start();
require_once("../../db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}
	

$name = $_FILES['picture']['name'];
$temp_name = $_FILES['picture']['tmp_name'];
$size = $_FILES['picture']['size'];
$type = $_FILES['picture']['type'];

if ($size <= 3000000) {
	move_uploaded_file($temp_name,$name);
} else {
	echo 'The file is too large';
	echo 'The file is ' . $size . ' and needs to be less than 500KB';
}

$username = $_SESSION['username'];
	
#Get from forms
$item = $_POST['item'];
$price = $_POST['price'];
$dur = $_POST['dur'];
$descr = $_POST['descr'];
$aid = $_SESSION['uid'];

#Prevent MySQL Injection
$item = strip_tags($item);
$price = strip_tags($price);
$dur = strip_tags($dur);
$descr = strip_tags($descr);

$item = stripslashes($item);
$price = stripslashes($price);
$dur = stripslashes($dur);
$descr = stripslashes($descr);

$status = "Active";

#Create rental listing
$sql = "insert into Rental_Listing (item,price,duration,descr,picture,status,owner,aid) values('$item','$price','$dur','$descr','$name','$status','$username','$aid')";


if ($conn->query($sql) === TRUE) {
	header("Location: ../../account/fm_account.php");
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}



?>

