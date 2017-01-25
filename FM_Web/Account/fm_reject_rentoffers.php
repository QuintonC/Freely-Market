<?php

session_start();
require_once("db_constant.php");


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	# check connection
	if ($mysqli->connect_errno) {
		echo "<p>MySQL error no {$mysqli->connect_errno} : {$mysqli->connect_error}</p>";
		exit();
	}

#Get from url
$prid = $_GET['id'];

#Get rental listing id 
$mysql = "select rid from Pending_Rental where prid = '$prid'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$rid = $row['rid'];

#Delete listing from pending rental
$sql1 = "delete from Pending_Rental where rid = '$rid'";

if ($conn->query($sql1) === TRUE) {
	header("Location: fm_account.php");
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

?>