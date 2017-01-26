<?php

session_start();
require_once("db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}

#Get from forms
$item = $_POST['item'];
$price = $_POST['price'];
$descr = $_POST['descr'];
$picture = $_POST['picture'];
$aid = $_SESSION['uid'];

#Prevent MySQL Injection
$item = strip_tags($item);
$price = strip_tags($price);
$descr = strip_tags($descr);
$picture = strip_tags($picture);

$item = stripslashes($item);
$price = stripslashes($price);
$descr = stripslashes($descr);
$picture = stripslashes($picture);

#Create Sale Listing
$sql = "insert into Buy_Listing (item,price,descr,picture,aid) values('$item','$price','$descr','$picture','$aid')";


if ($conn->query($sql) === TRUE) {
	header("Location: fm_account.php");
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}


?>

