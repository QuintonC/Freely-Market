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
$psid = $_GET['id'];

#Get sale listing id 
$mysql = "select bid from Pending_Sale where psid = '$psid'";
$result = $conn->query($mysql);
$row = mysqli_fetch_array($result);
$bid = $row['bid'];

#Delete listing from pending sale
$sql1 = "delete from Pending_Sale where bid = '$bid'";

if ($conn->query($sql1) === TRUE) {
	header("Location: fm_account.php");
	exit;
} else {
	echo "Error: " . $sql . "<br>" . $conn->error;
}

?>