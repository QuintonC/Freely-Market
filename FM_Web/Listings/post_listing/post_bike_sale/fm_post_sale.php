<?php
session_start();

require_once("../../../db_constant.php");
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

if ($size <= 5000000) {
	move_uploaded_file($temp_name,'../../../images/' . $name);
} else {
	echo 'The file is too large';
	echo 'The file is ' . $size . ' and needs to be less than 500KB';
}
	
#Get from forms
$item = $_POST['item'];
$price = $_POST['price'];
$descr = $_POST['descr'];
$aid = $_SESSION['uid'];

#Prevent MySQL Injection
$item = strip_tags($item);
$price = strip_tags($price);
$descr = strip_tags($descr);

$item = stripslashes($item);
$price = stripslashes($price);
$descr = stripslashes($descr);


$status = "Active";
$typ = "User";
$username = $_SESSION['username'];

#Create Sale Listing
$sql = "insert into Buy_Listing (item,price,descr,picture,status,aid,typ,owner) values('$item','$price','$descr','$name','$status','$aid','$typ','$username')";
if ($conn->query($sql) === TRUE) {
	header("Location: ../../../account/fm_account.php");
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}
?>


