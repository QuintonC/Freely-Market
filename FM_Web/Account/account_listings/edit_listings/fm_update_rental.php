<?php
session_start();
require_once("../../../db_constant.php");

if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] == true) {
    $log = $_SESSION['username'];
} else {
    echo "Please log in first to see this page.";
}

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
	move_uploaded_file($temp_name,'../../../images/' . $name);
} else {
	echo 'The file is too large';
	echo 'The file is ' . $size . ' and needs to be less than 500KB';
}

$rid = $_GET['id'];
$item = $_POST['item'];
$price = $_POST['price'];
$duration = $_POST['duration'];
$descr = $_POST['descr'];

#Prevent MySQL Injection
$item = strip_tags($item);
$price = strip_tags($price);
$dur = strip_tags($dur);
$descr = strip_tags($descr);

$item = stripslashes($item);
$price = stripslashes($price);
$dur = stripslashes($dur);
$descr = stripslashes($descr);

#Updates Rental_Listing
$sql = "update Rental_Listing set item = '$item', price = '$price', duration = '$duration', descr = '$descr', picture = '$name' where rid = '$rid'";	

if ($conn->query($sql) === TRUE) {
	header("Location: ../fm_listed_rentals.php");
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

?>