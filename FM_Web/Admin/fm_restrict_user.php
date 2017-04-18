<?php

session_start();
require_once("../db_constant.php");

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
	
#Get ID from url
$aid = $_GET['id'];

#Delete Sale Listing
$sql = "Update User_Accounts Set active = 2 where aid = '$aid'";	

#Remove Listings of newley restricted user
$asql = "Update Buy_Listing Set status = 'Inactive' where aid = '$aid'";	
$bsql = "Update Equipment_Listing Set status = 'Inactive' where aid = '$aid'";
$csql = "Update Rental_Listing Set status = 'Inactive' where aid = '$aid'";

if ($conn->query($sql) === TRUE and $conn->query($asql) === TRUE and $conn->query($bsql) === TRUE and $conn->query($csql) === TRUE) {
	header("Location: fm_admin_view_users.php");
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

?>