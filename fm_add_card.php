<?php

#Initiates session variables
session_start();

#References data base connection variables
require_once("db_constant.php");
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}

#Gets form entries
$name = $_POST['name'];
$number = $_POST['number'];
$expr = $_POST['expr'];
$cvv = $_POST['cvv'];
$aid = $_SESSION['aid'];

#query creates card info for user account
$sql = "INSERT INTO CardInfo (card_name,card_number,expr,cvv,aid) VALUES ('$name','$number','$expr','$cvv','$aid')";


if ($conn->query($sql) === TRUE) {
	#If query is successful then the user is redirected to the log in page
	header("Location: fm_login.html");
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}


?>







